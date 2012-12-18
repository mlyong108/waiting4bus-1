<?php
function  connection()
{
    $link=mysql_connect('localhost:3306','weixin','weixin');
    if(!$link)
      return 0;
    if(!mysql_select_db('weixindata'))
    {
    	$sql1= 'create database weixindata';
    	if(mysql_query($sql1))
    	{
    		mysql_select_db('weixin');
    		return $link;
    	}
    }
    return 0;
}
function adduser($usercode)
{
	$link=connection();
	 if(!$link)
      return 0;
	$id=0;
	$query="select id from user where usercode='%s' limit 1";
	sprintf($query,$usercode);
	$result=mysql_query($query);
	if(!$result)
	{
			$id=mysql_result($result, 0);
	}
	else 
	{
		$query="insert into user (usercode,intime) values ('%s',now())";	
		sprintf($query,$usercode);
		mysql_query($query,$link);
		$id=mysql_insert_id($link);
	}
	mysql_close($link);
	return $id;
}
function getuserid($usercode)
{
	$link=connection();
	 if(!$link)
      return 0;
	$id=0;
	$query="select id from user where usercode='%s' limit 1";
	sprintf($query,$usercode);
	$result=mysql_query($query);
	if(!$result)
	{
			$id=mysql_result($result, 0);
	}
	mysql_close($link);
	return $id;
}
function deleteuser($usercode)
{
	$re=0;
	$link=connection();
	 if(!$link)
      return 0;
	if(!$id=getuserid($usercode))
		$re=1;
	$query0="delete from  userdc userid='%d'";
	sprintf($query0,$id);
	$query1="delete from  userlog userid='%d'";
	sprintf($query1,$id);
	$query2="delete from  user usercode='%s'";
	sprintf($query2,$usercode);
	$link=connection();
	if(!mysql_query('START TRANSACTION',$link))
		return $re=0;
	if(!mysql_query($query0,$link))
	{
		mysql_query('ROLLBACK');
	}
	if(!mysql_query($query1,$link))
	{
		mysql_query('ROLLBACK');
	}
	if(!mysql_query($query2,$link))
	{
		mysql_query('ROLLBACK');
	}
	if(!mysql_query('COMMIT'))
	{
		$re=0;
	}
	mysql_close($link);
	return $re;
}

function adduserlog($usercode,$content,$type)
{
	$query="insert into userlog (userid,querycontent,querytime,contenttype) values ((select id from user where usercode='%s'),'%s',NOW(),'%s'";
	sprintf($query,$usercode,$content,$type);
	$link=connection();
	 if(!$link)
      return 0;
	mysql_query($query,$link);
	$id=mysql_insert_id($link);
	mysql_close($link);
	return $id;
}
/**
   1. 查询是否重复
   2. 查询空闲值
   3. insert
   返回的是插入的快捷命令序号
*/
function addfavstation($usercode,$l_s_id)
{
	$cmdnum=0;
	$link=connection();
	 if(!$link)
      return 0;
    $query="select a.id,a.cmdnum from favstation a where a.linestationid=%d and a.userid=(select id from user where usercode='%s')";//(select id from line_station where name='%s' and line= )";
    sprintf($query,$l_s_id,$usercode);
    $result=mysql_query($query);
    if(mysql_num_rows($result))
    {
    	$cmdnum=mysql_result($result, 0,1);
    	mysql_close($link);
    	return $cmdnum;
    }
    $query="select a.cmdnum from favstation a where a.userid=(select id from user where usercode='%s') order by a.cmdnum";
    sprintf($query,$usercode);
    $result=mysql_query($query);
    $min=findMin($result);
    $query="insert into favstation (userid,cmdnum,createtime,linestationid) values ((select id from user where usercode='%s'),%d,NOW(),%d)";
    sprintf($query,$usercode,$min,$l_s_id);
    mysql_query($query,$link);
    mysql_close($link);
    return $min;
}
function findMin($result)
{
	$pointer=0;
	$len=mysql_num_rows($result);
	$i=1;
	for($i=1;;$i++)
	{
		$num=$mysql_result($result);
		if(!$num || $i<$num)
			return $i;
	}
} 
function delfavstation($usercode,$num){
	$link=connection();
	if(!$link)
      return 0;
     $query="delete from favstation where userid=(select id from user where usercode='%s') and cmdnum=%d";
     sprintf($query,$usercode,$num);
     $mysql_query($query);
    mysql_close($link);
}
function getfavstation($usercode,$l_s_id)
{
	$cmdnum=0;
	$link=connection();
	if(!$link)
      return 0;
    $query="select cmdnum from favstation where userid=(select id from user where usercode='%s') and linestationid=%d";
    sprintf($query,$usercode,$num);
    if($result=$mysql_query($query)){
    	$cmdnum=mysql_result($result, 0);	
    }
    mysql_close($link);
    return $cmdnum;		
}
/*baidu key 手动添加即可*/
function addbaidukey(){
 	
}
function deletebaidukey(){
	
}
/**
	采用触发器，每天零点正要清空times
*/
function clearbaidukey($date){
	
}
function getbaidukey(){
	$link=connection();
	if(!$link)
      return null;
    $query="select a.id,a.key from baidukey as a where a.times<1000 limit 1";
    $result=mysql_query($query);
    $baidukey['id']=mysql_result($result, 0,0);
    $baidukey['key']=mysql_result($result, 0,1);
    mysql_close($link);
    return $baidukey;
}
function getline($linename)
{
	$link=connection();
	if(!$link)
      return null;
  $query="select * from busline where name='%s' limit 1";
  if($result=mysql_query($query))
  {
  	 $line['id']=mysql_result($result, 0,0);
  	 $line['name']=mysql_result($result, 0,1);
  	 $line['unum']=mysql_result($result, 0,2);
  	 $line['dnum']=mysql_result($result, 0,3);
  	 $line['us']=mysql_result($result, 0,4);
  	 $line['ue']=mysql_result($result, 0,5);
  	 $line['ds']=mysql_result($result, 0,6);
  	 $line['de']=mysql_result($result, 0,7);
  }
  mysql_close($link);
  return $line; 
}
function addline($name,$us,$ue,$ds,$de)
{
	//自动查询只能查出前三项
    $link=connection();
	if(!$link)
  	    return null;
  	if($line=getline($name))
  	{
  		mysql_close($link);
  		return $line;
  	}
    $query="insert into busline (name,upstations,downstations,upstart,upend,downstart,downend) values ('$s',0,0,'%s','%s','%s','%s')";
    sprintf($query,$name,$us,$ue,$ds,$de);
    mysql_query($query);
    $line=getline($name);
    mysql_close($link);
    return $line;
}
function delline($lineid)
{
	$link=connection();
	if(!$link)
  	    return null;
  	$query="delete from busline where id=%d";
  	sprintf($query,$lineid);
  	mysql_query($query);
  	mysql_close($link);
  	
}

function getstation($sname){
	$link=connection();
	if(!$link)
  	    return null;
  	$query="select * from station where name='%s'";
  	if($result=mysql_query($query)){
  		$station['id']=mysql_result($result, 0,0);
  		$station['name']=mysql_result($result, 0,1);
  		$station['lx']=mysql_result($result, 0,2);
  		$station['ly']=mysql_result($result, 0,3);
  	}
  	mysql_close($link);
  	return $station;
}

function delstation($id)
{
	$link=connection();
	if(!$link)
  	    return null;
  	$query="delete from station where id=%d";
  	sprintf($query,$id);
  	mysql_query($query);
  	mysql_close($link);
}

function addstation($name,$lx,$ly){
	$link=connection();
	if(!$link)
  	    return null;
    
  	$query="select * from station where name='%s'";  //目前用一个站点，以后用坐标
  	if($result=mysql_query($query)){
  		$station['id']=mysql_result($result, 0,0);
  		$station['name']=mysql_result($result, 0,1);
  		$station['lx']=mysql_result($result, 0,2);
  		$station['ly']=mysql_result($result, 0,3);
  		mysql_close($link);
  		return $station;	
  	}

  	$query="insert into station (name,lx,ly) values ('%s',%f,%f)";
  	sprintf($query,$name,$lx,$ly);
  	mysql_query($query);

  	$query="select * from station where name='%s'";
  	if($result=mysql_query($query)){
  		$station['id']=mysql_result($result, 0,0);
  		$station['name']=mysql_result($result, 0,1);
  		$station['lx']=mysql_result($result, 0,2);
  		$station['ly']=mysql_result($result, 0,3);
  		mysql_close($link);
  		return $station;	
  	}
  	mysql_close($link);
  	return $station;	
}



function getl_s($stationname,$line,$updown){
	$link=connection();
	if(!$link)
  	    return null;
  	$query="select * from line_station where stationid=(select id from station where name='%s') and lineid=(select id from line where name='%s') and direction=%d";
  	if($result=mysql_query($query));
  	{
  		$ls['num']=mysql_result($result, 0,'number');
  		$ls['di']=mysql_result($result, 0,'direction');
  		$ls['id']=mysql_result($result, 0,'id');
  		$ls['li']=mysql_result($result, 0,'lineid');
  		$ls['si']=mysql_result($result, 0,'stationid');
  	}
  	mysql_close($link);
  	return $ls;
}

function  addl_s($stationname,$line,$updown,$num)
{
	if($ls=getl_s($stationname,$line,$updown))
		return $ls;
	$link=connection();
	if(!$link)
  	    return null;
  	$query="insert into line_station (stationid,lineid,number,direction) values ((select id from station where name='%s'),(select id from line where name='%s'),%d,%d)";
  	sprintf($query,$stationname,$line,$num,$updown);
  	//todo: 本来需要用事物，插入station，更新线路station的number
    if(mysql_query(query)){
      if($updown==0)
       $query="update busline set upstations=upstations+1";
      else
       $query="update busline set upstations=upstations+1";
       mysql_query($query);
    }
  	//mysql_close($link);
  	$ls=getl_s($stationname,$line,$updown);
  	return $ls;
}

function dell_s($id)
{
	$link=connection();
	if(!$link)
  	    return null;
  	$query="delete from line_station where id=".$id;
  mysql_query($query);
  mysql_close($link);
}



function getxl($xl,$type)
{
  if(!$line=getline($xl))
    return null;
  $link=connection();
  if(!$link)
        return null;
  $query="select a.id,a.name,b.number from station a left join line_station b on a.id=b.stationid left join busline c on b.lineid=c.id where b.direction=%d and c.name=%s order by b.number";
  if($result=mysql_query($query))
  {
     while($aaa=mysql_fetch_array($result)){
       $stations[]=$aaa;
     }
  }
  mysql_close($link);
  return $stations;
}
//
function getRoundstations($x,$y,$r)
{

}