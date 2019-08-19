<?php

namespace App\Console\Commands;

use App\Models\Withdrawal;
use App\Models\WithdrawalServiceTime;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
    {
        //$uniqid = uniqid();echo "<pre/>";print_r( $uniqid.'===='.md5($uniqid.time()) );die;
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
            //有分配订单的客服
            $oRes = Withdrawal::select(DB::raw('service_id, count(*) as nums'))
                                        ->whereIn('service_id', $aServiceIds)
                                        ->where('status', '<=',  Withdrawal::STATUS_HOLDING)
                                        ->where('created_at', '>=', $todayStart)
                                        ->groupBy('service_id')
                                        ->orderBy('nums','ASC')
                                        ->get();
            if ($oRes->isNotEmpty())
            {
                foreach ($oRes as $v)
                {
                    $aServiceIds2[] = $v->service_id;           // 有分配订单的客服
                }
                $aDiff = array_diff($aServiceIds,$aServiceIds2);// $aServiceIds2元素 <= $aServiceIds元素 所以取出的是还未分配的客服
                if(!empty($aDiff))
                {
                    $aServiceIds = array_merge($aDiff,$aServiceIds2);
                }
                else  $aServiceIds = $aServiceIds2;
            }

            $oWithdrawals = Withdrawal::where('service_id', '0')->where('status', Withdrawal::STATUS_WAITING)->where('created_at', '>=', $todayStart)->get(['id']);
            //如果有需要分配客服的订单
            if ($oWithdrawals->isNotEmpty())
            {
                //如果订单数<=上班的客服人数
                $iWithdrawals = count($oWithdrawals);
                $iServiceIds = count($aServiceIds);
                if($iWithdrawals <= $iServiceIds)
                {
                    foreach ($oWithdrawals as $item)
                    {
                        $service_id = array_shift($aServiceIds);
                        Withdrawal::where('id', $item->id)->update([
                            'service_id' => $service_id,
                            'updated_at' => $now,
                        ]);
                    }
                }
                else //如果订单数>上班的客服人数
                {
                    foreach ($oWithdrawals as $k=>$item)
                    {
                        $j = ($k + $iServiceIds) % $iServiceIds;//循环取模
                        $service_id = $aServiceIds[$j];
                        Withdrawal::where('id', $item->id)->update([
                            'service_id' => $service_id,
                            'updated_at' => $now,
                        ]);
                    }
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
