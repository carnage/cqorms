<?php

namespace Carnage\Cqorms\Dbal\Serializer;

use JMS\Serializer\JsonDeserializationVisitor;

/**
 * Class JsonAsArrayDeserializationVisitor
 * @TODO trivial, replace with anon class in PHP7
 */
class JsonAsArrayDeserializationVisitor extends JsonDeserializationVisitor
{
    /**
     * Replaces the json decode function for data which has already been decoded. This allows us to use this visitor to
     * deserialize a generic format which has been converted from a string to an array outside of this class
     *
     * @param array $array
     *
     * @return array
     */
    protected function decode($array)
    {
        return $array;
    }
}