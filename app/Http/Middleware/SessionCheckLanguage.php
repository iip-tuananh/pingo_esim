<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use GeoIP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SessionCheckLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // public function handle(Request$request, Closure $next)
    // {
    //     // $request->session()->forget('locale');
    //     // if(Session::get('locale') == null){
    //     //     Session::put('locale', app()->getLocale());
    //     // }

    //     // Nếu có session 'locale' thì sử dụng nó
    //     if (Session::get('locale')) {
    //         App::setLocale(Session::get('locale'));
    //     } else {
    //         // Lấy vị trí người dùng theo IP
    //         $location = geoip()->getLocation();
    //         $country = $location['country'] ?? 'Unknown'; // Nếu không xác định được, mặc định là 'Unknown'

    //         // Map quốc gia với ngôn ngữ
    //         $languageMap = [
    //             'Vietnam' => 'vi',
    //             'United States' => 'en',
    //         ];

    //         // $apiUrl = "https://restcountries.com/v3.1/all";
    //         // $countries = json_decode(file_get_contents($apiUrl), true);

    //         // $languageMap = [];
    //         // foreach ($countries as $country) {
    //         //     $name = $country['name']['common'] ?? '';
    //         //     $languages = $country['languages'] ?? [];
    //         //     $primaryLang = array_values($languages)[0] ?? 'en';

    //         //     $languageMap[$name] = $primaryLang;
    //         // }

    //         // Chọn ngôn ngữ theo quốc gia, nếu không có thì mặc định là 'en'
    //         $locale = $languageMap[$country] ?? 'en';

    //         // Lưu ngôn ngữ vào session và đặt ngôn ngữ ứng dụng
    //         Session::put('locale', $locale);
    //         App::setLocale($locale);
    //     }
    //     return $next($request);
    // }

    public function handle($request, Closure $next)
    {
        // Session::forget('locale');

        // Kiểm tra session ngôn ngữ
        if (!Session::has('locale')) {
            // Lấy IP thực của người dùng
            $ip = $this->getRealIp();

            // Lấy thông tin vị trí theo IP
            $location = GeoIP::getLocation($ip);
            $country = $location->country ?? 'Unknown';
            $currency = $location->currency ?? 'USD';

            // Bản đồ quốc gia với mã ngôn ngữ
            $languageMap = [
                'Vietnam'        => 'vi',
                'United States'  => 'en-US',
            ];

            // Xác định ngôn ngữ, mặc định là tiếng Anh nếu không có
            $locale = $languageMap[$country] ?? 'en-US';

            // Lưu vào session và đặt ngôn ngữ ứng dụng
            Session::put('locale', $locale);
            Session::put('currency', $currency);
            App::setLocale($locale);
        } else {
            // Session::forget('locale');
            App::setLocale(Session::get('locale'));
        }

        return $next($request);
    }

    /**
     * Lấy IP thực của người dùng (hỗ trợ Cloudflare, Proxy, Load Balancer)
     */
    private function getRealIp()
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR',  // Proxy phổ biến
            'HTTP_X_REAL_IP',        // Một số proxy khác
            'REMOTE_ADDR',           // IP mặc định
        ];

        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ipList = explode(',', $_SERVER[$header]);
                return trim($ipList[0]); // Lấy IP đầu tiên trong danh sách
            }
        }

        return 'UNKNOWN';
    }
}
