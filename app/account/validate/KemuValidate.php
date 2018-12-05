<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 小夏 < 449134904@qq.com>
// +----------------------------------------------------------------------
namespace app\account\validate;

use think\Validate;

class KemuValidate extends Validate
{
    protected $rule = [
        'kmid'  => 'require',
        'kmname'  => 'require',
        'kmleibie'  => 'require',
    ];
    protected $message = [
        'kmid.require' => 'ID不能为空',
        'kmname.require' => '名称不能为空',
    ];

   
}