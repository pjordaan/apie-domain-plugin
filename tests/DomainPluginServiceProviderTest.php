<?php

namespace W2w\Test\ApieDomainPlugin;

use Orchestra\Testbench\TestCase;
use Pdp\HttpClient;
use Pdp\Manager;
use Pdp\Rules;
use W2w\Lib\ApieDomainPlugin\DomainPlugin;
use W2w\Lib\ApieDomainPlugin\DomainPluginServiceProvider;
use W2w\Lib\ApieDomainPlugin\HttpClient\MockHttpClient;

class DomainPluginServiceProviderTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('apie-domain-plugin', ['mock' => true]);
    }

    protected function getPackageProviders($app)
    {
        return [DomainPluginServiceProvider::class];
    }

    public function testServicesAreRegistered()
    {
        $this->assertTrue($this->app->bound(HttpClient::class));
        $this->assertTrue($this->app->bound(Rules::class));
        $this->assertTrue($this->app->bound(Manager::class));
        $this->assertTrue($this->app->bound(DomainPlugin::class));
        $this->assertInstanceOf(Rules::class, $this->app->get(Rules::class));
        $this->assertInstanceOf(Manager::class, $this->app->get(Manager::class));
        $this->assertInstanceOf(DomainPlugin::class, $this->app->get(DomainPlugin::class));
        $this->assertInstanceOf(MockHttpClient::class, $this->app->get(HttpClient::class));
    }
}
