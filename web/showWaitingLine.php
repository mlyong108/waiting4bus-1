<?php 
include '../lib/queryApi.php';
$xl=$_GET['xl'];
$type=$_GET['type'];
$stations=queryLineStations($xl,$type);
?>
<!DOCTYPE html><!--HTML5 doctype-->
<html>
<head>
<title></title>
<meta http-equiv="Content-type" content="text/html; charset=utf-8"> 
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">   
<link rel="stylesheet" type="text/css" href="../jqmobile/kitchensink/jq.ui.less.css" title="default"/>  
<script type="text/javascript" charset="utf-8" src="../jqmobile/jq.mobi.js"></script>   
<script type="text/javascript" charset="utf-8" src="../jqmobile/ui/jq.ui.js"></script> 
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.4"></script>  
<style>
    label {font-size:18px; font-weight:700;}
    select {font-size:18px; font-weight:400;}
    #queryResult {font-size:16px; font-weight:400;}
    
</style>
<script type="text/javascript">
    /* This function runs once the page is loaded, but appMobi is not yet active */
	var webRoot="../jqmobile/kitchensink/";
	$.ui.autoLaunch=true;
    $.ui.openLinksNewTab=false;
    $.ui.resetScrollers=false; 
	$.ui.ready(function(){  
	 $.ui.toggleHeaderMenu(false);
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
		url: './busLocation.php', 
		data: {'xl':'<?php echo $_GET["xl"];?>',
		'ud':<?php echo $_GET['type']; ?>,
		'sno':$('#stations').val()//索引从1开始
	 },  
		success: function(data) {
			$('#web').text(data);//todo：若查询结果为空，则考虑1、停运2、站点为开始的若干站，应以反方向最后几站值为准
		}
	});
   }
</script> 
</head>
<body>
<div id="jQUi">  
    <div id="header"> 
    </div>

<div id="content">
	<div title="<?php echo ($xl.'路开往'.$stations[count($stations)-1].'方向候车查询'); ?>" id="main" class="panel"  data-footer='none' selected="true">
	     	<fieldset> 
					<label for="stations">等车站点</label>
					<span><!-- span is needed for android select box fix -->
				<select id="stations" class='jq-ui-forms' onchange=selectedChanged()>

					<?php
					 for ($i=0; $i <count($stations) ; $i++) { 
					 echo "<option value='".($i+1)."'>".$stations[$i]."</option>";
				  } 
					?> 
					</select>
				</span><br/><br/>
				<!--<label for="web">查询结果</label><input type='button' value='刷新'>-->
				<!-- <textarea id="web" class='jq-ui-forms' readonly='false'></textarea><br/>  -->
				<div id='queryResult'></div>
				<!--设置默认查询站点：1、若非默认查询站点，checked=false，询问是否设置；
				2、已经为默认查询站点，则checked为ture，提示快捷查询指令为dc*-->
				<input type="checkbox" name="confirm" id="confirm" class="jq-ui-forms">
				  <label for="confirm">添加为默认候车查询站点？</label> 
			</fieldset>
	</div> 
</div>  
</div>
</body>
</html>