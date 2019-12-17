<?php

namespace App\Normalizers;

use Symfony\Component\Serializer\Normalizer\ConstraintViolationListNormalizer;
use Symfony\Component\Validator\ConstraintViolation;

class SimpleConstraintViolationListNormalizer extends ConstraintViolationListNormalizer
{
    /**
     * @param object $object
     * @param null   $format
     * @param array  $context
     *
     * @return array
     */
    public function normalize($object, $format = null, array $context = array()): array
    {
        $errorInfo = [];

        /** @var ConstraintViolation $violation */
        foreach ($object as $violation) {
            $errorInfo[] = [
                'code'    => $violation->getCode(),
                'message' => $violation->getMessage()
            ];
        }

        return [
            'error' => $errorInfo
        ];
    }
}
