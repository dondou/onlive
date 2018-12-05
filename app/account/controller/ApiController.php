<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\ip\controller;

use cmf\controller\HomeBaseController;
use think\Db;
use app\ip\model\IpModel;

header("access-control-allow-origin:*");
class ApiController extends HomeBaseController
{

    public function index()
    {       
	$ip=get_client_ip();
	$ip="183.238.50.211";
	$rs=dondou_get_ip($ip,'arr');
	//$s= json_encode();
	 print_r($rs);
	 print_r(taobao_sch($ip,'arr'));
	}
	
    public function sch()
    {       
		$ip=$this->request->param('ip');
		if($ip){
			$rs = explode('.',$ip);
			$ipn=$rx[0].".".$rx[1].".".$rx[2];
			$where = [
            'ip'=>['eq',$ipn],
            ];
		$ips=Db::name('ip_view')->where($where)->find();
		if($ips){
			$ipinfo["code"]=0;
			$ipinfo["data"]=$ips;
            $ipinfo =json_encode($ipinfo);
			
			
		}else{
				$ipinfo=dondou_get_ip($ip);
				if($ipinfo){$ipx=json_decode($ipinfo,true);
				if($ipx["code"]==0){
					$ipx["data"]["ip"]=$ipn;
					$addc = new ipModel();
					$addc->insterip($ipx["data"]);
					}
				}
				}
		}
		json($ipinfo)->send();		
	}
	
}
