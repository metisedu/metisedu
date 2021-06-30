<?php
include_once('./_common.php');

$cp_id = $_POST['cp_id'];

$sql = "SELECT *
		FROM han_shop_coupon_log
		WHERE rand_id = '".$cp_id."'
		";
$data = sql_fetch($sql);

if(!$data){
	echo"30";
}else if($data['mb_id'] == ""){
	$sql = "UPDATE han_shop_coupon_log
			SET    mb_id = '".$member['mb_id']."'
			WHERE rand_id = '".$cp_id."'
			";
	sql_query($sql);

	echo"10";
}else if($data['mb_id']){
	echo"20";
}else{
	echo"30";
}
?>