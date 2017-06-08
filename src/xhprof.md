#性能分析工具xhprof
php7以前的版本不用多说，网上的资料很多，这里介绍PHP7+ 版本的xhprof安装,希望对大家有所帮助

#### 1. 安装扩展(此处不详细介绍安装扩展，只贴出命令)
```shell
git clone https://github.com/yaoguais/phpng-xhprof.git
phpize
./configure
cp modules/phpng_xhprof.so  $(php-config --extension-dir)

添加扩展ini文件，可以通过php --ini 查看配置文件
touch /usr/local/etc/php/7.1/conf.d/ext-xhprof.ini
在ext-xhprof.ini文件中加入
[xhprof]
extension = "phpng_xhprof.so"
xhprof.output_dir=/tmp/xhprof

这样扩展就安装好了 可以通过php -m | grep  xhprof 查看
```

#### 2. 测试文件

```php
<?php
xhprof_enable(XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);
function splitElem(&$a, $low, $high)
{
	$split_elem = $a[$low];

	for(;;) {
		while ($low < $high && $a[$high] >= $split_elem)
			$high--;
		if ($low >= $high) break;
		$a[$low++] = $a[$high];

		while ($low < $high && $a[$low] <= $split_elem)
			$low++;
		if ($low >= $high) break;
		$a[$high--] = $a[$low];
	}

	$a[$low] = $split_elem;

	return $high;
}

function quickSort(&$a, $low, $high)
{
	if ($low >= $high || empty($a)) return;

	$split_elem_index = splitElem($a, $low, $high);
	quickSort($a, $low, $split_elem_index - 1);
	quickSort($a, $split_elem_index+1, $high);
}

$a = array();

for ($i = 0; $i < 30; $i++) {
	$a[] = rand(125, 250);
}

quickSort($a, 0, count($a) - 1);
$xhprofData = xhprof_disable();// $xhprofData是数组形式的分析结果
require_once "./lib/xhprof_lib/utils/xhprof_lib.php";
require_once "./lib/xhprof_lib/utils/xhprof_runs.php";
$xhprofRuns = new XHProfRuns_Default();
$runId = $xhprofRuns->save_run($xhprofData, 'test');

echo 'http://xhprof.org/index.php?run=' . $runId . '&source=test';
```
#### 这样就好了吗？ 当然没那么简单，上面需要xhprof_lib.php和xhprof_runs.php 这两个文件， 还需要配置一个web服务 xhprof.org （当然你也可以叫其它名，这个心情了）

```shell
去clone一个php5.6使用的版本就好了

git clone https://github.com/phacility/xhprof.git

复制里面两个文件夹 xhprof_html  和xhprof_lib 到./lib 文件夹，当然也可以不复制,能require到就行

将xhprof_html目录配置root目录，下面贴一下nginx配置仅供参考
server {
    charset utf-8;
    client_max_body_size 128M;

    listen 80; ## listen for ipv4
    #listen [::]:80 default_server ipv6only=on; ## listen for ipv6

    server_name xhprof.org;
    root        /Users/yarw/project/php/lib/xhprof_html;
    index       index.php;

    access_log  /Users/yarw/project/php/yii/log/access.log;
    error_log   /Users/yarw/project/php/yii/log/error.log;

    location / {
        # Redirect everything that isn't a real file to index.php
        try_files $uri $uri/ /index.php$is_args$args;
    }

    # uncomment to avoid processing of calls to non-existing static files by Yii
    #location ~ \.(js|css|png|jpg|gif|swf|ico|pdf|mov|fla|zip|rar)$ {
    #    try_files $uri =404;
    #}
    #error_page 404 /404.html;

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_pass 127.0.0.1:9000;
        #fastcgi_pass unix:/var/run/php5-fpm.sock;
        try_files $uri =404;
    }

    location ~* /\. {
        deny all;
    }
}
```

祝安装顺利~~
