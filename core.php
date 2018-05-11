<?php
/*
 * 核心类
 * Author Qiang Ge
 * Mail 2962051004@qq.com
 * Date 2018-5-11 10:00
 * Website https://api.yya.gs
*/
class BaiduImgUpload
{
    private $UPLOAD_SIZE_MAX = 10485760;
    // 图片上传限制 默认10M
    private $API_URL = 'https://image.baidu.com/wiseshitu/a_upload';
    // 接口地址，不要修改
    /**
     * json_encode 简单封装
     * 
     * @access private 
     * @param array $arr 数组
     * @return string JSON字符串
     *
    **/
    private function json_en($arr)
    {
        return json_encode($arr, 488);
    }
    /**
     * 甩出提示
     * 
     * @access public 
     * @param string $msg 提示内容
     *
    **/
    public function error($msg)
    {
        echo self::json_en(array('error' => $msg, 'code' => 0));
        exit;
    }
    /**
     * response
     * 
     * @access private 
     * @param string $url 图片地址
     *
    **/
    private function response($url)
    {
        echo self::json_en(array('error' => '', 'code' => 1, 'url' => $url));
        exit;
    }
    /**
     * 判断是否图片
     * 
     * @access private 
     * @param string $filename 图片路劲
     * @return Boolean 是否为图片
     *
    **/
    private function isImage($filename)
    {
        $types = '.gif|.jpeg|.png|.bmp';
        if (file_exists($filename)) {
            $info = @getimagesize($filename);
            $ext = image_type_to_extension($info['2']);
            return stripos($types, $ext);
        } else {
            return false;
        }
    }
    /**
     * 核心方法
     * 
     * @access public
     *
    **/
    public function main()
    {
        if (!self::isImage($_FILES["upload"]["tmp_name"])) {
            // 判断是否上传的是图片
            // 如果不是图片 ヽ(*´з｀*)ﾉ
            unlink($_FILES["upload"]["tmp_name"]);
            // 删除临时文件
            self::error('请选择正确的图片');
        }
        if ($_FILES["upload"]["size"] > $this->UPLOAD_SIZE_MAX) {
            // 如果上传的图片大小超过设置的大小
            unlink($_FILES["upload"]["tmp_name"]);
            // 一样删除临时文件
            self::error('图片大小超出限制');
        }
        if ($_FILES["upload"]["error"] > 0) {
            // 鬼知道发生了什么
            unlink($_FILES["upload"]["tmp_name"]);
            // 强迫症忍不住，接着删除临时文件 (๑•́ ₃ •̀๑)
            self::error('上传失败');
        }
        if(!function_exists('curl_init')) {
         self::error('大佬，你咋还没有Curl扩展呢');
        }
        $ch = curl_init(); // 初始化
        curl_setopt($ch, CURLOPT_URL, $this->API_URL); // API地址
        curl_setopt($ch, CURLOPT_POST, 1); // 发送POST请求
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取的信息以文件流的形式返回
        curl_setopt($ch, CURLOPT_TIMEOUT, 180); // 超时180秒
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 禁止SSL验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 禁止SSL验证
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
           'upload' => curl_file_create($_FILES["upload"]["tmp_name"]) // 创建一个 CURLFile 对象
        ));
        $output = curl_exec($ch); // 执行 cURL 会话
        curl_close($ch); // 关闭 cURL 会话
        $output = json_decode($output, true);
        // 解析JSON
        if ($output["errno"]) {
            // 假设百度抽风
            self::error('上传失败');
            // 那么
        } else {
            self::response($output['url']); 
// 示例输出
//            {
//            "error": "",
//            "code": 1,
//            "url": "http://e.hiphotos.baidu.com/image/%70%69%63/item/500fd9f9d72a605979e23ca02434349b023bba9e.jpg"
//            }
        }
    }
}