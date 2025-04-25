<?php
namespace App\Services;

use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\PayPalHttpClient;

class PayPalClient
{
    /**
     * Trả về một instance PayPalHttpClient,
     * sử dụng sandbox hoặc live dựa trên config.
     */
    public static function client(): PayPalHttpClient
    {
        $config = config('paypal');
        $mode   = $config['mode'] === 'live' ? 'live' : 'sandbox';
        $creds  = $config[$mode];

        if ($mode === 'live') {
            $environment = new ProductionEnvironment(
                $creds['client_id'],
                $creds['secret']
            );
        } else {
            $environment = new SandboxEnvironment(
                $creds['client_id'],
                $creds['secret']
            );
        }

        return new PayPalHttpClient($environment);
    }
}
