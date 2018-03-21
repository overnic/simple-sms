# 短信集成包

## 安装

```shell
composer require overnic/dm-package
```

## 配置

> Laravel 应用

1. 复制文件`src/config/sms.php` 到程序`config`目录下

2. 在 `config/app.php` 注册 ServiceProvider
```php
'providers' => [
    // ...
    OverNick\Dm\DmServiceProvider::class,
],
```

3. 程序中的使用
```php
<?php

namespace App\Http\Controllers;

use OverNick\Dm\Config\DmConfig;

class SmsController extends Controller
{

    /**
     * 发送短信
     *
     * @return string
     */
    public function index()
    {
        // 初始化配置文件
        $config = new DmConfig();
        
        // 设置模版参数
        $config->setParams([
            "code" => "123456",
            "product" => "001"
            ]);
        
        // 设置模版id
        $config->setTpl('001');
        // 设置收信手机号
        $config->setTo('13100000001');
        // 使用签名
        $config->setSign('阿里云签名');
        
        // 默认使用阿里云短信
        app('sms')->send($config);
        
        // 设置模版
        $config->setTpl('001');
         // 设置收信手机号
         $config->setTo('13100000001');
        // 设置模版参数
        $config->setParams([1,2,3]);
        // 设置签名
        $this->setSign('腾讯云签名');
        
        // 使用腾讯云短信
        app('sms')->dirver('tencent')->send($config);
    }
}

```

4. 修改默认服务商,修改`config/sms.php`,将`default` 值修改为tencent
```php
return [
    /**
     * 默认使用的短信服务商
     */
    'default' => 'tencent',   
    /**
     * 配置信息
     */
    'drivers' => [
        // 腾讯云配置
        'tencent' => [
            'app_id' => '控制台中的app id',
            'app_key' => '控制台中的app key'
        ],
        // 阿里云配置
        'aliyun' => [
            'access_key_id' => '控制台中的AccessKeyId',
            'access_secret' => '控制台中的AccessSecret'
        ]
    ]
];
```
5. 扩展...


> 不嵌套框架使用
```php
<?php
/**
 * Created by PhpStorm.
 * User: overnic
 * Date: 2018/1/3
 * Time: 19:20
 */

$path =  __DIR__.DIRECTORY_SEPARATOR;

// composer 自动加载，路径自行修改
require_once $path.'/../vendor/autoload.php';

// 引用配置文件
$config = require_once $path.'/../config/sms.php';
  
// 腾讯云短信
// 实例化
$ten_sms = new \OverNick\Dm\Client\TencentDmClient(array_get($config,'drivers.tencent'));
$param = new \OverNick\Dm\Config\DmConfig();
$param->setTo('13100000001');
$param->setParams(['123456', '产品名']);   // 设置参数
$param->setSign('签名');              // 签名
$param->setTpl('001');             // 模版id
  
// 群发
$ten_sms->send($param);
$param = new \OverNick\Dm\Config\DmConfig();
$param->setTo(['13100000001', '13100000002']);
$param->setParams(['123456', '产品名']);   // 设置参数
$param->setSign('签名');              // 签名
$param->setTpl('001');             // 模版id
// 发送短信
$ten_sms->send($param);
  
  
// 阿里云短信
// 实例化
$sms = new \OverNick\Dm\Client\AliyunDmClient(array_get($config,'drivers.aliyun'));
$param->setTo('13100000001');         // 设置短信
$param->setSign('签名');              // 签名
// 设置参数
$param->setParams([
    "code" => "123456",
    "product" => "001"
]);
// 发送短信
$result = $sms->send($param);
 
// 群发
$param->setTo(['13100000001', '13100000002']);         // 设置短信
$param->setSign(['签名', '签名2']);              // 签名
// 设置参数
$param->setParams([
    ["code" => "123456","product" => "001"],
    ["code" => "123456","product" => "001"],
]);
  
// 发送短信
$result = $sms->send($param);
```