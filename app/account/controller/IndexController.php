<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\account\controller;

use cmf\controller\HomeBaseController;
use think\Db;
use app\account\model\accountModel;

class IndexController extends HomeBaseController
{

    public function importexecl()
    {       
	$filePath="upload/x.xlsx";
	$addc = new AccountModel();
	$data=$addc->readexecl($filePath);
	
	foreach($data as $i=>$val){
		$s['kmid'] = $val["A"];
		$s['kmname'] = $val["B"];
		$s['kmleibie'] = $val["C"];
        if($val["D"] == '借'){
            $s['kmfangxiang'] = "j";
        }else{
            $s['kmfangxiang'] = "d";
		$s['kmwbhs'] = $val["E"];
		$s['kmqmth'] = $val["F"];
		$s['kmfzhs'] = $val["G"];
        }
		/****/
		// Db::name("account_kemu")->insert($s);
	}
		}


    public function someip()
    {
        $where = [
			'sch_sta'=>['eq',0],
            //'country_id'=>['eq','US'],
			//'region_id'=>['eq','']
            'region_id'=>['exp',' is NULL']
        ];
		$x=Db::name('ip')->where($where)->limit(100)->select();
		if(!empty($x)){
		
foreach ($x as $ix){
	echo $ix["ip"]." | ";
	
	$ip=$ix["ip"].".1";
		$rs=dondou_get_ip($ip,'arr');
		print_r($rs);
		if($rs["code"]==0&&!empty($rs["data"])){
		$addc = new ipModel();
		$rt=$rs["data"];
		$rt["id"]=$ix["id"];
		$addc->Addipinfo($rt);
		}
}
	echo $u='<div id="u">ooopp</div>';
		$this->success('next','/ip/index/someip','',1);
		}
	}


    public function gettaoip()
    {
        $where = [
			'sch_sta'=>['eq',0],
            'country_id'=>['eq','CN'],
			//'region_id'=>['eq','']
            'region_id'=>['exp',' is NULL']
        ];
		$x=Db::name('ip')->where($where)->find();
		if($x){
		echo $p=$x["id"].'<br>';
		$ip=$x["ip"].".1";
		$rs=dondou_get_ip($ip,'arr');
		if($rs["code"]==0&&!empty($rs["data"])){
		$addc = new ipModel();
		$rt=$rs["data"];
		$rt["id"]=$x["id"];
		print_r($rs);
		$addc->Addipinfo($rt);
		echo $u='<div id="u">'.$p.'</div>';

		}
		$this->success('next','/ip/index/gettaoip','',1);
		}
	}
    public function gettaoipx()
    {
 		$p=$this->request->param('p');
		
       $where = [
			'id'=>['lt',$p],
			'sch_sta'=>['eq',0],
            'country_id'=>['eq','CN'],
            //'region_id'=>['ep','']
            'region_id'=>['exp',' is NULL']
        ];
		$x=Db::name('ip')->where($where)->order('id','desc')->find();
		if($x){
		$ip=$x["ip"].".0";
		$rs=dondou_get_ip($ip,'arr');
		//$rs=taobao_sch($ip,'arr');
print_r($rs);
		if($rs["code"]==0&&!empty($rs["data"])){
		
		$addc = new ipModel();
		$rt=$rs["data"];
		$rt["id"]=$x["id"];
		$p=$x["id"];
		$addc->Addipinfo($rt);
		echo $u='<div id="p">'.$p.'</div>';

		}
		}
		$this->success('next','/ip/index/gettaoipx/p/'.$p,'',1);
	}

}
