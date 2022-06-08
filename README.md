##响应处理

Response组件用于处理http数据响应

###安装组件

使用 composer命令进行安装或下载源代码使用(依赖config,debug组件)。

    composer require willphp/response

>WillPHP框架已经内置此组件，无需再安装。

###使用示例

    \willphp\response\Response::make('content')->output(); //设置响应内容并输出

###设置内容

可设置的内容类型:字符串,数组,URL地址

    echo Response::make('content'); //显示content 
    echo Response::make(['name'=>'willphp']); //显示json
    echo Response::make('http://www.113344.com'); //跳转url 	 

###输出内容

    Response::output($content); //用output输出响应内容
    echo Response::make($content); //或直接echo输出Response对象

###获取json

	echo Response::json(['name'=>'willphp']); 
