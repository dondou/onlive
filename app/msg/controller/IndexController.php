<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\msg\controller;

use cmf\controller\HomeBaseController;
use think\Db;
use app\msg\model\MsgModel;
use app\msg\model\IpModel;

header("access-control-allow-origin:*");
class IndexController extends HomeBaseController
{
    public function _initialize(){
       $this->push_ser=config('push_ser');//
       $this->login_ser=config('login_ser');//
       $this->msg_json=config('msg_json');//
       $this->sta_json=config('sta_json');//
       $this->ip_ser=config('ip_ser');//
    }

    public function index()
    {
        $where   = [];
        $request = input('request.');
        if (!empty($request['uid'])) {
            $where['fuid'] = intval($request['dondou_uid']);
        }        
		//return $this->fetch(':index');
		$usersQuery = Db::name('user_msg_view');

        $list = $usersQuery->where($where)->field("msgid,nicename")->order("list_order ASC")->select();
		echo json_encode($list);    
		}
    public function crec()
    {
        $rs = $this->request->param();
		//if($rs['uid'])
		//{
		//$addc = new MsgModel();
		//$addc->Addcenlit($rs);
		//}
		//$ip=get_client_ip(0, true);
		$ip=$rs["ip"];
		//$rs=json_encode(dondou_get_ip($ip),JSON_UNESCAPED_UNICODE);
		$rs=json_decode(dondou_get_ip($ip),true);
		if($rs["code"]==0){
		$addc = new ipModel();
		$rt=$rs["data"];
		$addc->Addipinfo($rt);
		print_r($rt);
		}
		//json(json_encode($rs))->send();
	}
    public function conf()
    {
		$murl=$this->request->param('murl');
        //$fuid = $this->request->param('uid');
        if ($murl) {
        $where["msgurl"] = $murl;
		$usersQuery = Db::name('user');
        $uid = $usersQuery->where($where)->field("msgid")->find();
		$usersQuery = Db::name('user_msg_view');
        $list = $usersQuery->where($where)->field("msgid,nicename,avatar,msgurl")->order("list_order ASC")->select();
		$rs['userlist']=$list;
        $rs['msg_json']=$this->msg_json;
        $rs['sta_json']=$this->sta_json;
		$rs['login_ser']=$this->login_ser;
		$rs['ip_ser']=$this->ip_ser;
		
		$rs['furl']=$murl;
		$rs['uid']=$uid['msgid'];
		//print_r($list);
        }else{
		$rs['err']="y";
		}
		$data =json_encode($rs);
		json($data)->send();
    }
	//推送消息
	public function omsg()
    {
        $tid = $this->request->param('tid');
        $fid = $this->request->param('fid');
        $divid = $this->request->param('divid');
        $headpic = $this->request->param('headpic');
        $nicename = $this->request->param('nicename');
        $msg = $this->request->param('dondou_word');
        //$msg = "#";
		$rdata=$this->push_msg($fid,$tid,$nicename,$headpic,$msg,$this->push_ser);		
        //$data = $this->request->param();
		//$data =json_encode($data);
		//json($data)->send();
		//$rstd=$this->push_msg('8898',nl2br($msg));
		//$data["msg"]=$msg;
		$data["rst"]=$rdata;
		$data["divid"]=$divid;
		$data =json_encode($data);
		//echo $data;
		json($data)->send();
    }
	//推送是否在线状态
	public function osta()
    {
        $fid = $this->request->param('fid');
        $sta = $this->request->param('sta');
		$rdata=$this->push_sta($fid,$sta,$this->push_ser);		
		$data["rst"]=$rdata;
		$data =json_encode($data);
		json($data)->send();
    }
    //获取访客ip信息
	public function gip()
    {
        //$rs['cip']=get_client_ip(0,true);
		$rs['cip']="183.238.50.211";
		$ipinfo=$this->ipinfo($rs['cip']);
		if($ipinfo){
		$ipinfo=json_decode($ipinfo,true);
        $rs['county']=$ipinfo["data"]["county"];
        $rs['city']=$ipinfo["data"]["city"];
        $rs['region']=$ipinfo["data"]["region"];
        $rs['country']=$ipinfo["data"]["country"];
        $rs['rst']="o";
        }else{
        	$rs['rst']="x";
        }
		$data =json_encode($rs);
		json($data)->send();
    }
	private function push_sta($fid,$sta,$sendurl) {
		// 指明给谁推送，为空表示向所有在线用户推送
		// 推送的url地址，使用自己的服务器地址
		$push_api_url = $sendurl;
		$post_data = array(
 		  "type" => "pushsta",
		  "fo" => $fid, 
		  "to" => '', 
		  "sta" => $sta, 
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

	private function push_msg($fid,$uid,$nname,$headpic,$msg,$sendurl) {
		// 指明给谁推送，为空表示向所有在线用户推送
		// 推送的url地址，使用自己的服务器地址
		$push_api_url = $sendurl;
		$post_data = array(
 		  "type" => "dondoupush",
 		  "content" => $msg,
 		  "to" => $uid, 
		  "fo" => $fid, 
		  "nicename" => $nname, 
		  "headpic" => $headpic, 
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
protected function ipinfo($ip) {

    $host = "https://dm-81.data.aliyun.com";
    $path = "/rest/160601/ip/getIpInfo.json";
    $method = "GET";
    $appcode = "43d88d9754694c7ea12591fcd637e21d";
    $headers = array();
    array_push($headers, "Authorization:APPCODE " . $appcode);
    $querys = "ip=".$ip;
    $bodys = "";
    $url = $host . $path . "?" . $querys;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    //curl_setopt($curl, CURLOPT_FAILONERROR, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($curl, CURLOPT_HEADER, true);
    if (1 == strpos("$".$host, "https://"))
    {
       curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    }
		return curl_exec($curl);
   //$ipinfo=json_decode(curl_exec($curl),true);
//return $counid=$ipinfo["data"]["country_id"];
//echo $ip["data"]["city_id"];
 //$c="MO,HK,TW";
  }

}
