<?php

namespace Service;

use app\admin\model\Config;
use app\admin\model\OrderModel;
use app\admin\model\User;
use think\Log;
use think\Db;

/**
 * 补助
 */
class SubsidyService
{
    public $order;

    public function __construct()
    {
        $this->order = new OrderModel();
    }

    #招商销售奖励
    public function subsidy($orderid)
    {
        $order = OrderModel::get($orderid);
        Db::startTrans();
        try {
            #非店面购买 升级后的代理商每销售1台，给上级额外补助60元/台
//        if ($order['is_shop'] == 2 && $order['sign'] != 3) {
            if (in_array($order['sign'], [1, 2])) {
                if (!empty($order['send_id'])) {
                    $user_agent = User::get($order['send_id']);
                    return $this->dian($user_agent, $order);
                } else if (!empty($order['shop_id'])) {
                    $user_agent_shop = User::get($order['shop_id']);
                    if ($user_agent_shop['pid'] != "") {
                        $user_agent = User::get($user_agent_shop['pid']);
                        return $this->dian($user_agent, $order);
                    }
                }
            }
            Db::commit();
        }catch (Exception $e) {
            Db::rollback();
        }
    }

    #理商伞下购货，额外补助200元/台（同级别不重复拿） user_id  商品数量
    public function umbrella($orderid)
    {
        $order = OrderModel::get($orderid);
        $user_list = getUpUser(User::all(), $order['user_id']);
        $account = new AccountRecord();
        for ($i = 0; $i < count($user_list); $i++) {
            if (in_array(User::get($user_list[$i])['level'], [3, 4, 5, 6])) {
                halt($user_list[$i]);
                User::get($user_list[$i])->setInc("account", Config::get(9)->value * $order['num']);
                $account->setAccountRecord($user_list[$i], "代理商伞下购货",
                    11, 2, Config::get(9)->value * $order['num'], $order['user_id']);
                file_put_contents("./log.txt",
                    date("Y-m-d H:i:s", time()) . "伞下购货补助给id{$user_list[$i]}:" .
                    Config::get(9)->value * $order['num'] . "\n",
                    FILE_APPEND);
                if ($account) {
                    return true;
                    break;
                }
            }
        }
    }

    #招商销售奖励上一步已调用
    public function dian($user_agent, $order)
    {
        $account = new AccountRecord();
        if ($user_agent['pid'] != "") {
            $user_agent_up = User::get($user_agent['pid']);#查询该代理商的上级
            $user_agent_up->setInc("account", Config::get(14)->value * $order['num']);

            $account->setAccountRecord($user_agent_up["id"], "招商销售奖",
                3, 2, Config::get(14)->value * $order['num'], $order['send_id']);#来源id为该代理商
            Log::record("升级后的代理商每销售1台 奖励60");
            return true;
        }
    }

    #根据店面等级进行补助  订单信息
    public function shopLevel($order)
    {
        $account = new AccountRecord();
        if ($order->sign == 2) {
            $user_storefront = User::get($order->shop_id);#查询店面

            if ($user_storefront['level'] == "7") {#一级 补助100 直属代理商余额减去100
                $user_storefront->setInc("account", (Config::get(11)->value * $order['num']));
                $account->setAccountRecord($order->shop_id, "一级店面补助",
                    9, 1, Config::get(11)->value * $order['num'], $order['user_id']);
                Log::record("1店面补助");

                file_put_contents("./log.txt", date("Y-m-d H:i:s", time()) .
                    $order['user_id'] . "一级店面补助\n", FILE_APPEND);

                if ($user_storefront['agency_id'] != 0) {#为0  店面直属代理商
                    $user_agent = User::get($user_storefront['agency_id']);

                    $user_agent->setDec("account", Config::get(11)->value * $order['num']);
                    $account->setAccountRecord($user_storefront->agency_id, "店面补助扣除代理商",
                        11, 1, Config::get(11)->value * $order['num'], $order['shop_id']);
                    Log::record("1店面补助直属代理扣款");
                    file_put_contents("./log.txt", date("Y-m-d H:i:s", time()) . "
                        1店面补助直属代理扣款\n", FILE_APPEND);
                    return true;
                }

            }

            if ($user_storefront['level'] == "8") {#二级 补助50 直属代理商余额减去50
                $user_storefront->setInc("account", Config::get(12)->value * $order['num']);
                $account->setAccountRecord($order->shop_id, "二级店面补助",
                    9, 1, Config::get(12)->value * $order['num'], $order['user_id']);
                Log::record("二级店面补助");
                file_put_contents("./log.txt", date("Y-m-d H:i:s", time()) . " 二级店面补助\n",
                    FILE_APPEND);

                if ($user_storefront->agency_id != 0) {#店面直属代理商
                    $user_agent = User::get($user_storefront['agency_id']);
                    $user_agent->setDec("account", Config::get(12)->value * $order['num']);
                    $account->setAccountRecord($user_storefront->agency_id, "店面补助扣除代理商",
                        11, 2, Config::get(12)->value * $order['num'], $order['shop_id']);
                    file_put_contents("./log.txt", date("Y-m-d H:i:s", time()) .
                        "2店面补助直属代理扣款\n", FILE_APPEND);
                    return true;
                }
            }

        }
    }

    #代理商发货奖励
    public function daili($order)
    {
        $account = new AccountRecord();
        if ($order['sign'] == 1 && $order['send_id'] != "") {#代理发货奖 奖励50
            $user_agent = User::get($order['send_id']);
            $user_agent->setInc("account", (Config::get(10)->value * $order['num']));
            $account->setAccountRecord($order['send_id'], "代理商发货奖",
                12, 2, (Config::get(12)->value * $order['num']), $order['user_id']);
            file_put_contents("./log.txt", date("Y-m-d H:i:s", time()) . "代理商发货补给id:
            {$order['send_id']}\n", FILE_APPEND);
        }
    }
}