qeephp-read_write_splitting
===========================

give the qeephp framework read write splitting feature.

qeephp本身没有读写分离的机制，所以需要自己来完成。
本项目是先编写一个数据库连接选择方法，根据需求来选择使用主数据库连接还是从数据库连接进数据库行操作，再通过重载qeephp本身
的orm model层save和destroy方法来实现的，当然要使用此功能的model需要继承我们的读写分离model。

要完成这个事情一共有4步:
1) 编写数据库DSN选择方法。将get_db_config.php中的getDbConfig及getDbCon函数复制到你的app文件中<如myapp.php>。
2) 重载model层<readwrite.php>
3) 修改框架app数据库初始化工作<参见dsn.init.txt>。
4) 修改需要使用读写分离的model类，继承我们的读写分离类<参见mymodel.php>。
5) MySQL数据库配置文件见database.yaml.php