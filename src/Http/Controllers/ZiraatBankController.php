<?php

namespace Aghaeian\ZiraatBank\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Transformers\OrderResource;
use ZiraatBank\Gateway as ZiraatBank_Gateway; // Eğer mevcut değilse, doğru bir sınıfla değiştirin

class ZiraatBankController extends Controller
{
    protected $order;

    public function __construct(
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository
    ) {}

    public function createZiraatBankGateway($environment)
    {
        $configKey = $environment === 'sandbox' ? 'ziraat_bank_sandbox' : 'ziraat_bank';

        $merchantId = core()->getConfigData("sales.payment_methods.ziraat_bank.{$configKey}_merchant_id");
        $privateKey = core()->getConfigData("sales.payment_methods.ziraat_bank.{$configKey}_private_key");
        $publicKey = core()->getConfigData("sales.payment_methods.ziraat_bank.{$configKey}_public_key");

        return new ZiraatBank_Gateway([
            'environment' => $environment,
            'merchantId'  => $merchantId,
            'privateKey'  => $privateKey,
            'publicKey'   => $publicKey,
        ]);
    }

    public function redirect()
    {
        $configKey = core()->getConfigData('sales.payment_methods.ziraat_bank.sandbox') ? 'ziraat_bank_sandbox' : 'ziraat_bank';

        $merchantId = core()->getConfigData("sales.payment_methods.ziraat_bank.{$configKey}_merchant_id");
        $privateKey = core()->getConfigData("sales.payment_methods.ziraat_bank.{$configKey}_private_key");
        $publicKey = core()->getConfigData("sales.payment_methods.ziraat_bank.{$configKey}_public_key");

        if ($merchantId && $privateKey && $publicKey) {
            try {
                $clientToken = core()->getConfigData('sales.payment_methods.ziraat_bank.ziraat_bank_tokenization_key');
                $cart = Cart::getCart();

                // Sepet verisini kontrol edin
                dd($cart); // Bu satırı geçici olarak ekleyin, sepet verilerini kontrol etmek için

                $grand_total = number_format($cart->base_grand_total, 2, ',', '.');

                return view('ziraat_bank::drop-in-ui', compact('clientToken', 'grand_total'));
            } catch (\Exception $e) {
                Log::error('Redirect error in ZiraatBankController: ', ['exception' => $e]);
                session()->flash('error', trans('ziraat_bank::app.something-went-wrong'));
            }
        } else {
            session()->flash('warning', trans('ziraat_bank::app.credentials-missing'));
        }

        return redirect()->back();
    }

    public function transaction()
    {
        $environment = core()->getConfigData('sales.payment_methods.ziraat_bank.sandbox') ? 'sandbox' : 'production';
        $gateway = $this->createZiraatBankGateway($environment);

        try {
            $payload = request()->json()->all();

            if (!isset($payload['nonce'])) {
                return response()->json(['error' => 'Nonce not provided'], 400);
            }

            $nonceFromTheClient = $payload['nonce'];
            $cartAmount = Cart::getCart()->base_grand_total;

            $result = $gateway->transaction()->sale([
                'amount' => $cartAmount,
                'paymentMethodNonce' => $nonceFromTheClient,
                'options' => [
                    'submitForSettlement' => true,
                ],
            ]);

            if ($result->success) {
                $cart = Cart::getCart();

                $data = (new OrderResource($cart))->jsonSerialize();

                $order = $this->orderRepository->create($data);

                $this->orderRepository->update(['status' => 'processing'], $order->id);

                Cart::deActivateCart();

                session()->flash('order_id', $order->id);

                return response()->json([
                    'success' => true,
                ]);
            } else {
                Log::error('ZiraatBank transaction failed', ['result' => $result]);

                return response()->json(['error' => 'Transaction failed', 'details' => $result->message], 500);
            }
        } catch (\Exception $e) {
            Log::error('Transaction error in ZiraatBankController: ', ['exception' => $e]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    protected function prepareInvoiceData()
    {
        $invoiceData = [
            'order_id' => $this->order->id,
        ];

        foreach ($this->order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }
}
