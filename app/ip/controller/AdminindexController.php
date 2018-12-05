<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\ip\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use app\ip\model\IpModel;

class IndexController extends AdminBaseController
{

    public function index()
    {       
		}

	
    public function auto()
    {
		return $this->fetch(":auto");
		}
    public function autox()
    {
		return $this->fetch(":autox");
		}
    public function autoy()
    {
		return $this->fetch(":autoy");
		}
    public function gettaoip()
    {
		$p=$this->request->param('p');
		if(!$p){$p=19246;}
        $where = [
            'id'=>['gt',$p],
            'region_id'=>['exp',' is NULL']
        ];
		$x=Db::name('ip')->where($where)->find();
		if($x){
		$ip=$x["ip"].".0";
		$rs=dondou_get_ip($ip,'arr');
		print_r($rs);
		if($rs["code"]==0){
		$addc = new ipModel();
		$rt=$rs["data"];
		$rt["id"]=$x["id"];
		$p=$x["id"];
		$addc->Addipinfo($rt);
		echo $u='<div id="u">'.$p.'</div>';

		}
		}
		$this->success('next','/index/gettaoip/p/'.$p,'',1);
	}
    public function gettaoipx()
    {
 		$p=$this->request->param('p');
		if(!$p){$p=2607501;}
       $where = [
            'id'=>['lt',$p],
            'region_id'=>['exp',' is NULL']
        ];
		$x=Db::name('ip')->where($where)->order('id','desc')->find();
		$ip=$x["ip"].".0";
		
		$rs=taobao_sch($ip,'arr');
		print_r($rs);
		if($rs["code"]==0){
		$addc = new ipModel();
		$rt=$rs["data"];
		$rt["id"]=$x["id"];
		$p=$x["id"];
		$addc->Addipinfo($rt);
		echo $u='<div id="p">'.$p.'</div>';

		}
		$this->success('next','/index/gettaoipx/p/'.$p,'',1);
	}
	
	
function plip($rs,$country)
{
if($rs){
$addc = new ipModel();
	
$e = explode('	',$rs);
$f = explode('.',$e[0]);
$g = explode('.',$e[1]);
$f[0]=substr($f[0],2);
if($f[0]<$g[0]){
for($a=$f[0];$a<=$g[0];$a++)	
{
	if($a<$g[0])
	{
		if($a>$f[0]){
		for($b=0;$b<=255;$b++)
		{
			for($c=0;$c<=255;$c++){
				$r["ip"]=$a.".".$b.".".$c;
                $rt[]=$r;
			}
			if(!empty($rt)){
			Db::name('ip')->insertAll($rt);
			unset($rt);
		    }
		}
			
		}else{
		for($b=$f[1];$b<=255;$b++)
		{
			if($b==$f[1]){
			for($c=$f[2];$c<=255;$c++){
				$r["ip"]=$a.".".$b.".".$c;
				$r["country_id"]=$country;
                $rt[]=$r;
			}
			if(!empty($rt)){
			Db::name('ip')->insertAll($rt);
			unset($rt);
		    }
			}else{
			for($c=0;$c<=255;$c++){
				$r["ip"]=$a.".".$b.".".$c;
				$r["country_id"]=$country;
                $rt[]=$r;
			}
			if(!empty($rt)){
			Db::name('ip')->insertAll($rt);
			unset($rt);
			}
			}
		}
			
		}
	}
	//61.0.0 61.31.255
	if($a==$g[0]){
		
		for($b=0;$b<=$g[1];$b++)
		{
			if($b==$g[1]){
			for($c=0;$c<=$g[2];$c++){
				$r["ip"]=$a.".".$b.".".$c;
				$r["country_id"]=$country;
                $rt[]=$r;
			}
			if(!empty($rt)){
			Db::name('ip')->insertAll($rt);
			unset($rt);
		    }
			}else{
			for($c=0;$c<=255;$c++){
				$r["ip"]=$a.".".$b.".".$c;
				$r["country_id"]=$country;
                $rt[]=$r;
			}
			if(!empty($rt)){
			Db::name('ip')->insertAll($rt);
			unset($rt);
			}
			}
		}
		
		
	}
	
}
}


if($f[0]==$g[0]){
	$a=$f[0];
	if($f[1]<$g[1]){
	for($b=$f[1];$b<=$g[1];$b++)
		{
			if($b==$g[1]){
			for($c=0;$c<=$g[2];$c++){
				$r["ip"]=$a.".".$b.".".$c;
				$r["country_id"]=$country;
                $rt[]=$r;
			}
			if(!empty($rt)){
			Db::name('ip')->insertAll($rt);
			unset($rt);
			}
			
			}else{
			if($b>$f[1]){
			for($c=0;$c<=255;$c++){
				$r["ip"]=$a.".".$b.".".$c;
 				$r["country_id"]=$country;
               $rt[]=$r;
			}
			if(!empty($rt)){
			Db::name('ip')->insertAll($rt);
			unset($rt);
			}
				}
			if($b==$f[1]){
			for($c=$f[2];$c<=255;$c++){
				$r["ip"]=$a.".".$b.".".$c;
 				$r["country_id"]=$country;
               $rt[]=$r;
			}
			if(!empty($rt)){
			Db::name('ip')->insertAll($rt);
			unset($rt);
			}
				}
			}
		}
	}
	if($f[1]==$g[1]){
		$a=$f[0];
		$b=$f[1];

			for($c=$f[2];$c<=$g[2];$c++){
				$r["ip"]=$a.".".$b.".".$c;
				$r["country_id"]=$country;
                $rt[]=$r;
			}

			if(!empty($rt)){
			Db::name('ip')->insertAll($rt);
			unset($rt);
			}
		
	}
}// == end
}// for end

	}//plip end
	
	
	
}
