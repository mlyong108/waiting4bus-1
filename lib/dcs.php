<?php
//wx输出(text)本站的候车信息

require_once 'simplexml.php'; //不需要

$businfo="还有五分钟第一车到";
$usercode="1232123";
$platcode="12312321";
echo echotext($businfo,$platcode,$usercode);