<?php 
include '../lib/curlClass.php';
function queryGpsLine($xl,$type)
{
$curl = new curlClass(); 
//获取站点名称，以后可以考虑使用数据库本地存储
$data=$curl->get(BUSLINE_URI, array('xl'=>$xl,'ref'=>'0'));  
$data=strip_tags(mb_strstr($data,'上行线路：',false,'utf-8'));  
$line= mb_strstr($data,'下行线路：',$type==0?true:false,'utf-8');
$line=mb_substr($line,5,mb_strlen($line,'utf-8')-5,'utf-8'); 
 //转为数组
$stations=mb_split('--', $line); 
$stationsNum=count($stations);
error_log($stationsNum);
$lastStation=$stations[$stationsNum-1];
$result='选择候车站点：';
for ($i=0; $i < $stationsNum ; $i++) { 	
	$result.=sprintf('--<a href="%s?xl=%s%s&ud=%s&sno=%s&ref=4">%s</a>',
		BUSGPS_URI,$xl,urlencode('路'),$type,$i,$stations[$i]); 
} 
return $result;
}
?>


