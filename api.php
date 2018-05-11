<?php
/*
 * Api文件
 * Author Qiang Ge
 * Mail 2962051004@qq.com
 * Date 2018-5-11 10:00
 * Website https://api.yya.gs
*/
header('Content-type: application/json;charset=utf-8'); // 默认输出JSON
header('Access-Control-Allow-Origin:*'); // 设置跨域头

try {
    require_once 'core.php';
    $class = new BaiduImgUpload(); // 实例化
    if (isset($_FILES["upload"])) { // 判断是否有提交文件
        $class->main();
    } else { 
        $class->error('请选择图片');
    }
} catch (Exception $e) {
    // 谁在乎？
}


// 由于百度有防盗链。而且不支持https
// 所以这个并不能为你的服务器分担任何压力。。。。
// 简单说这是我写着玩的。对我来说无实际用途。。。看你想咋用了 Σ(|||▽||| )