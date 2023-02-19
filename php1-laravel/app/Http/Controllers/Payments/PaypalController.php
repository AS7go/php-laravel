<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Repositories\Contracts\OrderRepositoryContract;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Srmklive\PayPal\Services\PayPal;

class PaypalController extends Controller
{
    protected PayPal $payPalClient;

    public function __construct()
    {
        $this->payPalClient = new PayPal();
        $this->payPalClient->setApiCredentials(config('paypal'));
        $this->payPalClient->setAccessToken($this->payPalClient->getAccessToken());
    }

    public function create(CreateOrderRequest $request, OrderRepositoryContract $repository)
    {
        try {
            DB::beginTransaction();
            $total = Cart::instance('cart')->total();
            $paypalOrder = $this->createPaymentOrder($total);
            $request = array_merge(
                $request->validated(),
                [
                    'vendor_order_id' =>$paypalOrder['id'],
                    'total'=>$total
                ]
            );
            $order = $repository->create($request);

            \DB::commit();

            return response()->json($order);
        }catch (\Exception $exception){
            DB::rollBack();
            logs()->warning($exception);

            return response()->json(['error' => $exception->getMessage()], 422);
        }
    }

    public function capture(string $vendorOrderId)
    {

    }

    public function thankYou()
    {

    }

    protected function createPaymentOrder($total): array
    {
        return $this->payPalClient->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => config('paypal.currency'),
                        'value' => $total
                    ]
                ]
            ]
        ]);
    }

}
