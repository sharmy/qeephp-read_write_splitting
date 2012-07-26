<?php
/**
 * 根据指定DSN下标获取数据库配置，可选择主数据库或者从数据库，默认返回从数据库配置
 *
 * @param string $dsn_name 要使用的数据库配置名
 *
 * @return array 数据库配置
 * @author Chengtian.Hu<chengtian.hu@gmail.com>
 */
function getDbConfig($dsn_name = null, $read=true){
    if(!$dsn_name) $dsn_name = strtolower( Q::ini('app_config/RUN_MODE'));

    $dsnConfig = Q::ini('db_dsn_pool/' . $dsn_name);
    //读数据，选择从数据库，写数据，选择主数据库
    $dsnLabel = $read ? 'read' : 'write';

    //配置了一个主/从库
    if (!isset($dsnConfig[$dsnLabel][0]['driver']) && !isset($dsnConfig[$dsnLabel][0]['_use'])) {
        $dsnArr = array($dsnConfig[$dsnLabel]);
    }
    //配置了多个主/从库
    elseif(isset($dsnConfig[$dsnLabel])){
        $dsnArr = $dsnConfig[$dsnLabel];
    }else{
        $dsnArr = $dsnConfig;
    }

    $default = empty($dsn_name);
    if ($default && Q::isRegistered('dbo_default')) {
        return Q::registry('dbo_default');
    }

    if (empty($dsn_name)) {
        $dsn = Q::ini('db_dsn_pool/default');
    } else {
        //从计算出的数据库列表中随机选择一个
        $dsn = $dsnArr[array_rand($dsnArr)];
    }

    return $dsn;
}


/**
 * 根据指定DSN下标获取数据库连接，可选择主数据库或者从数据库，默认返回从数据库连接
 *
 * @param string $dsn_name 要使用的数据库连接
 *
 * @return QDB_Adapter_Abstract 数据库访问对象
 * @author Chengtian.Hu<chengtian.hu@gmail.com>
 */
function getDbCon($dsn_name = null, $read=true) {
    $dsn = getDbConfig($dsn_name,$read);

    if (!empty($dsn['_use'])) {
        $used_dsn = Q::ini("db_dsn_pool/{$dsn['_use']}");
        $dsn = array_merge($dsn, $used_dsn);
        unset($dsn['_use']);
        if ($dsn_name && !empty($dsn)) {
            Q::replaceIni("db_dsn_pool/{$dsn_name}", $dsn);
        }
    }

    if (empty($dsn)) {
        // LC_MSG: Invalid DSN.
        trigger_error('invalid dsn');
        throw new QException(__('Invalid DSN.'));
    }

    $dbtype = $dsn['driver'];
    $objid = "dbo_{$dbtype}_" . md5(serialize($dsn));
    if (Q::isRegistered($objid)) {
        return Q::registry($objid);
    }

    $class_name = 'QDB_Adapter_' . ucfirst($dbtype);
    $dbo = new $class_name($dsn, $objid);

    Q::register($dbo, $objid);
    $default = empty($dsn_name);
    if ($default) {
        Q::register($dbo, 'dbo_default');
    }

    return $dbo;
}