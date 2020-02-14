<?php


namespace W2w\Test\ApieDomainPlugin\Normalizers;

use Pdp\Domain;
use Pdp\PublicSuffix;
use Pdp\Rules;
use Symfony\Component\Serializer\Serializer;
use W2w\Lib\Apie\Exceptions\InvalidValueForValueObjectException;
use W2w\Lib\Apie\Plugins\Core\Serializers\SymfonySerializerAdapter;
use W2w\Test\ApieDomainPlugin\BaseTestCase;

class PublicSuffixNormalizerTest extends BaseTestCase
{
    public function testNormalize()
    {
        $apie = $this->createApieWithPlugin();
        $resourceSerializer = $apie->getResourceSerializer();
        $this->assertTrue($resourceSerializer instanceof SymfonySerializerAdapter, 'I expect apie to return the symfony serializer adapter');
        /** @var Serializer $serializer */
        $serializer = $resourceSerializer->getSerializer();
        $domain = $this->createRules()->getPublicSuffix('test.nl', Rules::ICANN_DOMAINS);
        $this->assertEquals('nl', $serializer->normalize($domain));

        $domain = $this->createRules()->getPublicSuffix('test.co.uk', Rules::ICANN_DOMAINS);
        $this->assertEquals('co.uk', $serializer->normalize($domain));
    }

    public function testDenormalize()
    {
        $apie = $this->createApieWithPlugin();
        $resourceSerializer = $apie->getResourceSerializer();
        $this->assertTrue($resourceSerializer instanceof SymfonySerializerAdapter, 'I expect apie to return the symfony serializer adapter');
        /** @var Serializer $serializer */
        $serializer = $resourceSerializer->getSerializer();
        $actual = $serializer->denormalize('TEST.NL', PublicSuffix::class);
        $this->assertInstanceOf(PublicSuffix::class, $actual);
        $this->assertEquals('nl', $actual->getContent());
    }

    /**
     * @dataProvider unhappyFlowProvider
     */
    public function testDenormalize_unhappy_flow(string $input)
    {
        $apie = $this->createApieWithPlugin();
        $resourceSerializer = $apie->getResourceSerializer();
        $this->assertTrue($resourceSerializer instanceof SymfonySerializerAdapter, 'I expect apie to return the symfony serializer adapter');
        /** @var Serializer $serializer */
        $serializer = $resourceSerializer->getSerializer();
        $this->expectException(InvalidValueForValueObjectException::class);
        $serializer->denormalize($input, PublicSuffix::class);
    }

    public function unhappyFlowProvider()
    {
        yield ['TEST'];
        yield ['---'];
    }
}
