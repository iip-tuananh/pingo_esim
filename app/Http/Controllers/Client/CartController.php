<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\product\Product;
use Cart, Auth, Redirect, DB;
use App\models\Bill\BillDetail;
use App\models\Bill\Bill;
use App\models\product\ProductOption;
use App\models\product\Voucher;
use App\Services\PayosService;

class CartController extends Controller
{
    protected $payosService;

    public function __construct(PayosService $payosService)
    {
        $this->payosService = $payosService;
    }

    public function checkout()
    {
        $data['cart'] = session()->get('cart', []);
        $data['profile'] = Auth::guard('customer')->user();
        $data['vouchers'] = Voucher::where('status', 1)->limit(10)->get();
        $data['all_vouchers'] = Voucher::where('status', 1)->get();
        return view('cart.checkout', $data);
    }
    public function postBill(Request $request)
    {
        $voucher = Voucher::query()->where('code', $request->voucher_code)->first();
        $profile = Auth::guard('customer')->user();
        $cart = session()->get('cart', []);
        $total_usd = 0;
        $total_vnd = 0;
        $qty = 0;
        $product_ids = [];
        $is_apply = false;
        foreach ($cart as $item) {
            $total_usd += $item['price_usd'] * $item['quantity'];
            $total_vnd += $item['price_vnd'] * $item['quantity'];
            $qty += $item['quantity'];
            if (in_array($item['idPro'], $product_ids)) {
                $is_apply = true;
            }
        }
        if(isset($voucher) && ($voucher->is_apply_all_product || $is_apply) && (($total_vnd >= $voucher->limit_bill_value && $voucher->limit_bill_value > 0) || ($voucher->limit_product_qty > 0 && $qty >= $voucher->limit_product_qty))) {
            $discount_price = $voucher->value;
            $total_vnd = $total_vnd - $discount_price;
            $total_usd = $total_usd - $discount_price;
        }
        $code_bill = rand();
        DB::beginTransaction();
        try {
            $query = new Bill();
            $query->code_bill = $code_bill;
            $query->code_customer = $profile ? $profile->id : 0;
            $query->total_money = $total_vnd;
            $query->statu = Bill::MOI;
            $query->payment_status = Bill::CHUA_THANH_TOAN;
            $query->note = $request->note;
            $query->cus_name = $request->billingName;
            $query->cus_phone = $request->billingPhone;
            $query->cus_email = $request->billingEmail;
            $query->cus_address = $request->billingAddress;
            $query->transport_price = $request->shippingMethod ? $request->shippingMethod : 0;
            $query->voucher_code = $voucher ? $voucher->code : null;
            $query->discount_value = $voucher ? $discount_price : 0;
            $query->save();


            foreach ($cart as $key => $item) {
                $billdetail = new BillDetail();
                $billdetail->code_bill = $code_bill;
                $billdetail->code_product = $item['idOption'];
                $billdetail->name = $item['name'];
                $billdetail->price = $item['price_vnd'];
                $billdetail->qty = $item['quantity'];
                $billdetail->images = $item['image'];
                $billdetail->save();
            }
            DB::commit();
            $request->session()->forget('cart');

            $total_price = intval($query->total_money);
            $paymentData = $this->payosService->createPayment($total_price, $code_bill, $query->id);

            return Redirect::to($paymentData['data']['checkoutUrl'])->with('success', 'Thanh toán đơn hàng');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
            return back()->with('error', 'Gửi đơn hàng thất bại');
        }
    }
    public function listCart()
    {
        $data['cart'] = session()->get('cart', []);
        return view('cart.list', $data);
    }
    public function addToCart(Request $request)
    {
        $option = ProductOption::where('id', $request->option_id)->where('sku', $request->sku)->first();
        $image = json_decode($option->product->images)[0];
        $cart = session()->get('cart', []);

        if (isset($cart[$request->option_id])) {
            $cart[$request->option_id]['quantity'] = $cart[$request->option_id]['quantity'] + $request->quantity;
        } else {
            $cart[$request->option_id] = [
                "idOption" => $option->id,
                "idPro" => $option->product->id,
                "name" => $option->name,
                "product_name" => $option->product->name,
                "quantity" => $request->quantity,
                "price_usd" => $option->price_usd,
                "price_vnd" => $option->price_vnd,
                "image" => $image,
            ];
        }

        session()->put('cart', $cart);
        return response()->json(['success' => true, 'message' => 'Thêm vào giỏ hàng thành công']);
    }
    public function update(Request $request)
    {
        if ($request->option_id && $request->quantity) {
            $cart = session()->get('cart');
            $cart[$request->option_id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            $data['cart'] = session()->get('cart', []);
            $html = view('cart.cart_ajax', $data)->render();
            return response()->json(['html' => $html]);
        }
    }
    public function remove(Request $request)
    {
        if ($request->option_id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->option_id])) {
                unset($cart[$request->option_id]);
                session()->put('cart', $cart);
            }
            $data['cart'] = session()->get('cart', []);
            $html = view('cart.cart_ajax', $data)->render();
            return response()->json(['html' => $html]);
        }
    }

    // áp dụng mã giảm giá (boolean)
    public function applyVoucher(Request $request) {
        $voucher = Voucher::query()->where('code', $request->code)->first();
        $cart = session()->get('cart', []);
        $total_usd = 0;
        $total_vnd = 0;
        $qty = 0;
        $product_ids = [];
        $is_apply = false;
        foreach ($cart as $item) {
            $total_usd += $item['price_usd'] * $item['quantity'];
            $total_vnd += $item['price_vnd'] * $item['quantity'];
            $qty += $item['quantity'];
            if (in_array($item['idPro'], $product_ids)) {
                $is_apply = true;
            }
        }
        if(isset($voucher) && ($voucher->is_apply_all_product || $is_apply) && (($total_vnd >= $voucher->limit_bill_value && $voucher->limit_bill_value > 0) || ($voucher->limit_product_qty > 0 && $qty >= $voucher->limit_product_qty))) {
            $discount_price = $voucher->value;
            $total_vnd = $total_vnd - $discount_price;
            $total_usd = $total_usd - $discount_price;
            $discount_price_format = formatCurrency(0, $voucher->value);
            $total = formatCurrency($total_usd, $total_vnd);

            return response()->json(['success' => true, 'voucher' => $voucher, 'message' => 'Áp dụng mã giảm giá thành công', 'discount_price' => $discount_price, 'total' => $total, 'discount_price_format' => $discount_price_format]);
        }
        $total = formatCurrency($total_usd, $total_vnd);
        $discount_price_format = formatCurrency(0, 0);
        return response()->json(['success' => false, 'message' => 'Không đủ điều kiện áp mã giảm giá', 'total' => $total, 'discount_price_format' => $discount_price_format]);
    }

    public function handleSuccess(Request $request)
    {
        $order = Bill::query()->where('id', $request->orderCode)->first();
        if ($request->cancel == true) {
            $order->statu = Bill::HUY;
            $order->save();
            return redirect()->route('home')->with('messagePayment', 'error', 'Đã hủy thanh toán');
        } else {
            if ($request->status == 'PAID') {
                $order->payment_status = Bill::DA_THANH_TOAN;
                $order->statu = Bill::MOI;
                $order->save();

                return redirect()->route('home')->with('messagePayment', 'success', 'Đã thanh toán thành công');
            } else {
                return redirect()->route('home')->with('messagePayment', 'error', 'Đã thanh toán thất bại');
            }
        }
    }
}
