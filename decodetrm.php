<?php
//$data = file_get_contents("http://www.set-fx.com/stats");
$obj = json_decode(file_get_contents("http://www.set-fx.com/stats"));
$trm = str_replace(",",".",str_replace(".","",$obj->{'trm'}));
echo $trm;
?>