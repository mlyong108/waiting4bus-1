<?php 
include '../lib/queryApi.php';
//todo:由站点uid查询站点经过的路线名称，并且该站点属于路线的第多少站。
$stationName='测试站';//queryByUid;
$linesInStation=array('Y809'=>15 ,'49'=>12 );//queryLineByStationUid($_GET['uid']);
?>
<!DOCTYPE html><!--HTML5 doctype-->
<html>
<head>
<title>站点候车查询</title>
<meta http-equiv="Content-type" content="text/html; charset=utf-8"> 
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">   
<link rel="stylesheet" type="text/css" href="../jqmobile/kitchensink/jq.ui.less.css" title="default"/>  
<style>
    label {font-size:18px; font-weight:700;}
    select {font-size:18px; font-weight:400;}
    #queryResult {font-size:16px; font-weight:400;}
    #allmap {height:300px;}
</style>
<script type="text/javascript" charset="utf-8" src="../jqmobile/jq.mobi.js"></script>   
<script type="text/javascript" charset="utf-8" src="../jqmobile/ui/jq.ui.js"></script> 
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.4"></script>  
<script type="text/javascript">
    /* This function runs once the page is loaded, but appMobi is not yet active */
	var webRoot="../jqmobile/kitchensink/";
	$.ui.autoLaunch=true;
    $.ui.openLinksNewTab=false;
    $.ui.resetScrollers=false; 
	$.ui.ready(function(){  
	$.ui.toggleHeaderMenu(false);
    drawWalkingRoute();
	});

    /* This code is used to run as soon as appMobi activates */
    var onDeviceReady=function(){
		AppMobi.device.setRotateOrientation("portrait");
        AppMobi.device.setAutoRotate(false);
		webRoot=AppMobi.webRoot+"kitchensink/"; 
	    $.ui.blockPageScroll(); //block the page from scrolling at the header/footer		
    };
    document.addEventListener("appMobi.device.ready",onDeviceReady,false);    
   function selectedChanged()
   {
		$.ajax({//http://218.28.136.21:8081/gps.asp?xl=49%E8%B7%AF&ud=0&sno=14&ref=4
		url: 'busLocation.php', 
		data: {'xl':$("option")[$('#lines')[0].selectedIndex].text,
		'ud':'0',
		'sno':$('#lines').val() 
	 },  
		success: function(data) {
			$('#queryResult').text(data);
		}
	});
   }
   function drawWalkingRoute()
   {

//显示路线图
var map = new BMap.Map("allmap");
map.centerAndZoom(new BMap.Point(116.404, 39.915), 11);
var walking = new BMap.WalkingRoute(map, {renderOptions:{map: map, autoViewport: true}});
walking.search("天坛公园", "故宫");
   }
</script> 
</head>
<body>
<div id="jQUi">  
   
<div id="content">
	<div title="" 
		id="main" class="panel"  data-footer='none' selected="true">
	     	<fieldset> 
					<label for="lines">选择等车线路</label>
					<span><!-- span is needed for android select box fix -->
				<select id="lines" onchange=selectedChanged()>

					<?php
					 foreach ($linesInStation as $name => $val) {
						echo "<option value='".$val."'>".$name."</option>";
					}
					?> 
					</select>
				</span><br/><br/>
				<div id='queryResult'></div>
				<br/> 
				<input type="checkbox" name="confirm" id="confirm" class="jq-ui-forms">
				  <label for="confirm">添加为默认候车查询站点？</label> 
			</fieldset>
			<div id='allmap'>
			<!--地图，显示当前站点和当前位置间的路线-->
			</div>
	</div> 
</div>   
</div>
</body>
</html>