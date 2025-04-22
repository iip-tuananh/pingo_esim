<?php

use App\models\Country;
use App\models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class CountryAndCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $response = Http::timeout(30)
            ->retry(3, 2000)
            ->get('https://restcountries.com/v3.1/all?fields=cca2,name,currencies');

        if ($response->successful()) {
            $countries = $response->json();

            foreach ($countries as $item) {
                $code = $item['cca2'] ?? null;
                $name = $item['name']['common'] ?? null;
                $currencies = $item['currencies'] ?? [];

                if ($code && $name && count($currencies)) {
                    $currencyCode = array_key_first($currencies);
                    $currencyData = $currencies[$currencyCode];

                    // Lưu currency nếu chưa có
                    Currency::updateOrCreate(
                        ['code' => $currencyCode],
                        [
                            'name' => $currencyData['name'] ?? null,
                            'symbol' => $currencyData['symbol'] ?? null,
                        ]
                    );

                    // Lưu country
                    Country::updateOrCreate(
                        ['code' => $code],
                        [
                            'name' => $name,
                            'currency_code' => $currencyCode,
                        ]
                    );
                }
            }

            $this->command->info('✔ Đã cập nhật danh sách countries và currencies.');
        } else {
            $this->command->error('✘ Không thể gọi REST Countries API.');
        }
    }
}
