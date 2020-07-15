<?php
/**
 * Created by PhpStorm.
 * User: yezhilie
 * Date: 2018/5/28
 * Time: 13:35
 */

namespace Auth\Service;

use System\Service\BaseService;

class SignService extends BaseService{

    /**
     * 获取签名
     *
     * @param $data
     * @param $access_token
     * @return array
     */
    static function getSign($data, $access_token){
        ksort($data);
        $str = '';
        foreach($data as $k => $v){
            $str .= $k .'='. $v .'&';
        }
        $str .= 'access_token=' . $access_token;
        $sign = md5($str);
        return self::createReturn(true, $sign);
    }

    /**
     * 签名校验
     *
     * @param $user_id
     * @param $timestamp
     * @param $platform
     * @param $redirect
     * @param $sign
     * @return int
     */
    static function checkSign($user_id ,$timestamp ,$platform ,$redirect ,$sign){
        if(!$user_id || !$timestamp || !$platform || !$redirect || !$sign){
            //参数错误
            return 1;
        }
        //5分钟有效
        if ($timestamp + 5 * 60 < time()) {
            //授权超时
            return 2;
        }
        $access_token = D('Auth/AccessToken')->where(['platform' => $platform, 'userid' => $user_id])->getField('access_token');
        if(!$access_token){
            //授权失败
            return 3;
        }
        $data = [
            'uid' => $user_id,
            'timestamp' => $timestamp,
            'platform' => $platform,
            'redirect' => $redirect,
        ];
        $res = SignService::getSign($data, $access_token);
        if($sign != $res['data']){
            //签名错误
            return 4;
        }
        return 0;
    }
}