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
				include_once constant('dc');	
			}else if(preg_match($line, $message['content'])){
				echo '线路查询';
				include_once constant('linephp');
			}else if(preg_match($p2p, $message['content'],$matches)){
				$cmd='p2p';
				$param1=$matches[1];
				$param2=$matches[3];
				//点到点
				include_once constant('p2p');
			}else {
				//echo 'help';
				include_once constant('other');
			}
		}else if($message['type']=='location')
		{
			// request to baidu,then get statiions around the location and echo
			include_once constant('location');
		}
	}

	function echotext($data,$from,$to){
		   $strlen=strlen($data);
		   if($strlen>2048)
		   {
		   		//return null;	
		   		$data=mb_substr($data, 0,2048);
		   }
		   
		   $type='text';
			$textTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[%s]]></MsgType><Content><![CDATA[%s]]></Content><FuncFlag>0</FuncFlag></xml>"; 
			$datetime=date("Y-m-d H:i:s");
			$textTpl=sprintf($textTpl,$to,$from,$datetime,$type,$data);
			return $textTpl;
	}
	/**
	 * news[][],title,des,picurl,url
	 * Enter description here ...
	 * @param unknown_type $from
	 * @param unknown_type $to
	 * @param unknown_type $data 应该为数字,data[0],data[1]
	 * @param unknown_type $content
	 */
	function echonews($from,$to,$data,$content=''){
		$newstpl="<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[news]]></MsgType><Content><![CDATA[]]></Content><ArticleCount>%d</ArticleCount><Articles>";
        $newstplend="</Articles><FuncFlag>1<FuncFlag></xml>";
        $count=count($data);
        $date=date('Y-m-d H:i:s');
        $newstpl=sprintf($newstpl,$to,$from,$date,$count);
		foreach ($data as $article){
			$newstpl=$newstpl.converttitlexml($article);
		}
		$newstpl=$newstpl.$newstplend;
		return $newstpl;
	}
	function converttitlexml($news){
		if(empty($news))
		{
			error_log("内容为空");
			return null;
		}
		return converttitlexml_1($news['t'],$news['des'], $news['pu'], $news['ur']);
	}
	function encyptnews($title,$desc,$purl,$url){
		$news['t']=$title;
		$news['des']=$desc;
		$news['pu']=$purl;
		$news['ur']=$url;
		return $news;
	}
	function converttitlexml_1($title,$desc,$purl,$url){
		$item="<item> <Title><![CDATA[%s]]></Title><Discription><![CDATA[%s]]></Discription><PicUrl><![CDATA[%s]]></PicUrl> <Url><![CDATA[%s]]></Url> </item>";
		$item=sprintf($item,$title,$desc,$purl,$url);
		return $item;
	}