<?php 
include '../lib/curlClass.php';
function queryGpsLine($xl,$type)
{
$curl = new curlClass(); 
//��ȡվ�����ƣ��Ժ���Կ���ʹ�����ݿⱾ�ش洢
$data=$curl->get(BUSLINE_URI, array('xl'=>$xl,'ref'=>'0'));  
$data=strip_tags(mb_strstr($data,'������·��',false,'utf-8'));  
$line= mb_strstr($data,'������·��',$type==0?true:false,'utf-8');
$line=mb_substr($line,5,mb_strlen($line,'utf-8')-5,'utf-8'); 
 //תΪ����
$stations=mb_split('--', $line); 
$stationsNum=count($stations);
error_log($stationsNum);
$lastStation=$stations[$stationsNum-1];
$result='ѡ���վ�㣺';
for ($i=0; $i < $stationsNum ; $i++) { 	
	$result.=sprintf('--<a href="%s?xl=%s%s&ud=%s&sno=%s&ref=4">%s</a>',
		BUSGPS_URI,$xl,urlencode('·'),$type,$i,$stations[$i]); 
} 
return $result;
}
?>


