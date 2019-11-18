<?php

namespace Bedard\Saas\Classes;

class ArrayUtil
{
    /**
     * Remove array keys with falsey values.
     *
     * @param array $source
     *
     * @return array
     */
    public static function removeEmptyProperties(array $source): array
    {
        foreach ($source as $key => $value) {
            if (empty($value)) {
                unset($source[$key]);
            }
        }

        return $source;
    }
}
