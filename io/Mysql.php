<?php

/**
 * 异步Mysql
 * Class AysMysql
 */
class AysMysql
{
    /**
     * @var string
     */
    public $dbSource = "";
    /**
     * mysql的配置
     * @var array
     */
    public $dbConfig = [];

    public function __construct()
    {
        //new swoole_mysql;
        $this->dbSource = new Swoole\Mysql;

        $this->dbConfig = [
            'host' => '127.0.0.1',
            'port' => 3306,
            'user' => 'root',
            'password' => '8db170b9cc5f01c6',
            'database' => 'swoole',
            'charset' => 'utf8',
        ];
    }

    public function update()
    {

    }

    public function add()
    {

    }

    /**
     * mysql 执行逻辑
     * @param $sql
     * @return bool
     */
    public function execute($sql)
    {
        // connect
        $this->dbSource->connect($this->dbConfig, function ($db, $result) use ($sql) {
            echo "mysql-connect" . PHP_EOL;
            if ($result === false) {
                var_dump($db->connect_error);
                // todo
            }

            $db->query($sql, function ($db, $result) {
                // select => result返回的是 查询的结果内容
                if ($result === false) {
                    // todo
                    var_dump($db->error);
                } elseif ($result === true) {// add update delete
                    // todo
                    var_dump($db->affected_rows);
                } else {
                    print_r($result);
                }
                $db->close(); //4.4.13-alpha版本 装了（swoole/ext-async）这个拓展之后并没有结束连接 ？？
            });
            echo 'executing' . PHP_EOL;
            sleep(3);

        });
        return true;
    }
}

$obj = new AysMysql();
$name = 'naruto';
//$sql = "select * from  users where id=1";
$sql = "update users set `name` = '" . $name . "' where id=1";
// insert into
// query (add select update delete)
$flag = $obj->execute($sql);
var_dump($flag) . PHP_EOL; // 先执行
echo "start" . PHP_EOL;// 先执行
sleep(4);// 先执行
echo "waited 4 s" . PHP_EOL;// 先执行
/*顺序的执行完之后才会执行异步MYSQL内的方法*/
// echo 'mysql-connect'; => echo 'executing';  => sleep(3);
// 最后执行最内部的 print_r($result);
