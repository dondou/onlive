<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use app\admin\model\ThemeModel;
use think\Db;
use think\Validate;
use tree\Tree;

class MsgController extends AdminBaseController
{
    /**
     */
    public function index()
    {
        $push_ser = config('push_ser');
        $login_ser = config('login_ser');
        $msg_json = config('msg_json');
        $sta_json = config('sta_json');
        $ip_ser = config('ip_ser');
        $ip_ser_bk = config('ip_ser_bk');
		
        $this->assign('push_ser', $push_ser);
        $this->assign('login_ser', $login_ser);
        $this->assign('login_ser', $login_ser);
        $this->assign('login_ser', $login_ser);
        $this->assign('login_ser', $login_ser);
        return $this->fetch();
    }


    /**
     */
    public function indexpost()
    {
        $data = $this->request->param();
        $result = cmf_set_dynamic_config(['push_ser' => $data['push_ser'],'login_ser' => $data['login_ser']]);
        if ($result === false) {
            $this->error('配置写入失败!');
        }

        $this->success("配置成功");

    }


}