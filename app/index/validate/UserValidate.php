<?php

// +----------------------------------------------------------------------
// | snake
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://baiyf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: NickBai <1902822973@qq.com>
// +----------------------------------------------------------------------

namespace app\index\validate;

use think\Validate;

class UserValidate extends Validate {

    protected $rule = [
        ['username', 'require', '用户名不能为空'],
        ['password', 'require', '密码不能为空'],
        ['email', 'require', '邮箱地址不能为空']
    ];

}
