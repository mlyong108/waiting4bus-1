<?php
include '../lib/queryApi.php';
header('Content-Type: text/html; charset=utf-8');
$loc=transG2B($_GET['x'],$_GET['y']);
$data=queryLocStations($loc['x'],$loc['y'],500);
print_r($data);
?>