<?php
/**
 * Created by le118
 * Author: le118
 * Date: 2019/12/9
 * Time: 11:55
 */

$table = new swoole_table(1024);
$table->column('name', $table::TYPE_STRING, 16);
$table->column('age', $table::TYPE_INT, 3);
$table->column('sex', $table::TYPE_STRING, 3);
$table->create();
$table->set(1, ['name' => 'wangle', 'age' => 26]);

$table[2] = [
    'name'=>'ez',
    'age'=> 18,
    'sex'=> '男生',
];
$table->incr(1, 'age', 2);
sleep(5);
var_dump($table->get(2));
echo $table->count().PHP_EOL;