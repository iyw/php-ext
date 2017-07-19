# lnmp环境安装

##php安装

安装过程中，需要安装的一些依赖
```
yum install -y  openssl openssl-devel  bzip2 bzip2-devel libcurl libcurl-devel jpeglib  libjpeg-devel freetype-devel openldap-devel libmcrypt libmcrypt-devel sqlite-devel libtidy libtidy-devel  libxslt libxslt-devel
```

```shell
./configure --prefix=/usr/local/lnmp/php --exec-prefix=/usr/local/lnmp/php --bindir=/usr/local/lnmp/php/bin --sbindir=/usr/local/lnmp/php/sbin --sysconfdir=/usr/local/lnmp/php/etc --datadir=/usr/local/lnmp/php/share --includedir=/usr/local/lnmp/php/include --libexecdir=/usr/local/lnmp/php/libexec --localstatedir=/usr/local/lnmp/php/var --sharedstatedir=/usr/local/lnmp/php/share/com --mandir=/usr/local/lnmp/php/share/man --infodir=/usr/local/lnmp/php/share/info --with-pear=/usr/local/lnmp/php/lib/php --with-libdir=lib64 --with-mysql=mysqlnd --with-mysqli=mysqlnd --with-curl=shared --with-ldap=shared --with-xmlrpc=shared --with-zlib=shared --with-bz2=shared --with-iconv=shared --with-mhash=shared --with-mcrypt=shared,/usr/local/lnmp/php --with-gd=shared --with-png-dir=/usr --with-jpeg-dir=/usr --with-freetype-dir=/usr --enable-gd-native-ttf --disable-debug --disable-rpath --disable-ipv6 --enable-simplexml --enable-libxml --enable-filter --enable-ftp --enable-xmlreader=shared --enable-xmlwriter=shared --enable-hash --enable-mysqlnd --enable-phar=shared --enable-json=shared --enable-dom=shared --enable-zip --enable-dba=shared --with-gdbm=/usr --with-dbm=/usr --with-db4=/usr --enable-xml --with-openssl --with-xsl=shared,/usr --enable-mbstring=shared,all --enable-ftp=shared --enable-bcmath=shared --enable-shmop=shared --enable-sysvsem=shared --enable-sysvshm=shared --enable-sysvmsg=shared --enable-sockets=shared --enable-exif=shared --enable-soap=shared --enable-wddx=shared --enable-pdo=shared --with-pdo-sqlite=shared,/usr --with-pdo-mysql=shared,mysqlnd --with-tidy=shared,/usr --with-pic --enable-inline-optimization --with-gettext=shared --with-config-file-scan-dir=/usr/local/lnmp/php/lib/php.d/ --enable-opcache=yes --enable-fpm --enable-cli --enable-pcntl

make && make install

```



##mysql安装

```
依赖安装：
yum install -y make  cmake bison  gcc gcc-c++

建立目录
mkdir -p /usr/local/lnmp/mysql
mkdir -p /usr/local/lnmp/mysql/data

建立用户和用户组
groupadd mysql
useradd -r -g mysql -s /bin/false  mysql

编译安装

cmake . -DCMAKE_INSTALL_PREFIX=/usr/local/lnmp/mysql -DMYSQL_DATADIR=/usr/local/lnmp/mysql/data -DSYSCONFDIR=/usr/local/lnmp/etc -DWITH_MYISAM_STORAGE_ENGINE=1  -DWITH_INNOBASE_STORAGE_ENGINE=1  -DWITH_MEMORY_STORAGE_ENGINE=1 -DWITH_READLINE=1  -DMYSQL_UNIX_ADDR=/usr/local/lnmp/var/lib/mysql/mysql.sock  -DMYSQL_TCP_PORT=6000 -DENABLED_LOCAL_INFILE=1  -DWITH_PARTITION_STORAGE_ENGINE=1  -DEXTRA_CHARSETS=all  -DDEFAULT_CHARSET=utf8  -DDEFAULT_COLLATION=utf8_general_ci

make && make install


修改目录所属人组
chown -R mysql:mysql /usr/local/lnmp/mysql/

```

mysql配置文件/etc/my.cnf

```
[client]
port=6000
socket=/usr/local/lnmp/mysql/mysql.sock
[mysqld]
character-set-server=utf8
collation-server=utf8_general_ci

skip-external-locking
skip-name-resolve

user=mysql
basedir=/usr/local/lnmp/mysql
datadir=/usr/local/lnmp/mysql/data
tmpdir=/usr/local/lnmp/mysql/temp
# server_id = .....
socket=/usr/local/lnmp/mysql/mysql.sock
log-error=/usr/local/lnmp/mysql/logs/mysql_error.log
pid-file=/usr/local/lnmp/mysql/mysql.pid
open_files_limit=10240
back_log=600
max_connections=500
max_connect_errors=6000
wait_timeout=605800
#open_tables=600
#table_cache = 650
#opened_tables = 630

max_allowed_packet=32M
sort_buffer_size=4M
join_buffer_size=4M
thread_cache_size=300
query_cache_type=1
query_cache_size=256M
query_cache_limit=2M
query_cache_min_res_unit=16k

tmp_table_size=256M
max_heap_table_size=256M

key_buffer_size=256M
read_buffer_size=1M
read_rnd_buffer_size=16M
bulk_insert_buffer_size=64M

lower_case_table_names=1

default-storage-engine=INNODB

innodb_buffer_pool_size=2G
innodb_log_buffer_size=32M
innodb_log_file_size=128M
innodb_flush_method=O_DIRECT
#####################
thread_concurrency=32
long_query_time=2
slow-query-log=on
slow-query-log-file=/usr/local/lnmp/var/log/mysql-slow.log

[mysqldump]
quick
max_allowed_packet=32M

[mysqld_safe]
log-error=/usr/local/lnmp/var/log/mysqld.log
pid-file=/usr/local/lnmp/var/run/mysqld/mysqld.pid
```

配置mysql服务
```
cd /usr/local/lnmp/mysql
cp support-files/mysql.server /etc/init.d/mysqld
chkconfig --add mysqld     # 添加到系统服务
chkconfig mysqld on        # 开机启动
```

启动服务
```
service mysqld start       # 启动mysql服务
service mysqld stop        # 停止mysql服务
service mysqld restart     # 重新启动mysql服务
```
