<?php
include_once("./_common.php");

$mb_id = $_POST["mb_id"];
$mode = $_POST["mode"];

$res_arr = Array();

if($mode == "add"){
	
	$point = $_POST["point"];
	$sub_point = $point * (-1); // 차감할 포인트
	$add_point = $point; // 추가할 코인

	// 입력한 만큼 포인트 차감
	insert_point2($mb_id, $sub_point, $point.' 포인트 '.$add_point .' 코인으로 환전', '@passive', $mb_id, $mb_id.'-'.uniqid(''), "", "", "", "");
	

	// 차감한 포인트 만큼 코인 추가
	//insert_point_metis_coin($mb_id, $point, $content='', $rel_table='', $rel_id='', $rel_action='', $expire=0)
	insert_point_metis_coin($mb_id, $add_point, $point.' 포인트 '.$add_point .' 코인으로 환전', $rel_table='', $rel_id='', $rel_action='', $expire=0);
	
	
	$point_sql = "SELECT * FROM han_point WHERE mb_id = '".$mb_id."' ORDER BY po_id DESC LIMIT 1";
	$point_row = sql_fetch($point_sql);


	$mem_info = get_member($mb_id);
	$res_arr["his_log"] = $point_row["po_datetime"]." : ".$point_row["po_content"];
	$res_arr["point"] = $mem_info["mb_point"];
	$res_arr["point_metis"] = $mem_info["mb_point_metis"];

	die(json_encode($res_arr));
}else if($mode == "all"){
	$sql = "SELECT * FROM han_member WHERE mb_id = '".$mb_id."' ";
	$row = sql_fetch($sql);
	
	$res_arr["mem_point"] = $row["mb_point"];
	die(json_encode($res_arr));
}




?>