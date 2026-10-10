<?php

namespace Tests\Unit;

use App\Services\Seo\ShopCacheInvalidator;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Mockery;
use PHPUnit\Framework\TestCase;

class ShopCacheInvalidatorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $container = new Container;
        Container::setInstance($container);
        $container->instance('config', new Repository(['services' => ['shop_cache' => [
            'url' => 'https://shop.example.test/api/revalidate', 'secret' => 'unit-test-secret',
        ]]]));
        Facade::setFacadeApplication($container);
        DB::swap(Mockery::mock());
        Log::swap(Mockery::mock());
        Http::swap(new Factory);
        Http::preventStrayRequests();
        Http::fake(['shop.example.test/*' => Http::response(['revalidated' => true])]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(null);
        Container::setInstance(null);
        parent::tearDown();
    }

    public function test_multiple_changes_send_one_authenticated_invalidation(): void
    {
        DB::shouldReceive('transactionLevel')->twice()->andReturn(0);
        $service = new ShopCacheInvalidator;
        $service->schedule();
        $service->schedule();
        Http::assertNothingSent();
        $service->flush();
        $service->flush();
        Http::assertSentCount(1);
        Http::assertSent(fn ($request) => $request->method() === 'POST'
            && $request->url() === 'https://shop.example.test/api/revalidate'
            && $request->hasHeader('Authorization', 'Bearer unit-test-secret'));
    }

    public function test_transaction_invalidation_waits_for_commit(): void
    {
        $commit = null;
        DB::shouldReceive('transactionLevel')->once()->andReturn(1);
        DB::shouldReceive('afterCommit')->once()->andReturnUsing(function ($callback) use (&$commit) {
            $commit = $callback;
        });
        $service = new ShopCacheInvalidator;
        $service->schedule();
        $service->flush();
        Http::assertNothingSent();
        $commit();
        $service->flush();
        Http::assertSentCount(1);
    }

    public function test_missing_configuration_does_not_contact_frontend(): void
    {
        config(['services.shop_cache.secret' => null]);
        $service = new ShopCacheInvalidator;
        $service->schedule();
        $service->flush();
        Http::assertNothingSent();
    }

    public function test_frontend_failure_never_breaks_a_completed_payment(): void
    {
        DB::shouldReceive('transactionLevel')->once()->andReturn(0);
        Http::swap(new Factory);
        Http::fake(['shop.example.test/*' => Http::response([], 503)]);
        Log::shouldReceive('warning')->once()->with('Shop cache invalidation failed', Mockery::on(
            fn ($context) => array_keys($context) === ['exception']
        ));
        $service = new ShopCacheInvalidator;
        $service->schedule();
        $service->flush();
        Http::assertSentCount(1);
    }
}
