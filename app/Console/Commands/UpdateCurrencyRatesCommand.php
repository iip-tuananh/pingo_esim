<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\UpdateCurrencyRatesJob;

class UpdateCurrencyRatesCommand extends Command
{
    protected $signature = 'currency:update';
    protected $description = 'Cập nhật tỷ giá tiền tệ mới nhất';

    public function handle()
    {
        dispatch(new UpdateCurrencyRatesJob());
        $this->info('Đã gửi Job cập nhật tỷ giá!');
    }
}
