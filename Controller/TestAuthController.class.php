<?php

/**
 * author: Jayin <tonjayin@gmail.com>
 */

namespace Auth\Controller;

/**
 * 测试已授权
 */
class TestAuthController extends AuthorizedController {

    /**
     * 已授权的可以看到下面文字
     */
    function test(){
        echo 'I have authorized!';
    }

}