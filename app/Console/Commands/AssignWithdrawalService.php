<?php

namespace App\Console\Commands;

use App\Models\Withdrawal;
use App\Models\WithdrawalServiceTime;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class AssignWithdrawalService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Withdrawal:assignService';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '给客服分配出款任务';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   //$uniqid = uniqid();echo "<pre/>";print_r( $uniqid.'===='.md5($uniqid.time()) );die;
        $now        = Carbon::now()->toDateTimeString();
        $todayStart = Carbon::today()->toDateTimeString();
        $onServiceIds = WithdrawalServiceTime::where('status', 1)->where('on_time', '>=', $todayStart)->get(['service_id']);
        //如果客服已经打卡上班
        if ($onServiceIds->isNotEmpty())
        {
            foreach ($onServiceIds as $one)
            {
                $aServiceIds[] = $one->service_id;
            }
            $oWithdrawals = Withdrawal::where('service_id', 0)->where('status', Withdrawal::STATUS_WAITING)->where('created_at', '>=', $todayStart)->get(['id']);
            //如果有需要分配客服的订单
            if ($oWithdrawals->isNotEmpty())
            {
                foreach ($oWithdrawals as $item)
                {
                    $service_id = array_random($aServiceIds); // 随机分配一个上班的客服
                    Withdrawal::where('id', $item->id)->update([
                            'service_id' => $service_id,
                            'updated_at' => $now,
                        ]);
                }
            }
            else
            {
                $this->info('没有需要分配客服的订单');
            }
        }
        else
        {
            $this->info('没有打卡上班的客服');
        }

    }
}
