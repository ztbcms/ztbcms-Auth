<?php

/**
 * author: zhlhuang <zhlhuang888@foxmail.com>
 */

namespace Auth\Controller;

use Auth\Service\AuthService;


/**
 * PC_web下的授权中间件
 */
class BaseWebController extends CorsController {

    public $userId;
    public $nickname;
    public $username;
    public $access_token;
    public $store_id;


    protected function _initialize() {
        parent::_initialize();
        $access_token = $_SERVER[strtoupper('HTTP_' . AuthService::HTTP_HEADER_ACCESS_TOKEN)];
        $store_id = $_SERVER[strtoupper('HTTP_' . AuthService::HTTP_HEADER_STORE_ID)];
        if ($access_token) {
            $res = AuthService::checkAccessToken($access_token, AuthService::PLATFORM_PC_Web);
            if ($res['status']) {
                $access_token_record = $res['data'];
                $this->userId = $access_token_record['userid'];
                $this->nickname = $access_token_record['nickname'];
                $this->username = $access_token_record['username'];
                $this->access_token = $access_token_record['access_token'];
                $this->store_id = $store_id;
            } else {
                $this->ajaxReturn(self::createReturn(false, ['errorCode' => 401, 'msg' => '请求未授权'], '请求未授权，请登录'));
            }
        } else {
            $this->ajaxReturn(self::createReturn(false, ['errorCode' => 401, 'msg' => '请求未授权'], '请求未授权，请登录'));
        }
    }
}