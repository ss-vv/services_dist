<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
    	// 客服退出时 自动下班打卡
	    $schedule->command('punchCard:off')->everyMinute()->withoutOverlapping();
	    // 分配客服
	    $schedule->command('distribute:service')->everyFiveMinutes()->withoutOverlapping();
	    // 计算统计客服业绩
	    $schedule->command('compute:service_achievement')->dailyAt('00:00');

        //用户风险控制检查
	    $schedule->command('risk_check:user')->everyMinute()->withoutOverlapping();
        //代理风险控制检查
	    $schedule->command('risk_check:agent')->everyMinute()->withoutOverlapping();
        //平台风险控制检查
	    $schedule->command('risk_check:platform')->everyMinute()->withoutOverlapping();
        //支付风险控制检查
	    $schedule->command('risk_check:pay')->everyMinute()->withoutOverlapping();

	    // 5分钟未回复的玩家信息 重新处理
	    $schedule->command('handle:msg_not_replay')->everyFiveMinutes()->withoutOverlapping();

        //给客服分配出款任务
        $schedule->command('Withdrawal:assignService')->everyMinute()->withoutOverlapping();

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
