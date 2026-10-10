<?php

namespace Tests\Unit;

use App\DTOs\InstagramPost\InstagramPostMediaDto;
use App\DTOs\InstagramPost\InstagramPostStoreDto;
use App\Http\Requests\Admin\InstagramPost\InstagramPostCoverRequest;
use App\Http\Requests\Admin\InstagramPost\StoreInstagramPostRequest;
use App\Http\Resources\InstagramPostMedia\InstagramPostMediaResource;
use App\Models\InstagramPost;
use App\Models\InstagramPostMedia;
use App\Repositories\InstagramPost\InstagramPostRepositoryInterface;
use App\Repositories\InstagramPostMedia\InstagramPostMediaRepositoryInterface;
use App\Services\InstagramPost\InstagramPostService;
use App\Services\S3\S3ServiceInterface;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Mockery;
use PHPUnit\Framework\TestCase;

class InstagramMediaCoverTest extends TestCase
{
    private $posts;
    private $media;
    private $storage;
    private InstagramPostService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $container = new Container;
        Container::setInstance($container);
        Facade::setFacadeApplication($container);
        Validator::swap(new Factory(new Translator(new ArrayLoader, 'en'), $container));
        $this->posts = Mockery::mock(InstagramPostRepositoryInterface::class);
        $this->media = Mockery::mock(InstagramPostMediaRepositoryInterface::class);
        $this->storage = Mockery::mock(S3ServiceInterface::class);
        $this->service = new InstagramPostService($this->posts, $this->media, $this->storage);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(null);
        Container::setInstance(null);
        parent::tearDown();
    }

    private function video(): InstagramPostMedia
    {
        $video = (new InstagramPostMedia)->forceFill(['instagram_post_id' => 4, 'type' => 'video', 'file' => 'clip.mp4', 'cover' => 'old.jpg']);
        $video->id = 9;
        return $video;
    }

    public function test_cover_indices_survive_mixed_and_sparse_upload_arrays(): void
    {
        $post = new InstagramPost;
        $post->id = 4;
        $photo = UploadedFile::fake()->image('photo.jpg');
        $video = UploadedFile::fake()->create('clip.mp4', 10, 'video/mp4');
        $legacy = UploadedFile::fake()->create('legacy.mp4', 10, 'video/mp4');
        $cover = UploadedFile::fake()->image('cover.png');
        $this->posts->shouldReceive('findWithMedia')->with(4)->once()->andReturn($post);
        $this->media->shouldReceive('nextSort')->with(4)->once()->andReturn(7);
        foreach ([[$photo, 'photo.jpg'], [$video, 'clip.mp4'], [$legacy, 'legacy.mp4'], [$cover, 'cover.png']] as [$file, $name]) {
            $this->storage->shouldReceive('upload')->with($file, 'instagram')->once()->andReturn($name);
        }
        $this->media->shouldReceive('create')->times(3)->andReturnUsing(fn ($attributes) => (new InstagramPostMedia)->forceFill($attributes));

        $items = $this->service->addMedia(new InstagramPostMediaDto(4, [2 => $photo, 5 => $video, 9 => $legacy], [5 => $cover]));

        self::assertSame([null, 'cover.png', null], array_map(fn ($item) => $item->cover, $items));
        self::assertSame([7, 8, 9], array_map(fn ($item) => $item->sort, $items));
        self::assertSame(['image', 'video', 'video'], array_map(fn ($item) => $item->type, $items));
    }

    public function test_creating_a_post_persists_its_video_cover(): void
    {
        $post = new InstagramPost;
        $post->id = 4;
        $video = UploadedFile::fake()->create('clip.mp4', 10, 'video/mp4');
        $cover = UploadedFile::fake()->image('cover.jpg');
        $this->posts->shouldReceive('create')->once()->andReturn($post);
        $this->posts->shouldReceive('findWithMedia')->with(4)->once()->andReturn($post);
        $this->storage->shouldReceive('upload')->with($video, 'instagram')->once()->andReturn('clip.mp4');
        $this->storage->shouldReceive('upload')->with($cover, 'instagram')->once()->andReturn('cover.jpg');
        $this->media->shouldReceive('create')->with(Mockery::on(fn ($data) => $data['cover'] === 'cover.jpg' && $data['sort'] === 0))->once()->andReturn($this->video());

        self::assertSame($post, $this->service->store(new InstagramPostStoreDto(1, 1, [$video], 'کپشن', [$cover])));
    }

    public function test_replacing_a_cover_returns_it_in_the_api_and_removes_the_previous_file(): void
    {
        $video = $this->video();
        $cover = UploadedFile::fake()->image('new.jpg');
        $this->media->shouldReceive('findOrFail')->with(9)->once()->andReturn($video);
        $this->storage->shouldReceive('upload')->with($cover, 'instagram')->once()->andReturn('new.jpg');
        $this->media->shouldReceive('update')->with($video, ['cover' => 'new.jpg'])->once()->andReturnUsing(function ($model, $attributes) {
            $model->forceFill($attributes);
            return true;
        });
        $this->storage->shouldReceive('remove')->with('instagram/old.jpg')->once();

        $result = $this->service->updateCover(9, $cover);
        $json = (new InstagramPostMediaResource($result))->resolve(Request::create('/'));
        self::assertSame('new.jpg', $json['cover']);
        self::assertSame('clip.mp4', $json['file']);
    }

    public function test_failed_cover_save_keeps_the_old_file_and_cleans_up_the_new_upload(): void
    {
        $video = $this->video();
        $this->media->shouldReceive('findOrFail')->with(9)->once()->andReturn($video);
        $this->storage->shouldReceive('upload')->once()->andReturn('new.jpg');
        $this->media->shouldReceive('update')->once()->andReturn(false);
        $this->storage->shouldReceive('remove')->with('instagram/new.jpg')->once();
        $this->expectException(\RuntimeException::class);
        $this->service->updateCover(9, UploadedFile::fake()->image('new.jpg'));
    }

    public function test_photos_cannot_receive_a_video_cover(): void
    {
        $photo = (new InstagramPostMedia)->forceFill(['type' => 'image', 'file' => 'photo.jpg']);
        $this->media->shouldReceive('findOrFail')->with(9)->once()->andReturn($photo);
        $this->expectException(ValidationException::class);
        $this->service->updateCover(9, UploadedFile::fake()->image('cover.jpg'));
    }

    public function test_failed_media_save_cleans_up_both_uploaded_files(): void
    {
        $post = new InstagramPost;
        $post->id = 4;
        $video = UploadedFile::fake()->create('clip.mp4', 10, 'video/mp4');
        $cover = UploadedFile::fake()->image('cover.jpg');
        $this->posts->shouldReceive('findWithMedia')->with(4)->once()->andReturn($post);
        $this->media->shouldReceive('nextSort')->with(4)->once()->andReturn(1);
        $this->storage->shouldReceive('upload')->with($video, 'instagram')->once()->andReturn('clip.mp4');
        $this->storage->shouldReceive('upload')->with($cover, 'instagram')->once()->andReturn('cover.jpg');
        $this->media->shouldReceive('create')->once()->andThrow(new \RuntimeException('Database unavailable'));
        $this->storage->shouldReceive('remove')->with('instagram/clip.mp4')->once();
        $this->storage->shouldReceive('remove')->with('instagram/cover.jpg')->once();
        $this->expectExceptionMessage('Database unavailable');
        $this->service->addMedia(new InstagramPostMediaDto(4, [$video], [$cover]));
    }

    public function test_deleting_a_video_also_deletes_its_cover(): void
    {
        $video = $this->video();
        $this->media->shouldReceive('findOrFail')->with(9)->once()->andReturn($video);
        $this->storage->shouldReceive('remove')->with('instagram/clip.mp4')->once();
        $this->storage->shouldReceive('remove')->with('instagram/old.jpg')->once();
        $this->media->shouldReceive('delete')->with($video)->once()->andReturn(true);
        self::assertTrue($this->service->removeMedia(9));
    }

    public function test_deleting_a_post_removes_all_of_its_media_and_covers(): void
    {
        $post = new InstagramPost;
        $post->id = 4;
        $post->setRelation('media', collect([$this->video(), (new InstagramPostMedia)->forceFill(['type' => 'image', 'file' => 'photo.jpg'])]));
        $this->posts->shouldReceive('findWithMedia')->with(4)->once()->andReturn($post);
        foreach (['clip.mp4', 'old.jpg', 'photo.jpg'] as $file) {
            $this->storage->shouldReceive('remove')->with('instagram/'.$file)->once();
        }
        $this->media->shouldReceive('deleteByPost')->with(4)->once();
        $this->posts->shouldReceive('delete')->with($post)->once()->andReturn(true);
        self::assertTrue($this->service->destroy(4));
    }

    public function test_upload_validation_accepts_images_and_rejects_invalid_or_oversized_covers(): void
    {
        $rules = (new InstagramPostCoverRequest)->rules();
        self::assertTrue(Validator::make(['cover' => UploadedFile::fake()->image('cover.jpg')], $rules)->passes());
        self::assertFalse(Validator::make([], $rules)->passes());
        self::assertFalse(Validator::make(['cover' => UploadedFile::fake()->create('clip.mp4', 10, 'video/mp4')], $rules)->passes());
        self::assertFalse(Validator::make(['cover' => UploadedFile::fake()->image('large.png')->size(5121)], $rules)->passes());
        self::assertFalse(Validator::make(['cover' => UploadedFile::fake()->create('cover.svg', 1, 'image/svg+xml')], $rules)->passes());

        $input = ['status' => 1, 'media' => [UploadedFile::fake()->create('clip.mp4', 10, 'video/mp4')]];
        self::assertTrue(Validator::make($input, (new StoreInstagramPostRequest)->rules())->passes());
        $input['covers'] = [UploadedFile::fake()->create('invalid.txt', 1, 'text/plain')];
        self::assertFalse(Validator::make($input, (new StoreInstagramPostRequest)->rules())->passes());
    }
}
