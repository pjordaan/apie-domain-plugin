<?php


namespace W2w\Lib\ApieDomainPlugin;

use erasys\OpenApi\Spec\v3\Schema;
use Pdp\Cache;
use Pdp\CurlHttpClient;
use Pdp\Domain;
use Pdp\Manager;
use Pdp\PublicSuffix;
use Pdp\Rules;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use W2w\Lib\Apie\PluginInterfaces\NormalizerProviderInterface;
use W2w\Lib\Apie\PluginInterfaces\SchemaProviderInterface;
use W2w\Lib\ApieDomainPlugin\Normalizers\DomainNormalizer;
use W2w\Lib\ApieDomainPlugin\Normalizers\PublicSuffixNormalizer;

/**
 * Apie plugin to add support for Pdp\Domain.
 */
class DomainPlugin implements SchemaProviderInterface, NormalizerProviderInterface
{
    private $rules;

    /**
     * @param Rules|null $rules
     */
    public function __construct(?Rules $rules = null)
    {
        if (!$rules) {
            $manager = new Manager(new Cache(), new CurlHttpClient());
            $rules = $manager->getRules();
        }
        $this->rules = $rules;
    }

    /**
     * @return Schema[]
     */
    public function getDefinedStaticData(): array
    {
        return [
            Domain::class => new Schema(['type' => 'string', 'format' => 'domain', 'example' => 'example.nl', 'default' => 'example.nl']),
            PublicSuffix::class => new Schema(['type' => 'string', 'format' => 'tld', 'example' => 'nl', 'default' => 'nl']),
        ];
    }

    /**
     * @return callable[]
     */
    public function getDynamicSchemaLogic(): array
    {
        return [];
    }

    /**
     * @return NormalizerInterface[]|DenormalizerInterface[]
     */
    public function getNormalizers(): array
    {
        return [new DomainNormalizer($this->rules), new PublicSuffixNormalizer($this->rules)];
    }
}
