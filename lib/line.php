<?php
   //wx输出(news)三个超链接,linemap,upstations,downstations
   
$usercode='';//to
$platcode='';//from

$con='';

$linename='B11线路';
$ldes="";
$lineurl='';
$purl='map.jpg';
$upstation="刘庄";
$upurl="";
$updes="";
$downstation="黄冈市";
$downurl='';
$downdes="";
$news[]=encyptnews($linename,$ldes,$purl,$lineurl);
$news[]=encyptnews($upstation,$updes,'',$upurl);
$news[]=encyptnews($downstation,$downdes,'',$downurl);

echo echonews($platcode,$usercode,$news,$con);
