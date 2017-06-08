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
在ext-xhprof文件中加入
[xhprof]
extension = "phpng_xhprof.so"
xhprof.output_dir=/tmp/xhprof

这样扩展就安装好了 可以通过php -m | grep  xhprof 查看
```
