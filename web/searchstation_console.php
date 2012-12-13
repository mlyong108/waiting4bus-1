 <?php   
//将谷歌转换为百度
 include '../lib/curlClass.php';
function transG2B($x,$y){
 $curl=new curlClass(); 
 $data=$curl->get('http://api.map.baidu.com/ag/coord/convert', 
     array('from'=>'2','to'=>'4','x'=>$x,'y'=>$y));
  $data= json_decode($data);
  return base64_decode($data->x).','.base64_decode($data->y);
}
//print_r(transG2B(113.673,34.768));
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.4"></script>
<title></title>
</head>
<body>
<div id='allmap'></div>
<div id='log'></div>
</body>
</html>

<script type="text/javascript">
var map = new BMap.Map("allmap");            // 创建Map实例
var mPoint = new BMap.Point(<?php echo transG2B($_GET['x'],$_GET['y']);?>); 
map.centerAndZoom(mPoint, 18);
var options = {
  onSearchComplete: function(results){
    // 判断状态是否正确
    if (local.getStatus() == BMAP_STATUS_SUCCESS){
      var s = [];
      for (var i = 0; i < results.getCurrentNumPois(); i ++){
        s.push(results.getPoi(i).title + ", " + results.getPoi(i).address);
      }
      document.getElementById("log").innerHTML = s.join("<br/>");
    }
  }
};
var local = new BMap.LocalSearch(map, options);
var circle = new BMap.Circle(mPoint, 300, {
    fillColor: "blue",
    strokeWeight: 1,
    fillOpacity: 0.3,
    strokeOpacity: 0.3
});
var bounds = getSquareBounds(circle.getCenter(), circle.getRadius());
local.searchInBounds("公交车站", bounds);


/**
 * 得到圆的内接正方形bounds
 * @param {Point} centerPoi 圆形范围的圆心
 * @param {Number} r 圆形范围的半径
 * @return 无返回值
 */

function getSquareBounds(centerPoi, r) {
    var a = Math.sqrt(2) * r; //正方形边长
    mPoi = getMecator(centerPoi);
    var x0 = mPoi.x,
        y0 = mPoi.y; 
    var x1 = x0 + a / 2,
        y1 = y0 + a / 2; //东北点
    var x2 = x0 - a / 2,
        y2 = y0 - a / 2; //西南点
    var ne = getPoi(new BMap.Pixel(x1, y1)),
        sw = getPoi(new BMap.Pixel(x2, y2));
    return new BMap.Bounds(sw, ne); 
}
//根据球面坐标获得平面坐标。 
function getMecator(poi) {
    return map.getMapType().getProjection().lngLatToPoint(poi);
}  
function getPoi(mecator) {
    return map.getMapType().getProjection().pointToLngLat(mecator);
} 
</script>
 


 