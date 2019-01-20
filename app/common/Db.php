<?php

//单例模式 三大原则
//1.构造函数需要标记为非public（防止外部使用new操作符创建对象）
//单例类不能再其他类中实例化，只能被其自身实例化；
//2.拥有一个保存类的实例的静态成员变量$_instance
//3.拥有一个访问这个实例的公共的静态方法
class Db {

    static private $_instance; //保存改单例类的实例的静态成员变量
    private $_dbConfig = array(
        'host' => '127.0.0.1',
        'user' => 'root',
        'password' => 'root',
        'database' => 'vip_member'
    );
    private $_connectSource;

    #非public的构造函数

    private function __construct() {
        
    }

    #访问这个实例的公共静态方法

    static public function getInstance() {
        #判断该单例类的实例是否存在
        if (!(self::$_instance instanceof self)) {
            #不存在则生成实例
            self::$_instance = new self();
        }
        #存在，则返回该实例
        return self::$_instance;
    }

    #连接数据库

    public function connect() {
        if (!self::$_connectSource) {
            #连接数据库，获取一个连接资源
            self::$_connectSource = mysql_connect($this->_dbConfig['host'], $this->_dbConfig['user'], $this->_dbConfig['password']);
            #判断，资源不存在，终止，并显示error
            if (!self::$_connectSource) {
                die('mysql connect error' . mysql_error());
            }
            #选择数据库
            mysql_select_db($this->_dbConfig['database'], self::$_connectSource);
            #设置字符集编码 
            mysql_query('set names UTF8', self::$_connectSource);
        }
        #返回连接数据库的资源
        return self::$_connectSource;
    }

}

#使用这个单例类,首先访问静态方法获取实例（对象），在调用connect方法
$connect = Db::getInstance()->connect();

$sql = "select * from vip_member";
$result = mysql_query($sql,$connect);
//输出数据的条数
echo mysql_num_rows($result);