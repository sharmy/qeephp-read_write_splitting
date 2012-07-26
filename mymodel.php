<?
/**
* 要使用主从分离读写的特性，Model需继承自Model_ReadWrite。
* 并且在__define方法return数组中定义数据表对应的数据库DSN配置名和
**/
class MyModel extends Model_ReadWrite {

    /**
     * 返回对象的定义
     *
     * @static
     *
     * @return array
     */
    static function __define() {

        //指定连接使用的DSN,以便在切换主从数据库的时候使用
        $_dsn = 'message_dsn';

        return array(
            //指定连接使用的DSN配置名
            '_dsn' => $_dsn,

            //数据库定义
            'table_config' => array(
                'conn' => getDbCon($_dsn), //指定数据库连接信息
            ),
            
            //table_name,props,validations等其它定义项...............
        )
    }
}