<?php
//用于ajax调用刷新候车信息的页面
include '../lib/queryApi.php';//($xl,$ud,$sno,$fx,$hczd)
$data=queryBusLocation($_GET['xl'].'路',$_GET['ud'],$_GET['sno']); 
if ($data=='未查询到车辆。') {
   
}
echo $data;
?>