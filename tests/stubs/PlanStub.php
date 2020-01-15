<?php

namespace Bedard\Stripe\Tests\Stubs;

class PlanStub extends \Stripe\ApiResource
{
    const OBJECT_NAME = 'plan';

    use \Stripe\ApiOperations\All;
    use \Stripe\ApiOperations\Create;
    use \Stripe\ApiOperations\Delete;
    use \Stripe\ApiOperations\Retrieve;
    use \Stripe\ApiOperations\Update;
}
