<?php

namespace app\agent\controller;

use app\admin\model\RearviewModel;
use app\admin\model\RearviewRecordModel;
use app\agent\model\User;
use think\Controller;
use think\Request;
use think\Session;

class Users extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        $uid = Session::get('id','agent');
        $user = new User();
        if($request->has('mobile','param',true)){
            $user->where('mobile','like','%'.$request->param('mobile').'%');
        }
        if($request->has('nickname','param',true)){
            $user->where('nickname','like','%'.$request->param('nickname').'%');
        }
        if($request->has('from_phone','param',true)){#推荐人手机号
            $fromUser = $user->where(["mobile"=>$request->param('from_phone')])->column("id");
            $where["pid"] = ["IN",$fromUser];
            $user->where($where);
        }

        if($request->has('level','param',true)){
            $user->where(['level'=>$request->param('level')]);
        }
        if($request->has('start','param',true)){#开始日期
            $user->where('created_at','>=',$request->input('start'));
        }
        if($request->has('end','param',true)){#结束日期
            $user->where('created_at','<=',$request->input('end'));
        }
        $data=$user->where('level','in',[1,2,7,8])->where("pid",$uid)
            ->paginate(config("page"),false, [
            'query' => Request::instance()->param(),//不丢失已存在的url参数
        ]);
        foreach ($data as $v){
            $v['pid'] = User::get($v['pid'])["mobile"];
            $v["level"] = config('level')[$v["level"]];
        }

        $page=$this->getPage($data);

        return $this->fetch("users/index",["currentPage"=>$page['currentPage'],
            "total"=>$page['total'], "users"=>$data,"render"=>$page['page']]);
    }
    public function getPage($object)//分页
    {
        if($object){
            $pages['currentPage']= $object->currentPage();
            $pages['total'] = $object->total();
            $pages['page'] = $object->render();
        }else{
            $pages['currentPage']=$pages['total']=$pages['page']=0;
        }
        return $pages;
    }

    public function editStorefront(){
        $input = Request::instance()->only("id,level");

        $user = User::get($input["id"]);
        $user->save($input,["id"=>$input["id"]]);

        return json(["status"=>0]);
    }
    #出货明细
    public function rearview(){
        $uid = Session::get('id','agent');

        $rearview = RearviewModel::where(["uid"=>$uid])->find();#库存
        $rearviewRecord = new RearviewRecordModel();
        $data = $rearviewRecord->where(["uid"=>$uid])->paginate(config("page"),false, [
            'query' => Request::instance()->param(),//不丢失已存在的url参数
        ]);
        $page=$this->getPage($data);
        return $this->fetch("users/rearview",["currentPage"=>$page['currentPage'],
            "rearview"=>$rearview,"total"=>$page['total'], "data"=>$data,"render"=>$page['page']]);
    }
}