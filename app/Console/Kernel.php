<?php

namespace App\Console;

use App\Models\Orders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // 作废超时未支付订单
        $schedule->call(function () {
            // 超时订单
            $orders = Orders::where('status', 1)
                ->where('created_at', '<', date('Y-m-d H:i:s', time() - 1800)) // 30分钟订单有效期
                ->with('orderDetails.goods');

            // 循环订单，修改订单状态,还原商品库存
            try {
                DB::beginTransaction();

                foreach ($orders as $order) {
                    $order->status = 5;
                    $order->save();

                    //还原商品库存
                    foreach ($order->orderDetails as $details) {
                        $details->goods->increment('stock', $details->num);
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
