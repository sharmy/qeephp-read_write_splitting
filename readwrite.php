<?php

/**
 * Model_ReadWrite 类实现了 数据库读写分离
 * 
 * 其它Model继承此类，即可使用原来的方法自动调用主从数据库进程操作
 *
 * @author Chengtian.Hu <chengtian.hu@gmail.com>
 * @version $Id: readwrite.php $
 * @package orm
 */
abstract class Model_ReadWrite extends QDB_ActiveRecord_Abstract {

    /**
     * 保存对象到数据库
     * 
     * 重载QeePHP本身写方法，自动使用主数据库连接写入数据
     *
     * @param int $recursion 保存操作递归到多少层
     * @param string $save_method 保存对象的方法
     *
     * @return QDB_ActiveRecord_Abstract 连贯接口
     * @author Chengtian.Hu<chengtian.hu@gmail.com>
     */
    public function save($recursion = 99, $save_method = 'save') {
        //获取Model配置
        $ref = (array) call_user_func(array($this->_class_name, '__define'));
        //获取Model配置绑定的数据库连接对象
        $dsn = isset($ref['_dsn']) ? $ref['_dsn'] : null;
        //将Model对象绑定的数据库连接替换为主库连接
        QDB_ActiveRecord_Meta::instance($this->_class_name)->table->setConn(getDbCon($dsn, false));
        //保存数据
        parent::save($recursion, $save_method);
        //恢复绑定的原始数据库连接
        QDB_ActiveRecord_Meta::instance($this->_class_name)->table->setConn(getDbCon());
    }

    /**
     * 销毁对象对应的数据库记录
     *
     * 重载QeePHP本身删除记录对象方法，自动使用主数据库连接写入数据
     *
     * @author Chengtian.Hu<chengtian.hu@gmail.com>
     */
    public function destroy() {
        //获取Model配置
        $ref = (array) call_user_func(array($this->_class_name, '__define'));
        //获取Model配置绑定的数据库连接对象
        $dsn = isset($ref['_dsn']) ? $ref['_dsn'] : null;
        //将Model对象绑定的数据库连接替换为主库连接
        QDB_ActiveRecord_Meta::instance($this->_class_name)->table->setConn(getDbCon($dsn, false));
        //删除数据
        parent::destroy();
        //恢复绑定的原始数据库连接
        QDB_ActiveRecord_Meta::instance($this->_class_name)->table->setConn(getDbCon());
    }
    
	/**
     * 开始一个事务
     *
     * 调用 startTrans() 开始一个事务后，应该在关闭数据库连接前调用 completeTrans() 提交或回滚事务。
     * @author Chengtian.Hu<chengtian.hu@gmail.com>
     */
     function startTrans() {
    	$ref = $this->__define();
        $dsn = isset($ref['_dsn']) ? $ref['_dsn'] : null;
        getDbCon($dsn, false)->startTrans();
    }
    
     /**
     * 完成事务，根据事务期间的查询是否出错决定是提交还是回滚事务
     *
     * 如果 $commit_on_no_errors 参数为 true，当事务期间所有查询都成功完成时，则提交事务，否则回滚事务；
     * 如果 $commit_on_no_errors 参数为 false，则强制回滚事务。
     *
     * @param boolean $commit_on_no_errors
     * @author Chengtian.Hu<chengtian.hu@gmail.com>
     */
     function ComplateTrans($commit_on_no_errors = true) {
    	$ref = $this->__define();
        $dsn = isset($ref['_dsn']) ? $ref['_dsn'] : null;
        getDbCon($dsn, false)->completeTrans($commit_on_no_errors);
    }
}