<?php

/**
 * author: Jayin <tonjayin@gmail.com>
 */

namespace Auth\Controller;

use Auth\Service\AuthService;
use Auth\Service\SignService;

/**
 * APP登录
 */
class AppLoginController extends CorsController {

    /**
     * 授权跳转
     */
    public function loginRedirect(){
        $config = require (APP_PATH . 'Auth/Conf/config.php');

        $user_id = I('get.uid');
        $timestamp = I('get.timestamp');
        $platform = I('get.platform');
        $redirect = $_GET['redirect'];
        $redirect = urlencode($redirect);
        $sign = I('get.sign');

        $error_code = SignService::checkSign($user_id ,$timestamp ,$platform ,$redirect ,$sign);

        if($error_code == 0){
            $res = AuthService::makeLoginCode($platform, $user_id);
            $login_code = $res['data'];
        }else{
            $login_code = '';
        }

        $redirect_url = $config['LOGIN_URL'];
        $param = "uid={$user_id}&timestamp={$timestamp}&platform={$platform}&redirect={$redirect}&login_code={$login_code}&error_code={$error_code}";
        if (strpos($redirect_url, '?') === false) {
            $redirect_url .= '?' . $param;
        } else {
            $redirect_url .= '&' . $param;
        }
        redirect($redirect_url);
    }

    /**
     * 登录
     */
    public function loginByLoginCode(){
        $login_code = I('post.login_code');
        if(!$login_code){
            $this->ajaxReturn(self::createReturn(false, null, '参数错误'));
        }
        $data = D('Auth/AccessToken')->where(['login_code' => $login_code])->find();
        if($data){
            //login_code验证一次立即无效
            D('Auth/AccessToken')->where(['id' => $data['id']])->save(['login_code' => '']);
            if($data['expired_time'] < time()){
                $this->ajaxReturn(self::createReturn(false, null, '登录凭证已过期'));
            }
            $this->ajaxReturn(self::createReturn(true, [
                'userid' => $data['userid'],
                'username' => $data['username'],
                'nickname' => $data['nickname'],
                'access_token' => $data['access_token']
            ], '登录成功'));
        }
        $this->ajaxReturn(self::createReturn(false, null, '参数错误'));
    }

    /**
     * app登录
     */
    public function appLogin(){
        $username = I('post.username');
        $platform = I('post.platform');
        if($platform != AuthService::PLATFORM_IOS && $platform != AuthService::PLATFORM_ANDROID){
            $this->ajaxReturn(self::createReturn(false, null, '不支持的平台'));
        }
        $res = AuthService::auth($username, '', $platform, true);
        if($res['status']){
            $res['data']['platform'] = $platform;
        }
        $this->ajaxReturn($res);
    }
}