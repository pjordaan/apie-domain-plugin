<?php


namespace W2w\Test\ApieDomainPlugin;

use Pdp\Domain;
use W2w\Lib\ApieDomainPlugin\DomainPlugin;
use W2w\Lib\ApieDomainPlugin\Normalizers\DomainNormalizer;
use W2w\Lib\ApieDomainPlugin\Normalizers\PublicSuffixNormalizer;

class DomainPluginTest extends BaseTestCase
{
    public function test_it_works_without_rules_argument()
    {
        $testItem = new DomainPlugin();
        $this->assertArrayHasKey(Domain::class, $testItem->getDefinedStaticData());
        $this->assertEquals([], $testItem->getDynamicSchemaLogic());
        $actual = $testItem->getNormalizers();
        $this->assertCount(2, $actual);
        $this->assertInstanceOf(DomainNormalizer::class, $actual[0]);
        $this->assertInstanceOf(PublicSuffixNormalizer::class, $actual[1]);
    }

    public function test_it_works_with_rules_argument()
    {
        $testItem = $this->createApieWithPlugin()->getPlugin(DomainPlugin::class);
        $this->assertArrayHasKey(Domain::class, $testItem->getDefinedStaticData());
        $this->assertEquals([], $testItem->getDynamicSchemaLogic());
        $actual = $testItem->getNormalizers();
        $this->assertCount(2, $actual);
        $this->assertInstanceOf(DomainNormalizer::class, $actual[0]);
        $this->assertInstanceOf(PublicSuffixNormalizer::class, $actual[1]);
    }
}
