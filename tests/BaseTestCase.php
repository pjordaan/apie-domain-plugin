<?php


namespace W2w\Test\ApieDomainPlugin;

use Pdp\Rules;
use PHPUnit\Framework\TestCase;
use W2w\Lib\Apie\Apie;
use W2w\Lib\ApieDomainPlugin\DomainPlugin;

abstract class BaseTestCase extends TestCase
{
    final protected function createRules(): Rules
    {
        return Rules::createFromPath(__DIR__ . '/../data/public-suffix.dat');
    }

    final protected function createApieWithPlugin(): Apie
    {
        $rules = $this->createRules();
        return new Apie([new DomainPlugin($rules)], true, null);
    }
}
