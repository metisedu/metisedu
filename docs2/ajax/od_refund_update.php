<?php
include_once("./_common.php");

if($od_status != "입금" && $od_status != "배송" && $od_status != "완료" ){
	echo"nopay";
	exit;
}

$od = sql_fetch("SELECT * FROM han_shop_order WHERE od_id = '".$od_id."' ");

if($od['od_receipt_price'] < $od_refund_sub_price){
	echo"uppay";
	exit;
}

$od_refund_memo = strip_tags($od_refund_memo);

$sQuery = " INSERT INTO han_shop_order_refund
				SET	od_id				= '".$od_id."'
				,	od_refund_memo		= '".$od_refund_memo."'
				,	od_refund_sub_price = '".$od_refund_sub_price."'
				,	od_refund_name		= '".$od_refund_name."'
				,	od_refund_bank		= '".$od_refund_bank."'
				,	od_refund_account	= '".$od_refund_account."'
				,	re_time				= '".G5_TIME_YMDHIS."'
				";
$result = sql_query($sQuery, false);

if(!$result){
	echo"noinsert";
	exit;
}

$sQuery = " UPDATE han_shop_order
				SET od_status			= '환불신청'
				,	od_refund_memo		= '".$od_refund_memo."'
				,	od_refund_sub_price = '".$od_refund_sub_price."'
				,	od_refund_name		= '".$od_refund_name."'
				,	od_refund_bank		= '".$od_refund_bank."'
				,	od_refund_account	= '".$od_refund_account."'
				,	od_refund_time		= '".G5_TIME_YMDHIS."'
			WHERE od_id = '".$od_id."'
			";
$rst = sql_query($sQuery, false);

if(!$rst){
	echo"noupdate";
	exit;
}
?>