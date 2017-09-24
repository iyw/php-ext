### 1 mariadb 10.1 多主一从 搭建



#### 1.添加yum源  

  ```shell   sudo  vim /etc/yum.repos.d/MariaDB.repo 

     [tanyawen@h17-vm8 ~]$ vim /etc/yum.repos.d/MariaDB.repo 
      #MariaDB 10.1 CentOS repository list - created 2016-05-18 02:33 UTC
      # http://mariadb.org/mariadb/repositories/
      [mariadb]
      name = MariaDB
      baseurl = http://yum.mariadb.org/10.1/centos6-amd64
      gpgkey=https://yum.mariadb.org/RPM-GPG-KEY-MariaDB
      gpgcheck=1
  ```



####  2.安装

```shell
     sudo yum -y install MariaDB-server MariaDB-client 
     说明：安装前如果之前安装mysql最后先卸载掉Mysql再安装（ rpm -qa |grep mysql）
```



#### 3、更改配置

```shell
    配置文件路径： sudo vim /etc/my.cnf.d/server.cnf
    
    master1配置如下(10.0.11.98)：
    [mysqld]
    # * Galera-related settings
     server-id =1
     log_bin =/var/log/mysql/mysql-bin
     binlog_format = row
     log-slave-updates
     sync_binlog =1
     
    slave1配置如下(10.0.11.98的从库 master2从库配置方式一样)：
    [mysqld]
    # * Galera-related settings
      server-id =2
      log-bin=mysql-bin
      log-slave-updates
      relay-log=relay-log-bin
    
    master2配置如下(10.0.11.99)（注意server-id 不一样）：
    [mysqld]
    
    # * Galera-related settings
     server-id =12
     log-bin=/var/log/mysql/mysql-bin
     binlog_format = row
     log-slave-updates
     sync_binlog =1
    
     汇总库(slave)配置(10.0.11.89)
      server-id =13
      log-bin=mysql-bin
      log-slave-updates
      relay-log=relay-log-bin
    
    过滤掉不需要汇总的库（yc_order_global）和表(driver_order) 
    
     额外加入如下配置：
     replicate-wild-ignore-table = mysql.%
     replicate-wild-ignore-table = test.%
     replicate-wild-ignore-table = information_schema.%
     replicate-wild-ignore-table = performance_schema.%
     replicate-wild-ignore-table = yc_order_global.%
     replicate-wild-ignore-table = %.driver_order


   添加映射：    
  [mysqld]
   replicate-rewrite-db=yc_order0->yc_order
   replicate-rewrite-db=yc_order1->yc_order
   replicate-rewrite-db=yc_order2->yc_order
   replicate-rewrite-db=yc_order3->yc_order
   replicate-rewrite-db=yc_order4->yc_order
   replicate-rewrite-db=yc_order5->yc_order
   replicate-rewrite-db=yc_order6->yc_order
   replicate-rewrite-db=yc_order7->yc_order
   replicate-rewrite-db=yc_order8->yc_order
   replicate-rewrite-db=yc_order9->yc_order
   replicate-rewrite-db=yc_order10->yc_order

   ……………………

  为了方便我写了简单的shell脚步可以参考

  #!/bin/bash
  echo "[mysqld]" >> yc_order.cnf;
  for((i=0; $i<1024; i++)); do
    echo "replicate-rewrite-db=yc_order${i}->yc_order" >> yc_order.cnf;
  done
```

####    4、授权

```shell
[master1]（10.0.11.98）:
       grant replication slave,replication client on *.* to 'slaves'@'10.0.11.89' identified by '123456'; 
       grant replication slave,replication client on *.* to 'slaves'@'10.0.11.90' identified by '123456'; 还去其他从库只需更改host即可
       
[slave1]（10.0.11.90）:
        change master to master_host='10.0.11.98',master_user='slaves',master_password='123456',master_log_file='mysql-bin.000001',master_log_pos=1;
    
[master2]（10.0.11.99）:
       grant replication slave,replication client on *.* to 'slaves'@'10.0.11.89' identified by '123456';
       
 [汇总库]:（10.0.11.89）
  在master1和master2上数据库分别执行 show master status 可以看到类似下面的表格
        |File|Position|Binlog_Do_DB|Binlog_Ignore_DB|
        |mysql-bin.000001|1|||
     change master 'r1' to master_host='10.0.11.98',master_user='slaves',master_password='123456',master_log_file='mysql-bin.000001',master_log_pos=1;
     change master 'r2' to master_host='10.0.11.99',master_user='slaves',master_password='123456',master_log_file='mysql-bin.000001',master_log_pos=1;
    
```


最后执行start all slaves 大功告成！
执行一下