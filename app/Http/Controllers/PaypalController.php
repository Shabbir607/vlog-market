<?php

namespace App\Http\Controllers;
use Srmklive\PayPal\Services\ExpressCheckout;


use Illuminate\Http\Request;
use NunoMaduro\Collision\Provider;
use App\Models\Cart;
use App\Models\Product;
use DB;
class PaypalController extends Controller
{

    protected $expressCheckout;

    public function __construct(ExpressCheckout $expressCheckout)
    {
        $this->expressCheckout = $expressCheckout;
    }

    public function payment()
    {
        // Fetch the user's cart
        $cart = Cart::where('user_id', auth()->user()->id)->where('order_id', null)->get()->toArray();
        
        // Prepare data for PayPal
        $data = [];

        $data['items'] = array_map(function ($item) {
            $product = Product::find($item['product_id']);
            return [
                'name' => $product->title,
                'price' => $item['price'],
                'desc' => 'Thank you for using PayPal',
                'qty' => $item['quantity']
            ];
        }, $cart);

        $data['invoice_id'] = 'ORD-' . strtoupper(uniqid());
        $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
        $data['return_url'] = route('payment.success');
        $data['cancel_url'] = route('payment.cancel');

        // Calculate total price
        $total = collect($data['items'])->sum(function ($item) {
            return $item['price'] * $item['qty'];
        });

        $data['total'] = $total;

        // If a coupon is applied, include the shipping discount
        if (session('coupon')) {
            $data['shipping_discount'] = session('coupon')['value'];
        }

        // Update the Cart order_id
        Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => session()->get('id')]);

        // Set up the Express Checkout using dependency injection
        $response = $this->expressCheckout->setExpressCheckout($data);

        // Redirect the user to PayPal for payment
        return redirect($response['paypal_link']);
    }

   
    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel()
    {
        dd('Your payment is canceled. You can create cancel page here.');
    }
  
    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */
    public function success(Request $request)
    {
        $provider = new ExpressCheckout;
        $response = $provider->getExpressCheckoutDetails($request->token);
        // return $response;
  
        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
            request()->session()->flash('success','You successfully pay from Paypal! Thank You');
            session()->forget('cart');
            session()->forget('coupon');
            return redirect()->route('home');
        }
  
        request()->session()->flash('error','Something went wrong please try again!!!');
        return redirect()->back();
    }
}
