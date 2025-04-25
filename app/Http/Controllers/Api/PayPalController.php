<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Model\Admin\Order;
use App\Model\Admin\OrderDetail;
use App\Model\Admin\ProductVariant;
use App\Services\PayPalOrderService;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class PayPalController extends Controller
{
    protected $orderService;

    public function __construct(PayPalOrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    // POST /api/paypal/create-order
    public function createOrder(Request $req)
    {
        $data = $req->toArray();
        $orderID = $this->orderService->createOrder($data);

        return response()->json(['orderID' => $orderID]);
    }

    // POST /api/paypal/capture-order
    public function captureOrder(Request $req)
    {
        DB::beginTransaction();
        try {
            // Lấy orderID do client gửi lên
            $orderID = $req->input('orderID');

            // Gọi service để capture (thu tiền) order trên PayPal
            $capture = $this->orderService->captureOrder($orderID);

           // Lấy ID của capture transaction đầu tiên trong purchase_units[0]
            $captureId  = optional($capture->purchase_units[0]->payments->captures[0])->id ?? null;
            // Thông tin người trả (payer) từ response của PayPal
            $payer    = $capture->payer;

            // Ghép họ và tên (given_name + surname), cắt bớt khoảng trắng thừa
            $fullName = trim($payer->name->given_name . ' ' . $payer->name->surname);

            // Lấy số điện thoại (nếu có) — có thể null nếu buyer không cung cấp
            $phone = $payer->phone->phone_number->national_number ?? null;

            // Thông tin giao hàng (shipping) trong purchase_units[0]
            $ship = $capture->purchase_units[0]->shipping;
            // Lấy phần address của shipping
            $addr = $ship->address;

            // Các thành phần địa chỉ
            $street  = $addr->address_line_1;                  // số nhà, tên đường
            $street2 = $addr->address_line_2 ?? '';            // phần bổ sung (nếu có)
            $city    = $addr->admin_area_2;                    // thành phố/quận
            $state   = $addr->admin_area_1;                    // bang/ tỉnh
            $postal  = $addr->postal_code;                     // mã bưu chính
            $country = $addr->country_code;                    // mã quốc gia (2 ký tự)

            $fullAddress = trim(
                "{$street}"
                . ($street2 ? ', ' . $street2 : '')
                . ", {$city}, {$state} {$postal}, {$country}"
            );

            $captureData = $capture
                ->purchase_units[0]
                ->payments
                ->captures[0] ?? null;

            if (! $captureData) {
                throw new \RuntimeException('Không lấy được capture data từ PayPal.');
            }

            // Giá trị thanh toán (USD) và mã tiền tệ
            $usdValue    = (float) $captureData->amount->value;         // ví dụ 25.00
            $usdCurrency = $captureData->amount->currency_code;        // ví dụ "USD"

            // Lấy tỉ giá VND/USD từ cấu hình, nếu không có dùng mặc định 24000
            $rate = config('services.exchange_rate_vnd_usd', 24000);

            // Chuyển sang VND, làm tròn về số nguyên
            $vndValue = (int) round($usdValue * $rate, 0);


            // xử lý lưu database
//            $order = Order::query()->create([
//                'customer_name' => $fullName,
//                'customer_phone' => $phone,
//                'customer_email' => $payer->email_address,
//                'customer_address' => $fullAddress,
//                'code' => $capture->id,
//                'capture_id' => $captureId,
//                'status' => 20,
//                'total_after_discount' => $vndValue,
//                'total_before_discount' => $vndValue,
//            ]);
//
//            $itemsData = $capture->purchase_units[0]->items;
//
//            foreach ($itemsData as $item) {
//                $desc = $item->description;
//                $parts = explode(', ', $desc);
//                $color = null;
//                $size  = null;
//
//                foreach ($parts as $part) {
//                    [$key, $value] = explode(': ', $part, 2);
//                    $key = trim($key);
//                    $value = trim($value);
//                    if ($key === 'color') {
//                        $color = $value;
//                    }
//                    if ($key === 'size') {
//                        $size = $value;
//                    }
//                }
//
//                $variant = ProductVariant::query()->find($item->sku);
//
//                $detail = new OrderDetail();
//                $detail->order_id = $order->id;
//                $detail->product_id = $variant->product_id;
//                $detail->variant_id = $variant->id;
//                $detail->color = $color;
//                $detail->size = $size;
//                $detail->qty = $item->quantity;
//                $detail->price = (int) round( $item->unit_amount->value * $rate, 0);
//                $detail->save();
//            }

            // clear cart


            DB::commit();
            return response()->json($capture);
        }catch (\Exception $exception){
            DB::rollBack();
            Log::error($exception);
        }
    }
}
