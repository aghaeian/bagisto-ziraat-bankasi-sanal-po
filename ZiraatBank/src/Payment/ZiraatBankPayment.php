<?php

namespace Webkul\ZiraatBank\Payment;

use Illuminate\Support\Facades\Storage;
use Webkul\Payment\Payment\Payment;

class ZiraatBankPayment extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code = 'ziraat_bank';

    /**
     * Return ZiraatBank redirect url
     *
     * @var string
     */
    public function getRedirectUrl()
    {
        return route('ziraat_bank.payment.redirect');
    }

    /**
     * Returns payment method image
     *
     * @return array
     */
    public function getImage()
    {
        $url = $this->getConfigData('image');

        return $url ? Storage::url($url) : bagisto_asset('images/review.png', 'shop');
    }
}

