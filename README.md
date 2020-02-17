# apie-domain-plugin
Apie Plugin to make Domain objects work.

[![CircleCI](https://circleci.com/gh/pjordaan/apie-domain-plugin.svg?style=svg)](https://circleci.com/gh/pjordaan/laravel-apie)
[![codecov](https://codecov.io/gh/pjordaan/apie-domain-plugin/branch/master/graph/badge.svg)](https://codecov.io/gh/pjordaan/laravel-apie/)
[![Travis](https://api.travis-ci.org/pjordaan/apie-domain-plugin.svg?branch=master)](https://travis-ci.org/pjordaan/laravel-apie)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/pjordaan/apie-domain-plugin/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/pjordaan/laravel-apie/?branch=master)

This is done with the library [jeremykendall/php-domain-parser](https://github.com/jeremykendall/php-domain-parser).

## Installation
Just use composer to install this package.
```bash
composer require w2w/apie-domain-plugin
```

## Using it in Apie directly
In case you use no framework, you can easily make it work with when you create the Apie object.

```php
<?php
use W2w\Lib\Apie\Apie;
use W2w\Lib\ApieDomainPlugin\DomainPlugin;

$apie = new Apie([new DomainPlugin()], true, null);
```

If you want to mock the call to get the public suffixes or have your own
initialization of the php domain parser you can provide it in the constructor:

```php
<?php
use Pdp\Cache;
use Pdp\Manager;
use Pdp\Rules;
use W2w\Lib\Apie\Apie;
use W2w\Lib\ApieDomainPlugin\DomainPlugin;
use W2w\Lib\ApieDomainPlugin\HttpClient\MockHttpClient;

$manager = new Manager(
    new Cache(sys_get_temp_dir()),
    new MockHttpClient()
);
$rules = $manager->getRules(Rules::ICANN_DOMAINS);

$apie = new Apie([new DomainPlugin($rules)], true, null);
```

## integrating with Laravel-apie
If you use apie with Laravel with w2w/laravel-apie you can just install this package with composer.
If auto-wiring is off you require to manually add W2w\Lib\ApieDomainPlugin\DomainPluginServiceProvider::class to the list
of service providers.

You still need to manually add the plugin in the config of laravel-apie:
```php
<?php
// config/apie.php
use W2w\Lib\ApieDomainPlugin\DomainPlugin;

return [
    'plugins' => [DomainPlugin::class],
];
```

### mocking 
With environment variable APIE_DOMAIN_PLUGIN_MOCK you can enable mocking which is also recommended for testing. It will load
a static file instead for the public suffixes.
