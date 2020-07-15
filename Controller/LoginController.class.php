<?php

/**
 * author: Jayin <tonjayin@gmail.com>
 */

namespace Auth\Controller;

use Auth\Service\AuthService;


/**
 * 统一登录入口
 */
class LoginController extends CorsController {

    /**
     * web平台登录
     */
    function web(){
        $username = I('post.username');
        $password = I('post.password');

        $result = AuthService::auth($username, $password, AuthService::PLATFORM_WEB);
        if($result['status']){
            $this->ajaxReturn(self::createReturn(true, $result['data'], '登录成功'));
        }else{
            $this->ajaxReturn(self::createReturn(false, null, $result['msg']));
        }
    }

    /**
     * android平台登录
     */
    function android(){
        $username = I('post.username');
        $password = I('post.password');

        $result = AuthService::auth($username, $password, AuthService::PLATFORM_ANDROID);
        if($result['status']){
            $this->ajaxReturn(self::createReturn(true, $result['data'], '登录成功'));
        }else{
            $this->ajaxReturn(self::createReturn(false, null, $result['msg']));
        }
    }

}