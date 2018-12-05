<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\msg\controller;

use think\Validate;
use cmf\controller\HomeBaseController;
use app\user\model\UserModel;
header("access-control-allow-origin:*");
class LoginController extends HomeBaseController
{
	
    public function _initialize(){
       $this->push_ser=config('push_ser');//这里赋值
    }
    public function index()
    {
        echo $this->aa;//这里调用
		$rs['dt']="good";
        $data =json_encode($rs);
		json($data)->send();
		 }

	public function osta()
    {
 		$rs["fid"] = $this->request->param('fid');
 		$rs["tid"] = $this->request->param('tid');
		if($rs["tid"]){
		$rt["st"]=$this->ck_sta($rs["fid"],$rs["tid"],$this->push_ser);	
		json(json_encode($rt))->send();
        //session("user", null);//只有前台用户退出
        //return redirect($this->request->root() . "/");
		}
    }

    public function doLogin()
    {
		$data= $this->request->param();
		
		if (!cmf_captcha_check($data['captcha'])) {
                $data['err']=('error');
            }
		    $userModel         = new UserModel();
            $user['user_pass'] = $data['password'];
            if (Validate::is($data['username'], 'email')) {
                $user['user_email'] = $data['username'];
                $log                = $userModel->doEmail($user);
            } else if (preg_match('/(^(13\d|15[^4\D]|17[13678]|18\d)\d{8}|170[^346\D]\d{7})$/', $data['username'])) {
                $user['mobile'] = $data['username'];
                $log            = $userModel->doMobile($user);
            } else {
                $user['user_login'] = $data['username'];
                $log                = $userModel->doName($user);
            }
            $session_login_http_referer = session('login_http_referer');
            $redirect                   = empty($session_login_http_referer) ? $this->request->root() : $session_login_http_referer;
			$user = cmf_get_current_user();
			$rs["info"]=$log;
			if($log==0)
			{
			$rs["avatar"]=$user["avatar"];
			$rs["msgid"]=$user["msgid"];
			$rs["nicename"]=$user["user_nickname"];
		$this->push_sta('9900',$rs["msgid"],$this->push_ser);
				}
			/*
            switch ($log) {
                case 0:
                    $this->success('登录成功', $redirect);
                    break;
                case 1:
                    $this->error('登录密码错误');
                    break;
                case 2:
                    $this->error('账户不存在');
                    break;
                default :
                    $this->error('未受理的请求');
            }
            */
        //$data =json_encode($data);
	    json(json_encode($rs))->send();
			
    }
	private function ck_sta($fid,$tid,$sendurl) {
		// 指明给谁推送，为空表示向所有在线用户推送
		// 推送的url地址，使用自己的服务器地址
		$push_api_url = $sendurl;
		$post_data = array(
 		  "type" => "cksta",
		  "fo" => $fid, 
		  "to" => $tid, 
		 );
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $push_api_url );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
		curl_setopt ($ch, CURLOPT_HTTPHEADER, array("Expect:"));
		$return = curl_exec ( $ch );
		curl_close ( $ch );
		//var_export($return);
		return $return;
    }
	private function push_sta($fid,$tid,$sendurl) {
		// 指明给谁推送，为空表示向所有在线用户推送
		// 推送的url地址，使用自己的服务器地址
		$push_api_url = $sendurl;
		$post_data = array(
 		  "type" => "pushsta",
		  "fo" => $fid, 
		  "to" => $tid, 
		 );
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $push_api_url );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
		curl_setopt ($ch, CURLOPT_HTTPHEADER, array("Expect:"));
		$return = curl_exec ( $ch );
		curl_close ( $ch );
		//var_export($return);
		return $return;
    }

}
