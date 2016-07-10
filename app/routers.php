<?php

use \Qp\Kernel\Router;

/**
 * 注意：【Router::modules】方法只能最多全局调用一次，如果调用该方法的话，必须放置在任何其他方法之前
 */
Router::modules([

]);

Router::set([
    "controllers"   => [
        "index" => "Index",
    ]
]);
