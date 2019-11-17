<?php

namespace Bedard\Saas\Tests\Unit\Models;

use Bedard\Saas\Models\Product;
use Bedard\Saas\Tests\PluginTestCase;

class ProductTest extends PluginTestCase
{
    public function test_active_scope()
    {
        $active = factory(Product::class)->create(['is_active' => true]);
        $inactive = factory(Product::class)->create(['is_active' => false]);

        $this->assertEquals(1, Product::active()->count());
        $this->assertEquals($active->id, Product::active()->first()->id);
    }
}
