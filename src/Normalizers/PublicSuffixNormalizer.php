<?php

namespace W2w\Lib\ApieDomainPlugin\Normalizers;

use Pdp\Exception\CouldNotResolvePublicSuffix;
use Pdp\PublicSuffix;
use Pdp\Rules;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use W2w\Lib\Apie\Exceptions\InvalidValueForValueObjectException;

class PublicSuffixNormalizer implements NormalizerInterface, DenormalizerInterface
{
    private $rules;

    public function __construct(Rules $rules)
    {
        $this->rules = $rules;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        try {
            $suffix = $this->rules->getPublicSuffix('test.' . ltrim($data, '.'), Rules::ICANN_DOMAINS);
            if (!$suffix->isKnown()) {
                throw new InvalidValueForValueObjectException($data, PublicSuffix::class);
            }
        } catch (CouldNotResolvePublicSuffix $e) {
            throw new InvalidValueForValueObjectException($data, PublicSuffix::class);
        }
        return $suffix;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === PublicSuffix::class;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        /** @var PublicSuffix $object */
        return (string) $object;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof PublicSuffix;
    }
}
