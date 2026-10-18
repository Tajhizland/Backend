<?php

namespace Tests\Unit;

use App\DTOs\InstagramProfile\InstagramProfileUpdateDto;
use App\Models\InstagramProfile;
use App\Repositories\InstagramProfile\InstagramProfileRepositoryInterface;
use App\Services\InstagramProfile\InstagramProfileService;
use App\Services\S3\S3ServiceInterface;
use Illuminate\Http\UploadedFile;
use Mockery;
use PHPUnit\Framework\TestCase;

class InstagramProfileServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_failed_save_removes_the_new_upload_and_keeps_the_previous_photo(): void
    {
        $profile = (new InstagramProfile)->forceFill(['avatar' => 'old.jpg']);
        $repository = Mockery::mock(InstagramProfileRepositoryInterface::class);
        $storage = Mockery::mock(S3ServiceInterface::class);
        $repository->shouldReceive('get')->once()->andReturn($profile);
        $repository->shouldReceive('save')->once()->andReturn(false);
        $storage->shouldReceive('upload')->once()->andReturn('new.jpg');
        $storage->shouldReceive('remove')->with('instagram/new.jpg')->once();
        $storage->shouldNotReceive('remove')->with('instagram/old.jpg');

        $service = new InstagramProfileService($repository, $storage);
        $this->expectException(\RuntimeException::class);
        $service->update(new InstagramProfileUpdateDto('Name', 'https://instagram.com/name/', avatar: UploadedFile::fake()->image('new.jpg')));
    }

    public function test_database_exception_cleans_up_the_new_upload_and_is_preserved(): void
    {
        $repository = Mockery::mock(InstagramProfileRepositoryInterface::class);
        $storage = Mockery::mock(S3ServiceInterface::class);
        $repository->shouldReceive('get')->once()->andReturn(new InstagramProfile);
        $repository->shouldReceive('save')->once()->andThrow(new \RuntimeException('Database unavailable'));
        $storage->shouldReceive('upload')->once()->andReturn('new.jpg');
        $storage->shouldReceive('remove')->with('instagram/new.jpg')->once();

        $service = new InstagramProfileService($repository, $storage);
        $this->expectExceptionMessage('Database unavailable');
        $service->update(new InstagramProfileUpdateDto('Name', 'https://instagram.com/name/', avatar: UploadedFile::fake()->image('new.jpg')));
    }
}
