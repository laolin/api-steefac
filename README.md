# 0 搭设本地API服务器方法

## 0.1 MYSQL
参考服务器的MYSQL数据，目前有用的是11个

参考第 `1.2` 点

## 0.2 `Api-core` 和 `api-steefac`仓库
克隆回此两仓库

## 0.3 API地址

API地址为 `Api-core/src/index.php` 所在的路径

`api-steefac` 中的 api通过下面的配置文件调用

## 0.4 `index.config` 设置

根据 `Api-core/src/`目录 下的 `index.config.php` 最后一行
会自动搜索并加载一个特殊文件
`'../../api-bak/index.config__test__.php'`

故可以复制 `index.config.php` 到 `../api-bak/index.config__test__.php`，
然后修改此设置文件。

此法可避免设置内容上传到git仓库中。

一般需要修改3个配置，参考第 `1.3.3` 节。

### (1) 定义 MYSQL数据库
### (2) 定义 apis路径，可以把`steefac`的API指定到 `api-steefac`仓库的`/src/apis` 路径下
### (3) 定义 微信APP


# 1 说明

## 1.1 客户端API 地址设置

在 app/app-steefac.define.js 文件中定义

测试时apiRoot可临时修改为本地的API地址
apiWxAuth不可修改。仅用于微信登录。

```
  apiRoot: 'https://api.qinggaoshou.com/api-eb', //一般的API
  apiWxAuth: 'https://qinggaoshou.com/api-eb', //WX 授权 callback 域名限制的URI
```


## 1.2 数据库

见数据库 `api_tbl_` 开头的 api_tbl_xxx 表

数据表说明：

api-core中用的：
 *  api_tbl_log 访问记录
 *  api_tbl_tokenbucket 令牌桶，用于控制访问量
 *  api_tbl_user　用户基本字段
 *  api_tbl_token 用户登录信息
 *  api_tbl_wxuser 微信信息
 *  api_tbl_uploads 上传文件记录

 * api_tbl_feed    feed数据
 * api_tbl_comment feed的评论

api-steefac中用的：
 * api_tbl_steelfactory 钢构厂
 * api_tbl_steelproject 项目
 * api_tbl_stee_user    用户资料

 
## 1.3 API 的 PHP 文件

运行API，需要以下文件：

### 1.3.1 index.php 文件

参考Api-core/src/目录

### 1.3.2 .htaccess 文件

参考Api-core/src/目录

### 1.3.3 index.config.php
参考Api-core/src/目录

####  (1) 定义数据库

数据用户

api_g("DBNAME",'test_dbname');
api_g("DBSERVER",'localhost');
api_g("DBPORT",3306);
api_g("DBUSER",'username');
api_g("DBPASS",'password');

数据表前缀：

api_g("api-table-prefix",'api_tbl_');

#### (2)  重定义 apis 路径

每个path可以指定一批apis到某一个指定的目录下


```
//开头要有'/'，结束不能有'/'，从 index.php 所在路径相对计算
api_g("path-apis",[
    [
      'apis'=>['stee_user','steeobj','steesys'],
      'path'=>'/../../api-steefac/src/apis'
    ]
  ]);
```


#### (3) 微信APPID 和 APPSEC 定义

'qgs-mp' 是客户端服务器端约定的字符串，公众号。
'qgs-web' 是客户端服务器端约定的字符串，对应的网页APP。
'main' 表示主公众号的appid,用来识别是否关注公众号，一般同'qgs-mp'。


```
api_g("WX_APPS", [
  //main 表示主 公众号的appid,用来识别是否关注公众号等
  'main'=>
    ['公众号 主号的appid','app-secret'],
  'qgs-web'=>
    ['wx-app-id 1','wx-app-secret xx1'],
  'qgs-mp'=>
    ['wx-app-id 2','xx2']
]);
```

- 1.3.4 Api-core 仓库

系统用户，微信相关的，feed相关的一些API

- 1.3.5 api-steefac 仓库

steefac 的API


