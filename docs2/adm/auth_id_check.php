<?php
include_once("./_common.php");

$sQuery = " SELECT *
			FROM {$g5['member_table']}
			WHERE mb_email = '".$mb_id."'
			";
$rst = sql_fetch($sQuery);

if($rst){
	echo $rst['mb_name']." ".$rst['mb_email'];
}else{
	echo "해당 아이디로 가입된 회원이 없습니다.";
}
?>