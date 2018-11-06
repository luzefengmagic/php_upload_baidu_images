<?php

/*
* 上传图片到百度图片 前端上传示例 Api处理文件
* @author QangMouRen<2962051004@qq.com>
* @github https://github.com/QiangMouRen/php_upload_baidu_images
* @version 1.0
* @static 变量、类、函数是静态的
*/
header('Access-Control-Allow-Origin:*'); // 开放接口

require("response.php");
require("../uploadBaiduImages.php");
/*
 * 图片上传限制(kb) 默认10M
 */
const upload_size_max = 10485760;


if (isset($_FILES["upload"])) { // 判断是否有提交文件
    
    if (!uploadBaiduImages::isImage($_FILES["upload"]["tmp_name"])) { // 判断是否上传的是图片
        unlink($_FILES["upload"]["tmp_name"]); // 删除临时文件
        response::error('请选择正确的图片');
    } else if ($_FILES["upload"]["size"] > upload_size_max) { // 如果上传的图片大小超过设置的大小
        unlink($_FILES["upload"]["tmp_name"]); //  删除临时文件
        response::error('图片大小超出限制');
    } else if ($_FILES["upload"]["error"] > 0) {
        unlink($_FILES["upload"]["tmp_name"]); // 删除临时文件
        response::error('上传失败');
    } else {
        $result = uploadBaiduImages::init($_FILES["upload"]["tmp_name"])::upload();
        if (is_array($result)) {
            response::succ($result);
            
        } else {
            response::error($result);
            
        }
    }
} else {
    response::error('请选择图片');
}

class response
{
    public static function init()
    {
        header('Content-type: application/json;charset=utf-8');
    }
    public static function error($massage)
    {
		self::init();
        echo self::enjson(array(
            "error" => "1",
            "massage" => $massage,
            "data" => ""
        ));
        exit;
    }
    public static function succ($data)
    {
		self::init();
        echo self::enjson(array(
            "error" => "0",
            "massage" => "",
            "data" => $data
        ));
        exit;
        
    }
    private static function enjson($arr)
    {
        return json_encode($arr);
        
    }
}
