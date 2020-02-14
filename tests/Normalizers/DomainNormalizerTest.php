<?php


namespace W2w\Test\ApieDomainPlugin\Normalizers;

use Pdp\Domain;
use Pdp\Rules;
use Symfony\Component\Serializer\Serializer;
use W2w\Lib\Apie\Exceptions\InvalidValueForValueObjectException;
use W2w\Lib\Apie\Plugins\Core\Serializers\SymfonySerializerAdapter;
use W2w\Test\ApieDomainPlugin\BaseTestCase;

class DomainNormalizerTest extends BaseTestCase
{
    public function testNormalize()
    {
        $apie = $this->createApieWithPlugin();
        $resourceSerializer = $apie->getResourceSerializer();
        $this->assertTrue($resourceSerializer instanceof SymfonySerializerAdapter, 'I expect apie to return the symfony serializer adapter');
        /** @var Serializer $serializer */
        $serializer = $resourceSerializer->getSerializer();
        $domain = $this->createRules()->resolve('test.nl', Rules::ICANN_DOMAINS);
        $this->assertEquals('test.nl', $serializer->normalize($domain));

        $domain = $this->createRules()->resolve('test.co.uk', Rules::ICANN_DOMAINS);
        $this->assertEquals('test.co.uk', $serializer->normalize($domain));
    }

    public function testDenormalize()
    {
        $apie = $this->createApieWithPlugin();
        $resourceSerializer = $apie->getResourceSerializer();
        $this->assertTrue($resourceSerializer instanceof SymfonySerializerAdapter, 'I expect apie to return the symfony serializer adapter');
        /** @var Serializer $serializer */
        $serializer = $resourceSerializer->getSerializer();
        $actual = $serializer->denormalize('TEST.nl', Domain::class);
        $this->assertInstanceOf(Domain::class, $actual);
        $this->assertEquals('test.nl', $actual->getDomain());
    }

    public function testDenormalize_unhappy_flow()
    {
        $apie = $this->createApieWithPlugin();
        $resourceSerializer = $apie->getResourceSerializer();
        $this->assertTrue($resourceSerializer instanceof SymfonySerializerAdapter, 'I expect apie to return the symfony serializer adapter');
        /** @var Serializer $serializer */
        $serializer = $resourceSerializer->getSerializer();
        $this->expectException(InvalidValueForValueObjectException::class);
        $serializer->denormalize('TEST', Domain::class);
    }
}
