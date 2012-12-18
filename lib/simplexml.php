<?php
   define('linephp', 'line.php');//wx输出(news)三个超链接,linemap,upstations,downstations
   define('dc', 'dcs.php');//wx输出(text)本站的候车信息,
   define('p2p', 'p2p.php');//通过百度查处路线，wx输出(txt)该信息
   define('location', 'locations.php');//输出附近所有站点,wx输出txt
   define('other', 'othercmds.php');//输出帮助,
    /**
     * 
     * Enter description here ...
     * @param unknown_type $str
     * xml格式:
     */
	function  parse_xml($str){
		if(empty($str))
			return null;
		$xml=simplexml_load_string($str);
	 	$message['from']=$xml->FromUserName;
	 	$message['to']=$xml->ToUserName;
	 	$message['type']=$xml->CreateTime;
	 	$message['type']=$xml->MsgType;
	 	if($message['type']=='text')
	 	{
	 		$message['content']=trim($xml->Content);
	 		$message['funflag']=$xml->FuncFlag;
	 	}else{
	 		$message['x']=$xml->Location_X;
	 		$message['y']=$xml->Location_Y;
	 		$message['scale']=$xml->Scale;
	 		$message['label']=$xml->Label;
	 	}
	 	return $message;
	}
	function  parse_cmd($message){
		if($message['type']=='text')
		{
			$dcnum="/^dc(\\d{1,3})/i";
			$line="/^(游|支|b|B|t|T|\\d)\\d*/";
			$p2p="/^(.+)(-|——)(.+)/";
			$ppsearch='';
			if(preg_match($dcnum, $message['content'],$matches))
			{
				$cmd='dc';
				$param1=$matches[1];	
				echo 'dc查询';	
			}else if(preg_match($line, $message['content'])){
				echo '线路查询';
			}else if(preg_match($p2p, $message['content'],$matches)){
				$cmd='p2p';
				$param1=$matches[1];
				$param2=$matches[3];
				echo '点到点';
			}else {
				echo 'help';
			}
		}else if($message['type']=='location')
		{
			// request to baidu,then get statiions around the location and echo
		}
	}