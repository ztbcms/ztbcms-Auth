<?php
/**
 * Created by PhpStorm.
 * User: yezhilie
 * Date: 2018/6/1
 * Time: 15:26
 */

namespace Auth\Controller;


use Auth\Service\OpenPlatformService;

/**
 * 第三方接口数据校验
 *
 * Class CheckDataController
 * @package Auth\Controller
 */
class CheckDataController extends CorsController{

    protected function _initialize() {
        parent::_initialize();

        $data = $_REQUEST;
        $app_secret = OpenPlatformService::getAppSecretByAppId($data['app_id']);
        if($app_secret['status'] == false){
            $this->ajaxReturn($app_secret);
        }
        $sign = $data['sign'];
        unset($data['sign']);
        if(!$sign){
            $this->ajaxReturn(self::createReturn(false, null, '签名错误'));
        }
        $local_sign = OpenPlatformService::getSign($data, $app_secret['data'])['data'];
        if($local_sign != $sign){
            $this->ajaxReturn(self::createReturn(false, null, '签名错误'));
        }

    }

}