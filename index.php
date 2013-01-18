<?php


define('API_URL', 'http://xxx.com');
$yii = dirname(__FILE__) . '/../framework/yii.php';
/**
 * 请根据实际情况修改main.php对应的目录
 */
$config = dirname(__FILE__) . '/../common/config/main.php';
require_once($yii);
Yii::createWebApplication($config)->run();
