<?php

namespace Bedard\Saas\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Bedard\Saas\Models\Settings;
use Flash;
use StripeManager;
use Stripe\Exception\ApiErrorException;
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
        'duration_in_months' => 'required_if:duration,repeating|integer|min:1',
        'max_redemptions' => 'integer|min:1',
        'percent_off' => 'numeric|min:0|max:100',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bedard.Saas', 'saas', 'coupons');
    }

    public function index()
    {
        $this->bodyClass = 'slim-container';

        return $this->makePartial('index');
    }

    public function formCreateModelObject()
    {
        $model = new \Model;
        $model->currency = Settings::get('currency_code');

        return $model;
    }

    /**
     * Create a coupon.
     */
    public function create_onSave()
    {
        $data = post('Model');

        if ($data['id'] === '') {
            unset($data['id']);
        }

        $this->validate($data);

        try {
            $coupon = StripeManager::createCoupon($data);
        } catch (ApiErrorException $e) {
            Flash::error($e->getMessage());
        }
        
        Flash::success('hooray');
    }

    /**
     * Validate form data.
     * 
     * @param  array    $data
     * 
     * @return void
     */
    protected function validate(array $data)
    {
        $validator = Validator::make($data, $this->rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        } 
    }
}
