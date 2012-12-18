<?php 
define('BUSLINE_URI', 'http://218.28.136.21:8081/line.asp');
define('BUSLocation_URI', 'http://218.28.136.21:8081/gps.asp'); 
define('BAIDU_PLACE_URI','http://api.map.baidu.com/place/search');
define('BAIDU_CONVERT_URI', 'http://api.map.baidu.com/ag/coord/convert');
define('BAIDU_KEY', '60f3e3376f8d6fb787110b9e16293f93');
include 'database.php';
function queryLineStations($xl,$type)
{
    //先从数据库中取出线路，如果没有在查询百度
    if(!empty($line=getline($xl))
     {
        return $line;
     }
$curl = new curlClass(); 
//获取站点名称，以后可以考虑使用数据库本地存储
$data=$curl->get(BUSLINE_URI, array('xl'=>$xl,'ref'=>'0')); 
$data=mb_strstr($data,'首末班时间：',false,'utf-8'); 

$bustime=ltrim(mb_split('<br>', $data)[0],'首末班时间：');
$bustime=mb_split('-',$bustime);
print_r($bustime);
$data=strip_tags(mb_strstr($data,'上行线路：',false,'utf-8')); 
$superline= mb_strstr($data,'下行线路：',true,'utf-8');
$superline=mb_substr($superline,5,mb_strlen($superline,'utf-8')-5,'utf-8'); 

$subline= mb_strstr($data,'下行线路：',false,'utf-8');
$subline=mb_substr($subline,5,mb_strlen($subline,'utf-8')-5,'utf-8'); 

 //转为数组
$subline=mb_split('--', $subline);
$superline=mb_split('--', $superline);  
//将line存入到数据库
$line11=addline($xl,strtotime($bustime[0]),strtotime($bustime[1])
  ,strtotime($bustime[0]),strtotime($bustime[1]));
  //存储站点
  foreach ($subline as $name) {//上行
      //how to decside x,y?
       $station=addstation($name,,$y);
       addl_s($stationname,$linename,$updown,$stationno);
  }
  //下行
  //todo:
return $stations;
} 
function queryBusLocation($xl,$ud,$sno)
{
	$curl=new curlClass();
	$data=$curl->get(BUSLocation_URI, array('xl'=>$xl,'ud'=>$ud,
		'sno'=>$sno,'ref'=>'4'));
	$data=strip_tags(mb_strstr($data,'候车信息：',false,'utf-8'));
	$data=mb_strstr($data,'当前查询时间',true,'utf-8');
    $data=mb_substr($data,5,mb_strlen($data,'utf-8')-5,'utf-8');
    return $data;
}
//将Google坐标转换为百度
function transG2B($x,$y){
 $curl=new curlClass(); 
 $data=$curl->get(BAIDU_CONVERT_URI, 
     array('from'=>'2','to'=>'4','x'=>$x,'y'=>$y));
 $data=json_decode($data); 
 return array('x'=>base64_decode($data->x),'y'=>base64_decode($data->y));
}
//todo:百度api使用限制(1000次/天)，使用多个key轮流;
//todo:百度站点查询相当不准确a、上下行站点没分开b、缺失部分信息
function queryLocStations($x,$y,$r)
{     
 $curl=new curlClass(); 
 //?&query=公交车站&location=39.915,116.404&radius=2000&output=json&key=
 $data=$curl->get(BAIDU_PLACE_URI, 
     array('query'=>'公交车站','location'=>$y.','.$x,//注意是纬度在前！！
        'radius'=>$r,'output'=>'json','key'=>BAIDU_KEY));
  //存入到数据库
  $sts=json_decode($data);
  //遍历并存入数据库
  return json_decode($data);
}

//一些查询函数，mocks
//查询运营时间
//@deprecated
function queryBusTimeSpan($xl)
{
    //
    return '5:30-21:00';
}
//返回站点为线路的第多少站,$type=0为上行
//@deprecated
function queryStationIndex($xlId,$stationId,$type)
{

    return 5;
}
//@deprecated
function queryStationLocation($sId)
{

}
/** 
* CURL基本类,包含get方式及POST方式 
* 
* @author  gster
* @date    2012-12-5 
*/ 
class curlClass 
{ 
    public function get($url, $params) 
    { 
        $ch = curl_init(); 
        // 设置 curl 相应属性 
        curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params)); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        $returnTransfer = curl_exec($ch); 
        curl_close($ch); 
        return $returnTransfer; 
    }
    public function post($url, $params) 
    { 
        $ch = curl_init(); 
        // 设置 curl 相应属性 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
        $returnTransfer = curl_exec($ch); 
        curl_close($ch); 
        return $returnTransfer; 
    } 
} 
// 测试代码 
//$curl = new curlClass(); 
//var_dump($curl->get(', array('a'=>'get','b'=>'1'))); 
//var_dump($curl->post(', array('a'=>'post','b'=>'2'))); 

?>