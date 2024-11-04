<?php

return [
    [
        'key'    => 'sales.payment_methods.ziraat_bank',
        'name'   => 'Ziraat Bankası Ödeme Yöntemi',
        'sort'   => 1,
        'info'   => 'ziraat_bank::app.system.description',
        'fields' => [

        [
            'name'          => 'merchant_id',
            'title'         => 'Ziraat Bankası Merchant ID',
            'type'          => 'text',
            'validation'    => 'required',
            'channel_based' => true,
        ],
        [
            'name'          => 'username',
            'title'         => 'Ziraat Bankası Kullanıcı Adı',
            'type'          => 'text',
            'validation'    => 'required',
            'channel_based' => true,
        ],
        [
            'name'          => 'password',
            'title'         => 'Ziraat Bankası Şifre',
            'type'          => 'password',
            'validation'    => 'required',
            'channel_based' => true,
        ],
        [
            'name'          => 'three_d_key',
            'title'         => '3D Key',
            'type'          => 'text',
            'validation'    => 'required',
            'channel_based' => true,
        ],
            [
                'name'          => 'active',
                'title'         => 'Ziraat Bankası Etkinleştir',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
            ], [
                'name'          => 'title',
                'title'         => 'ziraat_bank::app.system.settings.title',
                'type'          => 'text',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'ziraat_bank::app.system.settings.description',
                'type'          => 'textarea',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'ziraat_bank_merchant_id',
                'title'         => 'ziraat_bank::app.system.settings.ziraat_bank_merchant_id',
                'type'          => 'text',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
            ], [
                'name'          => 'ziraat_bank_public_key',
                'title'         => 'ziraat_bank::app.system.settings.ziraat_bank_public_key',
                'type'          => 'password',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
            ], [
                'name'          => 'ziraat_bank_private_key',
                'title'         => 'ziraat_bank::app.system.settings.ziraat_bank_private_key',
                'type'          => 'password',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
            ], [
                'name'          => 'ziraat_bank_tokenization_key',
                'title'         => 'ziraat_bank::app.system.settings.ziraat_bank_tokenization_key',
                'type'          => 'password',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
            ], [
                'name'          => 'sandbox',
                'title'         => 'ziraat_bank::app.system.settings.sandbox',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
            ], [
                'name'          => 'ziraat_bank_sandbox_merchant_id',
                'title'         => 'ziraat_bank::app.system.settings.ziraat_bank_sandbox_merchant_id',
                'type'          => 'text',
                'depends'       => 'sandbox:1',
                'validation'    => 'required_if:sandbox,1',
                'channel_based' => true,
            ], [
                'name'          => 'ziraat_bank_sandbox_public_key',
                'title'         => 'ziraat_bank::app.system.settings.ziraat_bank_sandbox_public_key',
                'type'          => 'password',
                'depends'       => 'sandbox:1',
                'validation'    => 'required_if:sandbox,1',
                'channel_based' => true,
            ], [
                'name'          => 'ziraat_bank_sandbox_private_key',
                'title'         => 'ziraat_bank::app.system.settings.ziraat_bank_sandbox_private_key',
                'type'          => 'password',
                'depends'       => 'sandbox:1',
                'validation'    => 'required_if:sandbox,1',
                'channel_based' => true,
            ], [
                'name'       => 'sort',
                'title'      => 'admin::app.configuration.index.sales.payment-methods.sort-order',
                'type'       => 'select',
                'depends'    => 'active:1',
                'validation' => 'required_if:active,1',
                'options'    => [
                    [
                        'title' => '1',
                        'value' => 1,
                    ], [
                        'title' => '2',
                        'value' => 2,
                    ], [
                        'title' => '3',
                        'value' => 3,
                    ], [
                        'title' => '4',
                        'value' => 4,
                    ], [
                        'title' => '5',
                        'value' => 5,
                    ],
                ],
            ],
        ],
    ],
];
