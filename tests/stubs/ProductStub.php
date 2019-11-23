<?php

namespace Bedard\Saas\Tests\Stubs;

use Stripe\ApiResource;

class ProductStub extends ApiResource
{
    const OBJECT_NAME = 'product';
    const TYPE_GOOD = 'good';
    const TYPE_SERVICE = 'service';

    use \Stripe\ApiOperations\All;
    use \Stripe\ApiOperations\Create;
    use \Stripe\ApiOperations\Delete;
    use \Stripe\ApiOperations\Retrieve;
    use \Stripe\ApiOperations\Update;
}
