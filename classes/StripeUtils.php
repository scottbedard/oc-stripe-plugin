<?php

namespace Bedard\Saas\Classes;

class StripeUtils
{
    /**
     * Sort stripe resources.
     * 
     * @param  array        $collection
     * @param  array|string $properties
     * @param  any          $default
     *
     * @return object
     */
    public static function sort($collection, $properties = 'metadata.order', $default = '0')
    {
        $propertiesArray = is_array($properties) ? $properties : [$properties];

        return array_values(array_sort($collection, function ($obj) use ($properties, $default) {
            foreach ($properties as $property) {
                if (array_has($obj, $property)) {
                    return array_get($obj, $property);
                }
            }

            return $default;
        }));
    }
}
