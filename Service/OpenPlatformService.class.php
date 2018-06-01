<?php

/**
 * author: Jayin <tonjayin@gmail.com>
 */

namespace Auth\Service;

use System\Service\BaseService;

class OpenPlatformService extends BaseService {

    /**
     * 获取 app_secret
     *
     * @param $app_id
     * @return array
     */
    static function getAppSecretByAppId($app_id){
        if(!$app_id){
            return self::createReturn(false, null, '找不到应用');
        }
        $app_secret = M('AuthOpenPlatform')->where(['app_id' => $app_id])->getField('app_secret');
        if($app_secret){
            return self::createReturn(true, $app_secret, '获取成功');
        }
        return self::createReturn(false, null, '找不到应用');
    }

    /**
     * 获取签名
     *
     * @param $data
     * @param $app_secret
     * @return array
     */
    static function getSign($data, $app_secret){
        ksort($data);
        $str = '';
        foreach($data as $k => $v){
            $str .= $k .'='. $v .'&';
        }
        $str .= 'app_secret=' . $app_secret;
        $sign = md5($str);
        return self::createReturn(true, $sign);
    }

    /**
     * 创建应用
     *
     * @param $app_name
     * @return array
     */
    static function addOpenPlatform($app_name){
        $data = [
            'app_id' => self::createAppId(),
            'app_name' => $app_name,
            'app_secret' => self::createAppSecret(),
            'add_time' => time(),
            'is_allow_auth' => 1
        ];
        M('AuthOpenPlatform')->add($data);
        return self::createReturn(true, $data, '创建应用成功');
    }

    /**
     * 获取列表
     *
     * @return array
     */
    static function getOpenPlatformList(){
        $list = M('AuthOpenPlatform')->field('id,app_id,app_name,is_allow_auth')->select();
        return self::createReturn(true, $list ?: []);
    }

    /**
     * 重置 Secret
     *
     * @param $id
     * @return array
     */
    static function resetAppSecret($id){
        if($id){
            $app_secret = self::createAppSecret();
            M('AuthOpenPlatform')->where(['id' => $id])->save(['app_secret' => $app_secret]);
            return self::createReturn(true, $app_secret, '重置成功');
        }
        return self::createReturn(false, null, '参数错误');
    }

    /**
     * @param $id
     * @param $value
     * @return array
     */
    static function updateOpenAuth($id, $value){
        $res = M('AuthOpenPlatform')->where(['id' => $id])->save(['is_allow_auth' => $value]);
        return self::createReturn(true, $res, '更新成功');
    }

    /**
     * 创建 app_id
     *
     * @return string
     */
    private static function createAppId(){
        do{
            $app_id = 'ZTB'.rand(100000000,999999999);
        }while(M('AuthOpenPlatform')->where(['app_id' => $app_id])->count());
        return $app_id;
    }

    /**
     * 创建 app_secret
     *
     * @param $len
     * @return string
     */
    private static function createAppSecret($len = 32){
        return self::genRandomString($len);
    }

    /**
     * 产生一个指定长度的随机字符串
     *
     * @param int $len 产生字符串的长度
     * @return string 随机字符串
     */
    private static function genRandomString($len = 32) {
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9",
        );
        $charsLen = count($chars) - 1;
        // 将数组打乱
        shuffle($chars);
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }

        return $output;
    }
}