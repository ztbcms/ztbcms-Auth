<?php

/**
 * author: Jayin <tonjayin@gmail.com>
 */

namespace Auth\Controller;

use Auth\Service\AuthService;

/**
 * 测试已授权
 */
class TestAuthController extends AuthorizedController {

    /**
     * 指定检测 web 平台
     * @var string
     */
    protected $auth_paltform = AuthService::PLATFORM_WEB;
    /**
     * 已授权的可以看到下面文字
     */
    function test(){
        echo '我已授权通过！';
    }

}