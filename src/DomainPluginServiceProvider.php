<?php

namespace W2w\Lib\ApieDomainPlugin;

use Illuminate\Support\ServiceProvider;
use Pdp\Cache;
use Pdp\CurlHttpClient;
use Pdp\HttpClient;
use Pdp\Manager;
use Pdp\Rules;
use W2w\Lib\ApieDomainPlugin\HttpClient\MockHttpClient;

/**
 * Adds functionality to parse domain names to Laravel/Lumen.
 */
class DomainPluginServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . '/../config/apie-domain-plugin.php' => $this->app->get('path.config') . DIRECTORY_SEPARATOR . 'apie-domain-plugin.php',
            ]
        );
        if ($this->app->make('config')->get('apie-domain-plugin.mock', false)) {
            $this->app->bind(HttpClient::class, MockHttpClient::class);
        }
    }

    public function register()
    {
        $this->app->bind(HttpClient::class, CurlHttpClient::class);

        $this->app->singleton(Manager::class, function () {
            $path = $this->app->get('path.storage') . DIRECTORY_SEPARATOR . 'app/domains';
            return new Manager(new Cache($path), $this->app->get(HttpClient::class));
        });
        $this->app->singleton(Rules::class, function () {
            return $this->app->get(Manager::class)->getRules()
                ->withAsciiIDNAOption(IDNA_NONTRANSITIONAL_TO_ASCII)
                ->withUnicodeIDNAOption(IDNA_NONTRANSITIONAL_TO_UNICODE);
        });
        $this->app->singleton(DomainPlugin::class);
    }
}
