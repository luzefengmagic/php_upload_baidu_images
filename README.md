## 上传图片到百度图片并获取直链

### 说明

将本地图片上传到百度图片（images.baidu.com），并获取直链

环境：php>7.0

扩展：curl 

### 方法

#### uploadBaiduImages::init

```php
/**
     * 初始化图片路劲
     * @static 该函数是静态的
     * @access public
     * @return object self
     *
**/
uploadBaiduImages::init($path);
```

#### uploadBaiduImages:upload

```php
/**
     * 上传图片
     * @static 该函数是静态的
     * @access public
     * @param void
     * @return string array 
     *
**/
uploadBaiduImages::upload();
// 必须先初始化图片路径 并使用链式调用 uploadBaiduImages::init($path)::upload();

```

#### uploadBaiduImages:isImage 

```php
/**
     * 判断文件是否正确的图片
     * @static 该函数是静态的
     * @access public 
     * @param string $filename 图片路劲
     * @return boolean
     *
    **/
uploadBaiduImages:isImage($filename);
// 非核心方法
```

### 前端上传图片示例

示例代码在test文件夹

- index.html 前端页面
- api.php 处理文件（返回json）

### bug

如有任何问题，欢迎提交issues
