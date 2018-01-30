<?php

/**
 * author: Jayin <tonjayin@gmail.com>
 */

namespace Auth\Controller;

use Auth\Service\AuthService;

/**
 * 授权判断
 *
 * 若以请求需要用户登录授权后才能操作，请继承这个类
 */
class AuthorizedController extends CorsController {

    /**
     * 用户信息
     * @var null
     */
    public $userInfo = null;
    /**
     * 用户登录凭证
     * @var string
     */
    public $accessToken = '';

    protected function _initialize() {
        parent::_initialize();

        $access_token = $_SERVER[strtoupper('HTTP_' . AuthService::HTTP_HEADER_ACCESS_TOKEN)];

        if ($access_token) {
            $res = AuthService::checkAccessToken($access_token, AuthService::PLATFORM_WEB);
            if ($res['status']) {
                $access_token_record = $res['data'];
                $this->userInfo = [
                    'userid' => $access_token_record['userid'],
                    'nickname' => $access_token_record['nickname'],
                    'username' => $access_token_record['username'],
                ];
                $this->accessToken = $access_token_record['access_token'];
            } else {
                $this->ajaxReturn(self::createReturn(false, null, '请求未授权', 401));
            }
        } else {
            $this->ajaxReturn(self::createReturn(false, null, '请求未授权', 401));
        }
    }


}