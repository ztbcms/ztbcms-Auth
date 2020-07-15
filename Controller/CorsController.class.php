<?php

/**
 * author: Jayin <tonjayin@gmail.com>
 */

namespace Auth\Controller;

use Common\Controller\Base;

class CorsController extends Base {

    protected function _initialize() {

        //http 预检响应
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: *');
            header('Access-Control-Allow-Headers: *');
            header('Access-Control-Max-Age: 86400'); // cache for 1 day

            exit();
        }

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Headers: *');

        parent::_initialize();
    }


}