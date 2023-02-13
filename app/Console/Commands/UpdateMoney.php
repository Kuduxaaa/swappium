<?php

namespace App\Console\Commands;

use App\Classes\WhitebitPrivate;
use App\Models\Fee;
use Illuminate\Console\Command;

class UpdateMoney extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:money';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update money with cron';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        WhitebitPrivate::updateMoneyWithCron();
        return 1;
    }
}
