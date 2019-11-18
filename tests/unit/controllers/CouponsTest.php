<?php

namespace Bedard\Saas\Tests\Unit\Controllers;

use October\Rain\Exception\AjaxException;
use Bedard\Saas\Tests\PluginTestCase;
use StripeManager;

class CouponsTest extends PluginTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function payload($data = [])
    {
        return [
            'Model' => array_merge([
                'amount_off'         => '',
                'currency'           => '',
                'discount_type'      => '',
                'duration'           => '',
                'duration_in_months' => '',
                'id'                 => '',
                'max_redemptions'    => '',
                'name'               => '',
                'percent_off'        => '',
                'redeem_by'          => '',
            ], $data),
        ];
    }

    public function test_creating_a_coupon()
    {
        $admin = self::createBackendUser();
        $faker = \Faker\Factory::create();

        $id = $faker->uuid;

        $response = $this->ajax(
            '/backend/bedard/saas/coupons/create', 'onSave',
            $this->payload([
                'amount_off'         => 500,
                'currency'           => 'eur',
                'duration'           => 'repeating',
                'duration_in_months' => 5,
                'id'                 => $id,
                'max_redemptions'    => 10,
                'name'               => 'Some awesome coupon',
            ])
        );

        $coupon = StripeManager::retrieveCoupon($id);
        $this->assertEquals(500, $coupon->amount_off);
        $this->assertEquals('Some awesome coupon', $coupon->name);
        $this->assertEquals(10, $coupon->max_redemptions);
        $this->assertEquals('eur', $coupon->currency);
        $this->assertEquals('repeating', $coupon->duration);
        $this->assertEquals(5, $coupon->duration_in_months);

        $response->assertStatus(200);
    }

    public function test_creating_a_coupon_with_validation_errors()
    {
        $admin = self::createBackendUser();
        
        $response = $this->ajax(
            '/backend/bedard/saas/coupons/create', 
            'onSave', 
            $this->payload([]) // <- an empty payload should cause errors
        );

        $response->assertStatus(500);
        
        $this->assertInstanceOf(AjaxException::class, $response->exception);
    }
}
