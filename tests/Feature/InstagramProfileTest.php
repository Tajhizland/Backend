<?php

namespace Tests\Feature;

use App\Models\InstagramPost;
use App\Models\InstagramProfile;
use App\Models\User;
use App\Services\S3\S3ServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Mockery;
use Tests\TestCase;

class InstagramProfileTest extends TestCase
{
    private const ENDPOINT = '/api/v1/admin/instagram-profile';

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'services.shop_cache.url' => null,
            'services.shop_cache.secret' => null,
        ]);
        DB::purge('sqlite');
        Http::preventStrayRequests();

        foreach ([
            '2026_09_27_100000_create_instagram_posts_table.php',
            '2026_09_27_100100_create_instagram_post_media_table.php',
            '2026_10_10_120000_add_cover_to_instagram_post_media_table.php',
            '2026_10_18_120000_create_instagram_profiles_table.php',
        ] as $file) {
            (require database_path('migrations/'.$file))->up();
        }
    }

    private function admin(): void
    {
        Sanctum::actingAs((new User)->forceFill(['id' => 1, 'role' => 'admin']));
    }

    private function fields(): array
    {
        return [
            'name' => 'کافه آزمایشی ۱۲۳',
            'url' => 'https://www.instagram.com/test_cafe.123/?igsh=example',
            'bio' => "تجهیزات کافه ☕\nپاسخ‌گویی از ساعت ۹ تا ۱۸",
        ];
    }

    public function test_default_profile_is_available_without_creating_a_record(): void
    {
        $this->admin();
        $this->getJson(self::ENDPOINT)->assertOk()
            ->assertJsonPath('result.data.username', 'tajhizland_com')
            ->assertJsonPath('result.data.avatar', null);
        $this->getJson('/api/v1/instagram-post')->assertOk()
            ->assertJsonPath('result.data.profile.username', 'tajhizland_com')
            ->assertJsonPath('result.data.listing.meta.total', 0);
        $this->assertSame(0, InstagramProfile::count());
    }

    public function test_only_administrators_can_read_or_update_the_profile_settings(): void
    {
        $this->getJson(self::ENDPOINT)->assertUnauthorized();
        $this->putJson(self::ENDPOINT, $this->fields())->assertUnauthorized();
        Sanctum::actingAs((new User)->forceFill(['id' => 2, 'role' => 'user']));
        $this->getJson(self::ENDPOINT)->assertForbidden();
        $this->putJson(self::ENDPOINT, $this->fields())->assertForbidden();
        $this->assertSame(0, InstagramProfile::count());
    }

    public function test_saved_profile_persists_and_is_shared_by_the_feed_and_post_page(): void
    {
        $this->admin();
        $fields = $this->fields();
        $this->post(self::ENDPOINT, ['_method' => 'PUT', ...$fields], ['Accept' => 'application/json'])
            ->assertOk()->assertJsonPath('result.data.username', 'test_cafe.123');
        $this->getJson(self::ENDPOINT)->assertOk()->assertJsonFragment($fields);

        $post = InstagramPost::create(['caption' => 'پست آزمایشی', 'status' => 1]);
        $post->media()->create(['type' => 'image', 'file' => 'post.jpg']);
        $this->getJson('/api/v1/instagram-post')->assertOk()
            ->assertJsonPath('result.data.profile.bio', $fields['bio'])
            ->assertJsonPath('result.data.profile.name', $fields['name'])
            ->assertJsonPath('result.data.profile.url', $fields['url']);
        $this->postJson('/api/v1/instagram-post/find', ['id' => $post->id])->assertOk()
            ->assertJsonPath('result.data.profile.username', 'test_cafe.123')
            ->assertJsonPath('result.data.profile.name', $fields['name'])
            ->assertJsonPath('result.data.profile.url', $fields['url']);

        $this->putJson(self::ENDPOINT, [...$fields, 'bio' => ''])->assertOk();
        $this->getJson(self::ENDPOINT)->assertOk()->assertJsonPath('result.data.bio', null);
        $this->assertSame(1, InstagramProfile::count());
    }

    public function test_photo_upload_is_preserved_by_text_edits_then_replaced_and_removed(): void
    {
        $this->admin();
        $storage = Mockery::mock(S3ServiceInterface::class);
        $this->app->instance(S3ServiceInterface::class, $storage);
        $storage->shouldReceive('upload')->twice()
            ->with(Mockery::type(UploadedFile::class), 'instagram', Mockery::on(fn ($name) => str_ends_with($name, '.jpg')))
            ->andReturn('first.jpg', 'second.jpg');
        $storage->shouldReceive('remove')->with('instagram/first.jpg')->once()
            ->andReturnUsing(function () {
                $this->assertSame(0, DB::transactionLevel());
                $this->assertSame('second.jpg', InstagramProfile::findOrFail(1)->avatar);
            });
        $storage->shouldReceive('remove')->with('instagram/second.jpg')->once()
            ->andReturnUsing(function () {
                $this->assertSame(0, DB::transactionLevel());
                $this->assertNull(InstagramProfile::findOrFail(1)->avatar);
            });

        foreach (['first.jpg', 'second.jpg'] as $file) {
            $this->post(self::ENDPOINT, [
                '_method' => 'PUT', ...$this->fields(), 'avatar' => UploadedFile::fake()->image($file),
            ], ['Accept' => 'application/json'])->assertOk()->assertJsonPath('result.data.avatar', $file);
            $this->putJson(self::ENDPOINT, $this->fields())->assertOk()->assertJsonPath('result.data.avatar', $file);
        }

        $this->putJson(self::ENDPOINT, [...$this->fields(), 'remove_avatar' => true])->assertOk()
            ->assertJsonPath('result.data.avatar', null);
        $this->getJson('/api/v1/instagram-post')->assertOk()->assertJsonPath('result.data.profile.avatar', null);
    }

    public function test_invalid_profile_links_and_missing_or_oversized_text_are_rejected(): void
    {
        $this->admin();
        foreach ([
            'javascript:alert(1)',
            'https://example.com/account/',
            'https://instagram.com.evil.test/account/',
            'https://www.instagram.com/',
            'https://www.instagram.com/p/a-post/',
        ] as $url) {
            $this->putJson(self::ENDPOINT, [...$this->fields(), 'url' => $url])->assertUnprocessable()
                ->assertJsonStructure(['errors' => ['url']]);
        }
        $this->putJson(self::ENDPOINT, [])->assertUnprocessable()->assertJsonStructure(['errors' => ['name', 'url']]);
        $this->putJson(self::ENDPOINT, [...$this->fields(), 'name' => str_repeat('a', 256), 'bio' => str_repeat('ب', 1001)])
            ->assertUnprocessable()->assertJsonStructure(['errors' => ['name', 'bio']]);
        $this->assertSame(0, InstagramProfile::count());
    }

    public function test_invalid_photos_and_simultaneous_upload_and_removal_are_rejected(): void
    {
        $this->admin();
        foreach ([
            UploadedFile::fake()->create('clip.mp4', 10, 'video/mp4'),
            UploadedFile::fake()->create('avatar.svg', 1, 'image/svg+xml'),
            UploadedFile::fake()->image('large.png')->size(5121),
        ] as $file) {
            $this->post(self::ENDPOINT, ['_method' => 'PUT', ...$this->fields(), 'avatar' => $file], ['Accept' => 'application/json'])
                ->assertUnprocessable()->assertJsonStructure(['errors' => ['avatar']]);
        }
        $this->post(self::ENDPOINT, [
            '_method' => 'PUT', ...$this->fields(), 'avatar' => UploadedFile::fake()->image('avatar.jpg'), 'remove_avatar' => '1',
        ], ['Accept' => 'application/json'])->assertUnprocessable()->assertJsonStructure(['errors' => ['avatar']]);
        $this->assertSame(0, InstagramProfile::count());
    }
}
