<?php

namespace W2w\Lib\ApieDomainPlugin\Normalizers;

use Pdp\Domain;
use Pdp\Rules;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use W2w\Lib\Apie\Exceptions\InvalidValueForValueObjectException;

class DomainNormalizer implements NormalizerInterface, DenormalizerInterface
{
    private $rules;

    public function __construct(Rules $rules)
    {
        $this->rules = $rules;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $domain = $this->rules->resolve((string) $data, Rules::ICANN_DOMAINS);
        if (!$domain->isResolvable()) {
            throw new InvalidValueForValueObjectException($data, Domain::class);
        }

        return $domain;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === Domain::class;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        /** @var Domain $object */
        return (string) $object;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Domain;
    }
}
