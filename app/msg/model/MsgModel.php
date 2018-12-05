<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\msg\model;

use think\Db;
use think\Model;

class MsgModel extends Model
{

       public function Addcenlit($da)
    {
        $uQuery = Db::name("user");
		
        $result    = $uQuery->where('msgid', $da['uid'])->find();
		if (!empty($result)) {
        $mQuery = Db::name("msg_client");
		$where = [
            'fid' => $da['fid'],
            'uid' => $da['uid'],
        ];
        $result    = $mQuery->where($where)->find();
        if (empty($result)) {
            $d   = [
                'furl' => $da["furl"],
                'ip'   => get_client_ip(0, true),
                'ct'   => time(),
            ];
			$da["ctime"]=time();
			$dad[]=$d;
			$da["foot"]=json_encode($dad);
			$mQuery->insert($da);
            //$mId = $mQuery->insertGetId($da);
            return 0;
        }else{
			if($result["furl"]!=$da["furl"])
			{
            $d   = [
                'furl' => $da["furl"],
                'ip'   => get_client_ip(0, true),
                'ct'   => time(),
            ];
			$dad=json_decode($result["foot"],true);
			$dad[]=$d;
	    		$da["ctime"]=time();
			    $da["foot"]=json_encode($dad);
			$mQuery->where('id', $result["id"])->update($da);
            return 1;
	 		}
			}
    }
	}
	
}