<?php

namespace Bedard\Saas\Tests\Unit;

use Bedard\Saas\Example;
use Bedard\Saas\Tests\PluginTestCase;

class ExampleTest extends PluginTestCase
{
    public function test_making_an_assertion()
    {
        $obj = new Example();

        $this->assertTrue($obj->foo());
    }
}
