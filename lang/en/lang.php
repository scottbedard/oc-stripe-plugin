<?php

return [
    'exceptions' => [
        'unauthorized_cancellation' => 'User attempted to cancel a subscription that does not belong to them.',
    ],
    'permissions' => [
        'access_settings'    => 'Manage Stripe settings',
    ],
    'settings' => [
        'form' => [
            'api_keys_invalid'         => 'API keys are invalid or misconfigured.',
            'api_keys_invalid_comment' => 'Please refer to the <a href="https://stripe.com/docs/keys" target="_new">Stripe documentation</a> for more information.',
            'api_keys_live'            => 'Live keys are configured properly.',
            'api_keys_live_comment'    => 'Services are fully operational and making live transactions.',
            'api_keys_test'            => 'Test keys are configured properly.',
            'api_keys_test_comment'    => 'Services should function normally, but no real transactions will occur.',
        ],
        'menu_category'    => 'Services',
        'menu_description' => 'Manage Stripe configuration.',
        'menu_label'       => 'Stripe settings',
    ],
];
