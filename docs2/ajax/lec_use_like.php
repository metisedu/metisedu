<?php
include_once("./_common.php");

if(!$is_member){
	echo"로그인 후 이용해 주세요!";
	exit;
}

$sQuery = " SELECT *
			FROM han_shop_item_use_like
			WHERE is_id = '".$is_id."'
			AND   mb_id = '".$member['mb_id']."'
			";
$rst = sql_fetch($sQuery);

if($rst){
	echo"좋아요는 한번만 가능 합니다!";
	exit;
}else{
	sql_query("INSERT INTO han_shop_item_use_like SET is_id = '".$is_id."', mb_id = '".$member['mb_id']."', lk_time = '".date("Y-m-d H:i:s")."' ");
}

$sQuery = " UPDATE han_shop_item_use
			SET	is_like = (is_like + 1)
			WHERE is_id = '".$is_id."'
			";
sql_query($sQuery);

exit;
?>