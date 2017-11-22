<?php

/**
 * author: Jayin <tonjayin@gmail.com>
 */

namespace Auth\Service;

use System\Service\BaseService;

class AuthService extends BaseService {

    //授权客户端，一个客户端一个token
    const PLATFORM_ANDROID = 'Android';
    const PLATFORM_PC_Web = 'PC_Web';

    /**
     * 请求头
     */
    const HTTP_HEADER_ACCESS_TOKEN = 'Ztbtoken';

    /**
     * 用户授权认证
     *
     * TODO 控制用户授权频率(防止暴力破解?)
     *
     * @param        $username
     * @param string $password
     * @param string $platform 授权平台
     * @param bool   $ignorePassword
     * @return array
     */
    static function auth($username, $password, $platform, $ignorePassword = false) {
        if ($ignorePassword) {
            $user = service("Passport")->getLocalUser($username, null);
        } else {
            $user = service("Passport")->getLocalUser($username, $password);
        }

        if ($user) {
            //先检查时候有无access_token
            $AuthAccessTokenDb = D('Auth/AccessToken');

            $access_token = $AuthAccessTokenDb->where(['username' => $username, 'platform' => $platform])->find();

            if (empty($access_token)) {
                //没有，则生成新的access_token
                $data = [
                    'userid' => $user['userid'],
                    'username' => $user['username'],
                    'nickname' => $user['nickname'],
                    'expired_time' => self::getExpiredTime(),
                    'create_time' => time(),
                    'platform' => self::getPlatform(),
                    'access_token' => self::makeAccessToken()
                ];

                $res = $AuthAccessTokenDb->add($data);

            } else {
                //有，则更新token, 更新有效时间
                $data = [
                    'access_token' => self::makeAccessToken(),
                    'expired_time' => self::getExpiredTime(),
                    'nickname' => $user['nickname']
                ];

                $res = $AuthAccessTokenDb->where(['id' => $access_token['id']])->save($data);
            }

            if ($res) {
                $ret = [
                    'userid' => $user['userid'],
                    'username' => $user['username'],
                    'nickname' => $user['nickname'],
                    'access_token' => $data['access_token']
                ];

                return self::createReturn(true, $ret, '授权成功');
            } else {
                return self::createReturn(false, null, '授权失败');
            }
        } else {
            return self::createReturn(false, null, '授权失败,请检查账号密码');
        }
    }


    /**
     * 生成 64位 access_token
     *
     * @return string
     */
    static function makeAccessToken() {
        return self::genRandomString(64);
    }

    /**
     * 检查授权凭证
     *
     * @param $access_token
     * @param $platform
     * @return array
     */
    static function checkAccessToken($access_token, $platform) {
        $record = self::findAccessToken($access_token, $platform);
        if (!$record) {
            return self::createReturn(false, null, '暂无访问凭证');
        }

        //超时
        if ($record['expired_time'] < time()) {
            return self::createReturn(false, null, '访问凭证过期,请重新授权');
        }

        return self::createReturn(true, $record, '访问凭证正常');

    }

    /**
     * 查找访问凭证
     *
     * @param string $access_token 访问凭证
     * @param string $platform     授权平台
     * @return mixed
     */
    private static function findAccessToken($access_token, $platform) {
        return D('Auth/AccessToken')->where(['access_token' => $access_token, 'platform' => $platform])->find();
    }


    /**
     * 清除过期的access_token
     */
    static function cleanExpiredAccessToken() {
        //TODO
    }

    /**
     * 获取过期时间
     *
     * 默认过期时间为30日
     *
     * @param int $now
     * @return int
     */
    private static function getExpiredTime($now = 0) {
        if (empty($now)) {
            $now = time();
        }

        return $now + 30 * 24 * 60 * 60;
    }

    private static function getPlatform() {
        return self::PLATFORM_ANDROID;
    }

    /**
     * 产生一个指定长度的随机字符串,并返回给用户
     *
     * @param int $len 产生字符串的长度
     * @return string 随机字符串
     */
    private function genRandomString($len = 32) {
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