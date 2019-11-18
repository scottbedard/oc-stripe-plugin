<?php

namespace Bedard\Saas\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Bedard\Saas\Classes\ArrayUtil;
use Bedard\Saas\Models\Settings;
use Flash;
use Stripe\Exception\ApiErrorException;
use StripeManager;
use ValidationException;
use Validator;

/**
 * Products Back-end Controller.
 */
class Coupons extends Controller
{
    public $formConfig = 'config_form.yaml';

    public $implement = [
        'Backend.Behaviors.FormController',
    ];

    public $registerPermissions = [
        'bedard.saas.access_coupons',
    ];

    public $rules = [
        'amount_off' => 'required_without:percent_off',
        'currency' => 'required_with:amount_off',
        'duration' => 'required|in:once,forever,repeating',
        'duration_in_months' => 'required_if:duration,repeating|integer|min:1',
        'max_redemptions' => 'integer|min:1',
        'percent_off' => 'required_without:amount_off|numeric|min:0|max:100',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bedard.Saas', 'saas', 'coupons');
    }

    /**
     * Normalize coupon data.
     * 
     * @param  array $data
     * 
     * @return array
     */
    protected function normalize($data)
    {
        unset($data['discount_type']);

        return ArrayUtil::removeEmptyProperties($data);
    }

    public function index()
    {
        $this->bodyClass = 'slim-container';

        return $this->makePartial('index');
    }

    public function formCreateModelObject()
    {
        $model = new \Model();
        $model->currency = Settings::get('currency_code');

        return $model;
    }

    /**
     * Create a coupon.
     */
    public function create_onSave()
    {
        $data = $this->normalize(post('Model'));

        $this->validate($data);

        try {
            $coupon = StripeManager::createCoupon($data);
        } catch (ApiErrorException $e) {
            dd($e);
            Flash::error($e->getMessage());
        }

        Flash::success('hooray');
    }

    /**
     * Validate form data.
     *
     * @param array $data
     *
     * @return void
     */
    protected function validate(array $data)
    {
        $validator = Validator::make($data, $this->rules);

        if ($validator->fails()) {
            dd($validator->messages());
            throw new ValidationException($validator);
        }
    }
}
