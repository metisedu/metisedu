<?php
include_once('./_common.php');

$co_id = preg_replace('/[^a-z0-9_]/i', '', $co_id);
if($_GET['co_id'] == '') {
	alert('정상적인 접근이 아닙니다');
}



if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/'.$_GET['co_id'].'.php');
    return;
}
?>

