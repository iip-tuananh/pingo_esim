<?php
namespace App\Services;

use App\Services\PayPalClient;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalHttp\HttpException;
use PayPalHttp\HttpRequest;
use PayPalCheckoutSdk\Core\PayPalHttpClient;

class PayPalOrderService
{
    protected $client;

    public function __construct()
    {
        $this->client = PayPalClient::client();
    }

    /**
     * Tạo Order và trả về order ID (O-xxx).
     */
    public function createOrder(array $data): string
    {
        $itemsIn = $data['items'] ?? [];

        $itemTotal      = 0.0;
        $formattedItems = [];

        foreach ($itemsIn as $itm) {
            $unitUsd  = (float) $itm['unit_amount']['value'];
            $quantity = (int)   $itm['quantity'];
            $subtotal = round($unitUsd * $quantity, 2);
            $itemTotal = round($itemTotal + $subtotal, 2);

            $formattedItems[] = [
                'name'        => $itm['name'],
                'sku'         => $itm['sku'],
                'description' => $itm['description'] ?? '',
                'unit_amount' => [
                    'currency_code' => $itm['unit_amount']['currency_code'] ?? 'USD',
                    'value'         => number_format($unitUsd, 2, '.', '')
                ],
                'quantity'    => (string) $quantity,
            ];
        }

        // 2) Đảm bảo tổng giá trị có 2 chữ số thập phân
        $amountValue = number_format($itemTotal, 2, '.', '');

        // 3) Xây dựng purchase unit với breakdown khớp itemTotal
        $purchaseUnit = [
            'amount' => [
                'currency_code' => 'USD',
                'value'         => $amountValue,
                'breakdown'     => [
                    'item_total' => [
                        'currency_code' => 'USD',
                        'value'         => $amountValue
                    ]
                ]
            ],
            'items' => $formattedItems,
        ];

        // 4) Gửi request tạo order lên PayPal
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            'intent'           => 'CAPTURE',
            'purchase_units'   => [ $purchaseUnit ],
            'application_context' => [
                'return_url'  => url('/payment-success'),
                'cancel_url'  => url('/payment-cancel'),
                'user_action' => 'PAY_NOW',
            ]
        ];

        $response = $this->client->execute($request);
        return $response->result->id;
    }



    /**
     * Capture Order theo order ID, trả về toàn bộ chi tiết capture.
     */
    public function captureOrder(string $orderID)
    {
        $request = new OrdersCaptureRequest($orderID);
        $request->prefer('return=representation');
        $response = $this->client->execute($request);

        return $response->result;
    }

    public function trackOrder(string $orderId, array $data)
    {
        // 1. Xây path relative
        $path = "/v2/checkout/orders/{$orderId}/track";

        // 2. Khởi tạo HttpRequest (path trước, verb sau)
        $request = new HttpRequest($path, 'POST');

        // 3. Gán header
        $request->headers['Content-Type'] = 'application/json';
        $request->headers['Prefer']       = 'return=representation';

        // 4. Gán body JSON
        $request->body = json_encode([
            'capture_id'      => $data['capture_id'],
            'carrier'         => strtoupper($data['carrier']),
            'tracking_number' => $data['tracking_number'],
            'notify_payer'    => (bool) ($data['notify_payer'] ?? false),
        ]);

        // 5. Thực thi request
        try {
            $response = $this->client->execute($request);
        } catch (HttpException $ex) {
            // Log hoặc ném exception custom nếu cần
            throw new \RuntimeException(
                "PayPal Track Error [{$ex->statusCode}]: {$ex->getMessage()}"
            );
        }

        // 6. Trả về kết quả
        return $response->result;
    }
}
