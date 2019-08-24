<?php

/**
 * author: Jayin <tonjayin@gmail.com>
 */

namespace Auth\Controller;

use Auth\Service\OpenPlatformService;
use Common\Controller\AdminBase;

/**
 * 第三方平台
 */
class OpenPlatformController extends AdminBase {

    /**
     * 应用列表
     */
    public function index(){
        $this->display();
    }

    /**
     * 创建应用
     */
    public function add(){
        if(IS_POST){
            $app_name = I('post.app_name');
            if(!$app_name){
                $this->error('请填写应用名称');
            }
            OpenPlatformService::addOpenPlatform($app_name);
            $this->success("创建成功");
            exit;
        }
        $this->display();
    }

    /**
     * 获取应用列表
     */
    public function getOpenPlatformList(){
        $res = OpenPlatformService::getOpenPlatformList();
        $this->ajaxReturn($res);
    }

    /**
     * 重置密钥
     */
    public function resetAppSecret(){
        $id = I('post.id');
        $res = OpenPlatformService::resetAppSecret($id);
        $this->ajaxReturn($res);
    }

    /**
     * 更新
     */
    public function updateOpenAuth(){
        $id = I('post.id');
        $value = I('post.value');
        $res = OpenPlatformService::updateOpenAuth($id, $value);
        $this->ajaxReturn($res);
    }

}