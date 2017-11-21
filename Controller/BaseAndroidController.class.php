<?php

/**
 * author: Jayin <tonjayin@gmail.com>
 */

namespace Auth\Controller;

use Auth\Service\AuthService;


/**
 * 安卓平台下的授权中间件
 */
class BaseAndroidController extends CorsController {

    public $userid;
    public $nickname;
    public $username;
    public $access_token;


    protected function _initialize() {
        parent::_initialize();

        $access_token = $_SERVER[strtoupper('HTTP_' . AuthService::HTTP_HEADER_ACCESS_TOKEN)];

        if ($access_token) {
            $res = AuthService::checkAccessToken($access_token, AuthService::PLATFORM_ANDROID);
            if ($res['status']) {
                $access_token_record = $res['data'];
                $this->userid = $access_token_record['userid'];
                $this->nickname = $access_token_record['nickname'];
                $this->username = $access_token_record['username'];
                $this->access_token = $access_token_record['access_token'];
            } else {
                $this->ajaxReturn(self::createReturn(false, ['error' => 401, 'msg' => '请求未授权'], '请求未授权，请登录'));
            }
        } else {
            $this->ajaxReturn(self::createReturn(false, ['error' => 401, 'msg' => '请求未授权'], '请求未授权，请登录'));
        }
    }
}