<?php
//用于ajax调用刷新候车信息的页面
include '../lib/queryApi.php';//($xl,$ud,$sno,$fx,$hczd)
header('Content-Type: text/html; charset=utf-8');
$data=queryBusLocation($_GET['xl'].'路',$_GET['ud'],$_GET['sno']); 
echo $data;
?>