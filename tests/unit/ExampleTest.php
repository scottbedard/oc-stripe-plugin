<?php

namespace Bedard\Saas\Tests\Unit;

use Bedard\Saas\Tests\PluginTestCase;
use Bedard\Saas\Example;

class ExampleTest extends PluginTestCase
{
    public function test_making_an_assertion()
    {
        $obj = new Example;

        $this->assertTrue($obj->foo());
    }
}
