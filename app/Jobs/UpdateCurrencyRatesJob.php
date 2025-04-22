<?php

namespace App\Jobs;

use App\models\CurrencyRate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Notifications\CurrencyRateUpdated;
use App\Notifications\CurrencyRateFailed;
use Illuminate\Support\Facades\Notification;

class UpdateCurrencyRatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $base = 'USD';
            $response = Http::get("https://open.er-api.com/v6/latest/{$base}");

            $data = $response->json();
            if ($data['result'] != 'success') {
                throw new \Exception('Gọi API thất bại');
            }

            $rates = $data['rates'];
            $now = now();

            foreach ($rates as $code => $rate) {
                // Cập nhật tỉ giá
                CurrencyRate::updateOrCreate(
                    ['currency' => $code],
                    [
                        'rate' => $rate,
                        'base_currency' => $base,
                        'updated_at' => $now,
                    ]
                );
            }

            // Gửi email thành công
            Notification::route('mail', 'nguyentienvu4897@gmail.com')
                ->notify(new CurrencyRateUpdated());

        } catch (\Exception $e) {
            // Gửi email khi lỗi
            Notification::route('mail', 'nguyentienvu4897@gmail.com')
                ->notify(new CurrencyRateFailed($e->getMessage()));

            // Ghi log
            logger()->error('Cập nhật tỉ giá lỗi: ' . $e->getMessage());
        }
    }
}
