<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\mall\controller;

use cmf\controller\HomeBaseController;
use think\Db;
use app\ip\model\IpModel;

class IndexController extends HomeBaseController
{

public function _initialize() {
		parent::_initialize();
        vendor('Taosdk.TopClient');
        vendor('Taosdk.TopLogger');
        vendor('Taosdk.RequestCheckUtil');
        vendor('Taosdk.ResultSet');
        vendor('Taosdk.Request.TbkItemGetRequest');
        vendor('Taosdk.Request.TbkRebateAuthGetRequest');
        vendor('Taosdk.Request.TbkItemRecommendGetRequest');
        vendor('Taosdk.Request.TbkTpwdCreateRequest');
		vendor('Taosdk.Request.TbkDgMaterialOptionalRequest');
		vendor('Taosdk.Request.TbkDgOptimusMaterialRequest');
    }
	
	function unescape($str) { 
    $ret = ''; 
    $len = strlen($str); 
    for ($i = 0; $i < $len; $i ++) 
    { 
        if ($str[$i] == '%' && $str[$i + 1] == 'u') 
        { 
            $val = hexdec(substr($str, $i + 2, 4)); 
            if ($val < 0x7f) 
                $ret .= chr($val); 
            else  
                if ($val < 0x800) 
                    $ret .= chr(0xc0 | ($val >> 6)) . 
                     chr(0x80 | ($val & 0x3f)); 
                else 
                    $ret .= chr(0xe0 | ($val >> 12)) . 
                     chr(0x80 | (($val >> 6) & 0x3f)) . 
                     chr(0x80 | ($val & 0x3f)); 
            $i += 5; 
        } else  
            if ($str[$i] == '%') 
            { 
                $ret .= urldecode(substr($str, $i, 3)); 
                $i += 2; 
            } else 
                $ret .= $str[$i]; 
    } 
    return $ret; 
}
    public function index()
    {       
	$url="https://uland.taobao.com/coupon/edetail?e=wWaQjQIVGiS7QfSPPGeGaWtu2IzL%2BdggzYhUfEwmy28c5IhvNSuhs97F%2BNp2t6VUQ1tll0cyOk0%2BSd9dbrzdAjOXvE8vBmpwZpvT6zESySqAeX2DTdNXqSBlYPh1ehU1IOKxc9bBptrl5ltu5qeZCA%3D%3D&af=1&pid=mm_98723245_44422162_454430401";

	$turl=parse_url($url);
	//print_r($turl);
	parse_str($turl['query'], $out);
	echo $str=$out['e'];
	echo "<hr>";
	$str="https%3A%2F%2Fs.click.taobao.com%2Ft%3Fe%3Dm%253D2%2526s%253DTee4Dmrn%252F3AcQipKwQzePOeEDrYVVa64LKpWJ%252Bin0XLjf2vlNIV67o39M2CC7zVfxa9spvDO8CnsncUnWONDef2im6aUEYoUENWZPKjh1UbvgHNwusPhTwlx3gxh1EtpR79OTFjg6zHrtX8oRbBtQyWCpnF1NwcqIYULNg46oBA%253D%26pvid%3D10_183.12.240.225_4884_1493360234795%26sc%3DcLA5Dow%26ref%3D%26et%3DFle2JGQo9qIie3gemH7PpdYtcb%252Fph4N2";
	echo $this->unescape($str);
		}
	
	public function itemlist()
    { 
	$c = new \TopClient; 
$c->appkey = config('tao_web_appkey');
$c->secretKey = config('tao_web_secretKey'); 

    $req = new \TbkItemGetRequest;
$req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,seller_id,volume,nick");
$req->setQ("女装");
$req->setCat("16,18");
$req->setItemloc("杭州");

$resp = $c->execute($req);
		$resp =json_encode($resp,JSON_UNESCAPED_UNICODE); 
		$obj=json_decode($resp,true);
print_r($obj);
	return $this->fetch();
	}

	/*通用物料搜索API
	查询优惠券和佣金 
	tk_total_sales		淘客30天月推广量
	tk_total_commi	 	月支出佣金
    coupon_id	 	优惠券id
    num_iid	 	宝贝id
    title	 	商品标题
	commission_rate		1550表示15.5%	佣金比率
	coupon_share_url      券二合一页面链接
	url	 	商品淘客链接
	white_image		商品白底图
	short_title		商品短标题
	category_id	Number		叶子类目id
	volume	 	30天销量
	*/
    public function taomaterial()
    {
		$c = new \TopClient; 
$c->appkey = config('tao_web_appkey');
$c->secretKey = config('tao_web_secretKey'); 
		$req = new \TbkDgMaterialOptionalRequest; 
$req->setQ("男童长袖t恤上衣儿童秋装大童纯棉童装男宝宝男孩秋衣打底衫衣服"); 
$req->setAdzoneId(49170200178); 
$resp = $c->execute($req);
		$resp =json_encode($resp,JSON_UNESCAPED_UNICODE); 
		$obj=json_decode($resp,true);
	    $this->echoarr($obj);
	}
	//淘宝客物料下行API
    public function taooptimus()
    {
		$c = new \TopClient; 
$c->appkey = config('tao_web_appkey');
$c->secretKey = config('tao_web_secretKey'); 
		$req = new \TbkDgOptimusMaterialRequest; 
$req->setQ("男童长袖t恤上衣儿童秋装大童纯棉童装男宝宝男孩秋衣打底衫衣服"); 
$req->setAdzoneId(49170200178); 
$resp = $c->execute($req);
		$resp =json_encode($resp,JSON_UNESCAPED_UNICODE); 
		$obj=json_decode($resp,true);
	    $this->echoarr($obj);
	}

	//生成优惠券淘口令
    public function taopassword()
    {
		$c = new \TopClient; 
$c->appkey = config('tao_web_appkey');
$c->secretKey = config('tao_web_secretKey'); 
		$req = new \TbkTpwdCreateRequest; 
		$req->setUserId(config('tao_userid')); 
		$req->setText("秋装大童纯棉童装男宝"); 
		$req->setExt("{'suerid':'x909088'}");//扩展字段
		$req->setUrl("https://uland.taobao.com/coupon/edetail?e=VC9ZSk%2BCK4kGQASttHIRqXv8f3aaUjT8Zfe%2FFBBxYjWEdvD4wwM%2BY8mNA6MMqj4Op5TCH4VCd3oAyt90yxK9p7P0%2FUQij3p3bd76m3V5xpZkqhIPOdZqAgiH7nP7NhQ6e51DCArOA067wFjt%2BgCPHAyYx1x7IG5v"); 
		$resp = $c->execute($req);
		$resp =json_encode($resp,JSON_UNESCAPED_UNICODE); 
		$obj=json_decode($resp,true);
	
		//$goodslist=$obj['results']['n_tbk_item'];
		    $this->echoarr($obj);
			
	}
		//淘宝客商品关联查询
    public function guanlian()
    {
$c = new \TopClient; 
$c->appkey = config('tao_web_appkey');
$c->secretKey = config('tao_web_secretKey'); 
$req = new \TbkItemRecommendGetRequest; 
$req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url"); 
$req->setNumIid('533938601044'); 
$req->setCount(3); 
$req->setPlatform(1);
 $resp = $c->execute($req);
		$resp =json_encode($resp,JSON_UNESCAPED_UNICODE); 
		$obj=json_decode($resp,true);
	    $this->echoarr($obj);
		}

	public function gettaourl()
    {
		$url = 'https://m.tb.cn/h.38AUp76';
		//$url="https://s.click.taobao.com/SYTcLLw";
		//$url="https://s.click.taobao.com/t?e=m%3D2%26s%3D%2FUjl2q6oM6uw%2Bv2O2yX1MeeEDrYVVa64K7Vc7tFgwiHjf2vlNIV67igSaGexFSTVByy0g7RzMQeHHwP9h6ov8Or%2B8O%2BnLbJGOZNoKzKKT4X2Fqyf6jVzZUg0aHp6CeiCz7vrZJXx%2BKRbF40Yp5WkKhuy1s%2FJnvGAOYQwrhPE0iw%3D&pvid=10_120.239.67.233_886_1539791615007&ut_sk=1.utdid_null_1539791623551.TaoPassword-Outside.lianmeng-app&sp_tk=77+la2dlQ2I2MXdoMDTvv6U=&spm=a211b4.23434152&visa=13a09278fde22a2e&disablePopup=true&disableSJ=1";
		$this->getlonglink($url);
		$this->gettaobaoshortlink($url);
	}
	//解析淘宝app分享短链接
	public function gettaobaoshortlink($url) {
    $clickurl = $url;//这里就用上面提到的url，太长，就不写了
    //第一步，获取代tu参数的链接
    //链接是带https的，需要打开php_openssl.dll。否则获取不到
    $headers = get_headers($clickurl);
    echo $tu =  str_replace('Location: ', '', $headers['5']);
    $eturl = urldecode($tu);
    $u = parse_url($eturl);
	//print_r($u);
    $param = $u['query'];
    $ref = str_replace('tu=', '', $param);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ref);
    curl_setopt($ch, CURLOPT_REFERER, $tu);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_NOBODY,1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
    curl_setopt($ch, CURLOPT_MAXREDIRS,2);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false );
    $out = curl_exec($ch);
    $dd =  curl_getinfo($ch);
    curl_close($ch);
    echo $item_url = $dd['url'];
    //dump ($item_url);
}
	//解析淘宝客长链接
	public function getlonglink($url) {
    $clickurl = $url;//这里就用上面提到的url，太长，就不写了
    //第一步，获取代tu参数的链接
    //链接是带https的，需要打开php_openssl.dll。否则获取不到
    $headers = get_headers($clickurl);
    echo $tu =  str_replace('Location: ', '', $headers['5']);
    $eturl = urldecode($tu);
    $u = parse_url($eturl);
	//print_r($u);
    $param = $u['query'];
    $ref = str_replace('tu=', '', $param);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ref);
    curl_setopt($ch, CURLOPT_REFERER, $tu);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_NOBODY,1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
    curl_setopt($ch, CURLOPT_MAXREDIRS,2);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false );
    $out = curl_exec($ch);
    $dd =  curl_getinfo($ch);
    curl_close($ch);
    echo $item_url = $dd['url'];
    //dump ($item_url);
}
//短链接还原
public function ss()
    {
	$url="http://t.cn/EzwTv6m";
	$headers = get_headers($url, TRUE);
//print_r($headers);
echo $headers['Location'];
	}
	//遍历数组
	public function echoarr($arr){
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
