# multi_daemon_process
多进程处理数据，daemon进程

###使用
在crontab中添加如下内容
```shell
php /path/test.php -s start --count 1
php /path/test.php -s start --count 2
php /path/test.php -s start --count 2   //报错，进程正在运行
php /path/test_new.php -s start --count 1
php /path/test_new.php -s start --count 2
php /path/test_new.php -s start --count 2   //报错，进程正在运行

php /path/test.php -s stop --count 1     //停止该文件对应的进程序号为1的进程
php /path/test_new.php -s stop --count 2   //停止该文件对应的进程序号为2的进程
```

### 主要作用
写这个的主要目的，适用于一个队列数据的处理

有几百条数据，每条数据处理需要4s左右，一共需要几时分钟，我将所有的数据信息全部存储到redis队列中，用多个进程去处理数据

一个生产者，多个消费者，速度回快很多
