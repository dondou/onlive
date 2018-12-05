<?php
namespace app\mall\controller;
use cmf\controller\HomeBaseController;
use think\Db;
class TaourlController extends HomeBaseController
{
	public function _initialize() {
		parent::_initialize();
        vendor('Taosdk.TopClient');
        vendor('Taosdk.TopLogger');
        vendor('Taosdk.RequestCheckUtil');
        vendor('Taosdk.ResultSet');
        vendor('Taosdk.Request.TopAuthTokenCreateRequest');
    }
	/**code
	   0_yangtata_tkl  yangtata淘口令解析通道无返回
	   0_jujuba jujuba淘口令解析通道无返回
	   1000     淘口令错误or解析数据为空
	   100      无优惠券 无返利
	   101      有返利
	   102      有优惠券
	**/
	public function index()
    {
		
		$tkl= $this->request->param('tkl');
		$tkl="MxhbbP29tGh";
		$da=Db::name("tkl")->where('tkl', $tkl)->find();
		if(empty($da)){
		$da=$this->jujuba_tkl($tkl);
		  if($da['code']=="0_jujuba"){
			  $da=$this->yangtata($tkl);
		  }
		  if($da['code']=="0_yangtata_tkl"){
			  $da=$this->jujuba_tkl($tkl);
		  }
		  
		  if($da['code']=='102' || $da['code']=='101'){
		      //Db::name("tkl")->insert($da);
		  }
		}
		
		 if($da['code']!='100'){
			  $dx=$this->alimama_query($da['itemlink']);
				if(empty($dx)){
				  sleep(2);
			      $dx=$this->alimama_query($da['itemlink']);
			    }
				if(!empty($dx)){
				  $da = array_merge($dx,$da); 
			    }
		}

		
		/**
		if($da['code']=="0_jujuba" or $da['code']=="0_yangtata_tkl"){
			$da['msg']="服务刚刚开了小差。亲，再试试吧";
		}
		if($da['code']==102 || $da['code']==101){
			$dx=$this->alimama_query($da['itemlink']);
		}
		if($da['code']==100){
			$da['msg']="亲，此宝贝无优惠，建议直接购买";
		}
		if(!empty($dx)){
			$da = array_merge($dx,$da); 
			}
			**/
		print_r($da);
	}
	//tool.chaozhi.hk生成二合一淘口令
	function chaozhi_cr_tkl($tkl,$http_url){
		
		$resp=$this->curl_post_form($post_data,$sumbit_url,$http_url);
$url = "http://www.yangtata.com/tools/get_tburl.html?url=".$tkl;
return $resp =$this->curl_get($url,$http_url);
		//$resp =json_decode($resp,true);
}
	
	function add_tkl($da) {
    	$cookie="";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
        $data=curl_exec($ch);
		print_r($data);
		echo $info = curl_getinfo($ch,CURLINFO_EFFECTIVE_URL);
		curl_close($ch);
    }
	//获取重定向 url
    public function curlGet($url) {
    	$cookie="";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
        $data=curl_exec($ch);
		print_r($data);
		echo $info = curl_getinfo($ch,CURLINFO_EFFECTIVE_URL);
		curl_close($ch);
    }
	//喵有券开放平台
 public function query_tkl_1()
    {
		$tkl= $this->request->param('tkl');
		$tkl="￥6m24b7zEDJ7￥";
		$url="https://api.open.21ds.cn/apiv1/getitemgyurlbytpwd?apkey=05723c24-825d-1e94-9076-7314cd778373&tpwdcode=".$tkl."&pid=mm_28698194_164150257_46422950469&tbname=sawenfly&tpwd=1";
		$resp=$this->curl_get($url);
		//$resp =json_encode($resp,JSON_UNESCAPED_UNICODE); 
		$resp=json_decode($resp,true);
		if(!empty($obj['result']['data']['item_id'])){
		
		print_r($resp);
		$obj=$resp['result']['data'];
		if(!empty($obj['coupon_info'])){
		$rs=[
		  'q'=>1,
		  'qinfo'=>$obj['coupon_info'],
		  'qtime'=>$obj['coupon_end_time'],
		  'uland'=>$obj['coupon_click_url'],
		];
		}else{
        $rs=[
		  'slink'=>$obj['item_url'],
		];		}
		$rs=[
		  'tkl'=>$tkl,
		  'mtkl'=>$obj['tpwd'],
		  'item_id'=>$obj['item_id'],
		  'r'=>$obj['max_commission_rate']*100,
		];
		
		}
		    $this->echoarr($resp);
	}
	
	public function query_tkl_itemid(){
		$s="27pybihAuqA";
		$url="https://api.open.21ds.cn/apiv1/tpwdtoid?apkey=05723c24-825d-1e94-9076-7314cd778373&tpwd=".$s;
		$resp=$this->curl_get($url);
		$resp=json_decode($resp,true);
		$this->echoarr($resp);		
		}
	public function query_url_tkl(){
		$s="44397263198";
		$url="https://api.open.21ds.cn/apiv1/getitemgyurl?apkey=05723c24-825d-1e94-9076-7314cd778373&itemid=".$s."&pid=mm_28698194_164150257_46422950469&tbname=sawenfly&tpwd=1";
		$resp=$this->curl_get($url);
		$resp=json_decode($resp,true);
		$this->echoarr($resp);		
		}
	public function query_sclick(){
		$s="6m24b7zEDJ7";
		$s=$this->jujuba_tkl($s);
		echo $s=substr($s,0, strpos($s,"?"));
		
		$url="https://api.open.21ds.cn/apiv1/sclicktoid?apkey=05723c24-825d-1e94-9076-7314cd778373&sclickurl=".$s;
		$resp=$this->curl_get($url);
		$resp=json_decode($resp,true);
		//$this->echoarr($resp);		
		}
		//喵有券开放平台--结束
		//http://jujuba.top
	function jujuba_tkl($tkl){
		 $url="http://jujuba.top/api/tkl.php?tkl=".$tkl;
		 $resp=$this->httpcode($url,'http://jujuba.top');
		 if($resp==200){
		 $resp=$this->curl_get($url,'http://jujuba.top');
		 $resp=str_replace("}{",",",$resp);
		 $resp=json_decode($resp,true);
		//$this->echoarr($resp);	
		if(!empty($resp['data']['url'])){
			
			if(strpos($resp['data']['url'],'s.click.taobao.com') !== false){
             $da['sort']="sclick";
			 $da['code']="101";
            }
            if(strpos($resp['data']['url'],'uland.taobao.com') !== false){
             $da['sort']="uland";
			 $da['code']="102";
            }
			if(strpos($resp['data']['url'],'a.m.taobao.com') !== false){
			 $da['sort']="x";
             $da['code']="100";
            }
			if($da['code']==102 || $da['code']==101){
 		      $da['link']=$resp['data']['url'];
			  if($da['sort']=='sclick'){
	        	if(strpos($da['link'],'t?e=') === false){
		           $da['link']=$this->clear_urlcan($da['link']);
		    	 }
			  }
				 
		       $http_url="http://www.yangtata.com";
		       $resp =$this->yangtata_tk_url($da['link'],$http_url);
		       $resp =json_decode($resp,true);
		       $da['itemlink']=$resp['data'];
		       $e = parse_url($da['itemlink']);
		       parse_str($e['query'], $e);
		       if($e){$da['itemid']=$e['id'];}
			}
		}else{
          $da['code']="1000";
		}
		
	}else{
         $da['code']="0_jujuba";
	}
		  $da['tkl']=$tkl;
		return $da;
}
//阿里妈妈超级查询
	function alimama_query($http_url){
$url = "https://pub.alimama.com/items/search.json?q=".$http_url;
$resp =$this->curl_get($url,$http_url);
		$resp =json_decode($resp,true);
		//$this->echoarr($resp);
		$da=array();
		if(!empty($resp['data']['pageList'][0]['title'])){
			$da['title']=$resp['data']['pageList'][0]['title'];
		}
		if(!empty($resp['data']['pageList'][0]['pictUrl'])){
			$da['pictUrl']=$resp['data']['pageList'][0]['pictUrl'];
		}
		if(!empty($resp['data']['pageList'][0]['couponInfo'])){
			$da['couponInfo']=$resp['data']['pageList'][0]['couponInfo'];
		}
		if(!empty($resp['data']['pageList'][0]['zkPrice'])){
			$da['zkPrice']=$resp['data']['pageList'][0]['zkPrice'];
		}
		if(!empty($resp['data']['pageList'][0]['tkCommonRate'])){
			$da['tkCommonRate']=$resp['data']['pageList'][0]['tkCommonRate'];
		}
		if(!empty($resp['data']['pageList'][0]['tkCommonFee'])){
			$da['tkCommonFee']=$resp['data']['pageList'][0]['tkCommonFee'];
		}
		if(!empty($resp['data']['pageList'][0]['tkRate'])){
			$da['tkRate']=$resp['data']['pageList'][0]['tkRate'];
		}
		if(!empty($resp['data']['pageList'][0]['tkCommFee'])){
			$da['tkCommFee']=$resp['data']['pageList'][0]['tkCommFee'];
		}
		return $da;
}


		
    //www.yangtata.com 
		function yangtata($tkl){
        $http_url="http://www.yangtata.com";
		$da=$this->yangtata_tkl($tkl,$http_url);
		if($da['code']==102 || $da['code']==101){
		if(empty($da['itemid'])){
			
		//print_r($da);
		if($da['sort']=='sclick'){
			if(strpos($da['link'],'t?e=') === false){
		      $da['link']=$this->clear_urlcan($da['link']);
			 }
		 $resp =$this->yangtata_tk_url($da['link'],$http_url);
		 $resp =json_decode($resp,true);
		 $da['itemlink']=$resp['data'];
		 $e = parse_url($da['itemlink']);
	     parse_str($e['query'], $e);
	     if($e){$da['itemid']=$e['id'];}
			}
		}
		}
		
		$da['tkl']=$tkl;
		return $da;
}
//淘链接解析itemid
	function yangtata_tk_url($tkl,$http_url){
$url = "http://www.yangtata.com/tools/get_tburl.html?url=".$tkl;
return $resp =$this->curl_get($url,$http_url);
		//$resp =json_decode($resp,true);
}
//淘口令解析
function yangtata_tkl($tkl,$http_url){
$url = "http://www.yangtata.com/tools/get_tbcode.html?test=".$tkl;
$resp=$this->httpcode($url,'http://jujuba.top');
if($resp==200){

        $resp =$this->curl_get($url,$http_url);
		$resp =json_decode($resp,true);
		//print_r($resp);
		if(!empty($resp['data'])){
		$data=$resp['data'];
		$data = explode("</br></br>", $data);
        foreach($data as $val){
          if(strpos($val,'原链接') !== false){
            $da['link']=str_replace("原链接：","",$val);
          }

          if(strpos($val,'产品链接') !== false){
            $da['itemlink']=str_replace("产品链接：","",$val);
            $e = parse_url($da['itemlink']);
          	parse_str($e['query'], $e);
          	if($e){$da['itemid']=$e['id'];}
          }
         }
            if(strpos($da['link'],'a.m.taobao.com') !== false){
            $da['sort']="x";
            $da['code']="100";
             }		 
            if(strpos($da['link'],'s.click.taobao.com') !== false){
             $da['sort']="sclick";
             $da['code']="101";
             }
            if(strpos($da['link'],'uland.taobao.com') !== false){
            $da['sort']="uland";
            $da['code']="102";
             }
		}else{
            $da['code']="1000";
			}
  }else{
	  $da['code']="0_yangtata_tkl";
  }
  return $da;
}

//淘宝二合一链接解密 因为需要令牌(登录) 暂时搁置
public function uland(){

	$uland="https://uland.taobao.com/coupon/edetail?e=%2BJvgnp9vtlht3vqbdXnGlmSqEg851moCCIfuc%2Fs2FDojlBAztOzKnXlMbjKTgfxfK56Yj1fdSkWrappEgRJcHjFCuH3ndvEEGZFBsOjrnBPEQlnWllxAq%2FDSiJQ%2BTzUuXFPF4Gzu%2Bi7vZehLUymc%2BekCLKxZXly7onv6QcvcARY%3D&af=4&pid=mm_28698194_164150257_49170200178&ut_sk=1.utdid_null_1540915966233.TaoPassword-Outside.lianmeng-app&sp_tk=77+lbWQyZGI3SnZ5U07vv6U=&spm=a211b4.23388084&visa=13a09278fde22a2e&disablePopup=true&disableSJ=1";
	 $url='https://acs.m.taobao.com/h5/mtop.alimama.union.hsf.coupon.get/1.0/?jsv=2.4.0&appKey=12574478&t=1528968734773&sign=854db05a11347be3faa5b0980718c805&api=mtop.alimama.union.hsf.coupon.get&v=1.0&AntiCreep=true&AntiFlood=true&type=jsonp&dataType=jsonp&callback=mtopjsonp1&data={"e":"V3jvW87rIXQGQASttHIRqeISwO2LYyDDB3KnYDrZgFzoEnMr9v+wai7tBIKWCr05a7E1JQXqpN8SMYg4guO+wpQ5wfGz/u+NFY/YQj1mGycVF+LQAJXviLEsC315c4LYGTHHFijCzT886ds3Wskk0w==","pid":"mm_33231688_7050284_23466709"}';
    echo $t=$this->msectime();
	$appkey=config('tao_appkey');
	$appkey='12574478';
	$_m_h5_tk=$this->curl_get_tk($url);
	$e = parse_url($uland);
	parse_str($e['query'], $e);
	$data=urlencode($e['e']);
	$sign=md5($_m_h5_tk.'&'.$t.'&'.$appkey.'&'.$data);
	$newurl='https://acs.m.taobao.com/h5/mtop.alimama.union.hsf.coupon.get/1.0/?jsv=2.4.0&appKey='.$appkey.'&t='.$t.'&sign='.$sign.'&api=mtop.alimama.union.hsf.coupon.get&token=6102803bb90181a7e45ec5fe260aa5a7b7d3714637276ce11507221&v=1.0&AntiCreep=true&AntiFlood=true&type=jsonp&dataType=jsonp&callback=mtopjsonp1&data={"e":'.'"'.$data.'","pid":"mm_28698194_164150257_49170200178"}';
	$ch =curl_init($newurl);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_HEADER,0);
$c=curl_exec($ch);
curl_close($ch);
$c =json_encode($c,JSON_UNESCAPED_UNICODE); 
$c =json_decode($c,true);
echo "nima";
print_r($c);
}
//curl获取cookies提取_m_h5_tk
function curl_get_tk($url){
$ch =curl_init($url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_HEADER,1);
$c=curl_exec($ch);
curl_close($ch);
if($c){
preg_match('/^Set-Cookie: (.*?);/m',$c,$m);
$preg= '/=[\s\S]*?_/i';
	preg_match_all($preg,$m[1],$res);
	   if(!empty($res[0][0])){
	     $c=$res[0][0];
        return str_replace(array('=','_'),array('',''), $c);
         }
	}
}
//判断服务器网址是否正常
function httpcode($url,$http_url){
  $ch = curl_init();
  $timeout = 3;
  curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
  curl_setopt($ch, CURLOPT_REFERER, $http_url);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
  curl_setopt($ch, CURLOPT_HEADER, 1);
  curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_exec($ch);
  return $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
  curl_close($ch);
}
function curl_get($url,$http_url){
	
$ch =curl_init($url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_REFERER, $http_url);
curl_setopt($ch,CURLOPT_HEADER,0);
return curl_exec($ch);
curl_close($ch);

}
function tkl_get($url,$http_url){
$ch =curl_init($url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_REFERER, $http_url);
curl_setopt($ch,CURLOPT_HEADER,0);
return curl_exec($ch);
curl_close($ch);
}

public function get_token(){
	$co="CTjhmdYWbasoOCJF1ag0FWpQ3117686";
	$c = new \TopClient;
$c->appkey = config('tao_web_appkey');
$c->secretKey = config('tao_web_secretKey'); 
$req = new \TopAuthTokenCreateRequest;
$req->setCode($co);
$resp = $c->execute($req);
		$resp =json_encode($resp,JSON_UNESCAPED_UNICODE); 
		$obj=json_decode($resp,true);
	    $this->echoarr($obj);

	}
//curl模拟post提交表单
function curl_post_form($post_data,$sumbit_url,$http_url){
	//$headers = $this->randIp();
$ch = curl_init();
//设置变量
curl_setopt($ch, CURLOPT_URL, $sumbit_url);
//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);//相当关键，这句话是让curl_exec($ch)返回的结果可以进行赋值给其他的变量进行，json的数据操作，如果没有这句话，则curl返回的数据不可以进行人为的去操作（如json_decode等格式操作）
//CURLOPT_RETURNTRANSFER 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
curl_setopt($ch, CURLOPT_HEADER, 1);//参数设置，是否显示头部信息，1为显示，0为不显示
curl_setopt($ch, CURLOPT_REFERER, $http_url);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_TIMEOUT, 1);//设置curl执行超时时间最大是多少
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
//执行并获取结果
echo $resp = curl_exec($ch);
//$info=curl_getinfo($ch);
//print_r($info);
curl_close($ch);
return $resp;
}
//返回毫秒级时间戳
function msectime() {
list($msec, $sec) = explode(' ', microtime());
return $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
}
//遍历数组
function echoarr($arr){
		foreach($arr as $k1=>$v1){
			if(is_array($v1)){
		       foreach($v1 as $k2=>$v2){
				   if(is_array($v2)){
					   foreach($v2 as $k3=>$v3){
						   if(is_array($v3)){
							   foreach($v3 as $k4=>$v4){
								   if(is_array($v4)){
									    foreach($v4 as $k5=>$v5){
											 if(is_array($v5)){
												 foreach($v5 as $k6=>$v6){
										        	echo "6--------------------- ".$k6." => ".$v6."<br>";
												 }
											 }else{
											echo "5---------------- ".$k5." => ".$v5."<br>";
											 }
										}
								   }else{
								   echo "4------------ ".$k4." => ".$v4."<br>";
								   }
							   }
							   
						   }else{
						   echo "3-------- ".$k3." => ".$v3."<br>";
						   }
						   
					   }
				   }else{
		              echo "2---- ".$k2." => ".$v2."<br>";
				   }
		        }
			}else{
				echo "1 ".$k1." => ".$v1."<br>";
				}
		     }
		}
/**
 * php截取指定两个字符之间字符串，默认字符集为utf-8 Power by 大耳朵图图
 * @param string $begin  开始字符串
 * @param string $end    结束字符串
 * @param string $str    需要截取的字符串
 * @return string
 */
function cut($begin,$end,$str){
    $b = mb_strpos($str,$begin) + mb_strlen($begin);
    $e = mb_strpos($str,$end) - $b;
 
    return mb_substr($str,$b,$e);

}
function clear_urlcan($url){

    $rstr='';

    $tmparr=parse_url($url);

    $rstr=empty($tmparr['scheme'])?'http://':$tmparr['scheme'].'://';

    $rstr.=$tmparr['host'].$tmparr['path'];

    return $rstr;

}


function randIP(){
       $ip_long = array(
           array('607649792', '608174079'), //36.56.0.0-36.63.255.255
           array('1038614528', '1039007743'), //61.232.0.0-61.237.255.255
           array('1783627776', '1784676351'), //106.80.0.0-106.95.255.255
           array('2035023872', '2035154943'), //121.76.0.0-121.77.255.255
           array('2078801920', '2079064063'), //123.232.0.0-123.235.255.255
           array('-1950089216', '-1948778497'), //139.196.0.0-139.215.255.255
           array('-1425539072', '-1425014785'), //171.8.0.0-171.15.255.255
           array('-1236271104', '-1235419137'), //182.80.0.0-182.92.255.255
           array('-770113536', '-768606209'), //210.25.0.0-210.47.255.255
           array('-569376768', '-564133889'), //222.16.0.0-222.95.255.255
       );
       $rand_key = mt_rand(0, 9);
       $ip= long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));
       $headers['CLIENT-IP'] = $ip; 
       $headers['X-FORWARDED-FOR'] = $ip; 
 
       $headerArr = array(); 
       foreach( $headers as $n => $v ) { 
           $headerArr[] = $n .':' . $v;  
       }
       return $headerArr;    
   }
}
