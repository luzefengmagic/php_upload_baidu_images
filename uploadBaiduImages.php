<?php

/*
* 上传图片到百度图片 封装类
* @author QangMouRen<2962051004@qq.com>
* @github https://github.com/QiangMouRen/php_upload_baidu_images
* @version 1.0
* @static 变量、类、函数是静态的
*/
class uploadBaiduImages
{

	/*
	 * 接口地址
	*/
    private static $api = 'https://image.baidu.com/wiseshitu/a_upload';
	
	/*
	 * 破解百度图片防盗链接口
	*/
	private static $bypass_burglar_chain_api = 'https://image.baidu.com/search/down?tn=download&url=';
	/*
	 * 本地图片地址
	*/
	private static $imagePath;
    
    /**
     * 判断是否图片
     * @static 该函数是静态的
     * @access public 
     * @param string $filename 图片路劲
     * @return boolean
     *
    **/
    public static function isImage($filename)
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
     * 初始化图片路径
     * @static 该函数是静态的
     * @access public
	 * @param string $path 本地图片路径
	 * @return object self
    **/
	public static function init($path) {
		
		self::$imagePath = $path;
		
		return self::class;
	}
    /**
     * 上传图片
     * @static 该函数是静态的
     * @access public
	 * @param void
	 * @return string array 
     *
    **/
    public static function upload()
    {
		
        if(!isset(self::$imagePath)){
			return '未初始化图片路径 请使用链式调用 uploadBaiduImages::init($path)::upload()';
			
		}
		if(is_file($self::$imagePath)){
			return '图片路径不存在';
			
		}
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::$api);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 180);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
           'upload' => curl_file_create(self::$imagePath)
        ));
        $output = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($output, true);
        if ($output["errno"]) {
            return '上传失败';
        } else {
            return array("url" => self::$bypass_burglar_chain_api.$output['url']);
        }
    }
}