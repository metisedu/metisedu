<?php
$sub_menu = '100500';
include_once('./_common.php');

check_demo();

if ($W == 'd')
    auth_check($auth[$sub_menu], "d");
else
    auth_check($auth[$sub_menu], "w");

check_admin_token();

for($i = 0; $i < count($_POST['ca_no']); $i++){
	$ca_no = (int) $_POST['ca_no'][$i];

	$ca_id			= $_POST['ca_id'][$i];
	$ca_position	= $_POST['ca_position'][$i];
	$ca_title		= addslashes(strip_tags($_POST['ca_title'][$i]));
	$ca_s_title		= addslashes(strip_tags($_POST['ca_s_title'][$i]));
	$ca_item_code1	= $_POST['ca_item_code1'][$i];
	$ca_item_code2	= $_POST['ca_item_code2'][$i];
	$ca_item_code3	= $_POST['ca_item_code3'][$i];
	$ca_item_code4	= $_POST['ca_item_code4'][$i];
	$ca_item_code5	= $_POST['ca_item_code5'][$i];
	$ca_item_code6	= $_POST['ca_item_code6'][$i];
	$ca_item_code7	= $_POST['ca_item_code7'][$i];
	$ca_item_code8	= $_POST['ca_item_code8'][$i];
	$ca_color		= $_POST['ca_color'][$i];

	if($ca_no){ // 수정
		$sql = " update han_shop_banner_cate
					set ca_position  = '".$ca_position."'
					,	ca_title = '".$ca_title."'
					,	ca_s_title = '".$ca_s_title."'
					,	ca_id = '".$ca_id."'
					,	ca_item_code1 = '".$ca_item_code1."'
					,	ca_item_code2 = '".$ca_item_code2."'
					,	ca_item_code3 = '".$ca_item_code3."'
					,	ca_item_code4 = '".$ca_item_code4."'
					,	ca_item_code5 = '".$ca_item_code5."'
					,	ca_item_code6 = '".$ca_item_code6."'
					,	ca_item_code7 = '".$ca_item_code7."'
					,	ca_item_code8 = '".$ca_item_code8."'
					,	ca_color = '".$ca_color."'
					,	ca_update_time = '".date("Y-m-d H:i:s")."'
					,	ca_ip = '".$_SERVER['REMOTE_ADDR']."'
				  where ca_no = '$ca_no' ";
		//echo"<p>".$sql;
		sql_query($sql);
	}else{ // 등록
		if($ca_title != ""){
			sql_query(" alter table han_shop_banner_cate auto_increment=1 ");

			$sql = " insert han_shop_banner_cate
						set ca_position  = '".$ca_position."'
						,	ca_title = '".$ca_title."'
						,	ca_s_title = '".$ca_s_title."'
						,	ca_id = '".$ca_id."'
						,	ca_item_code1 = '".$ca_item_code1."'
						,	ca_item_code2 = '".$ca_item_code2."'
						,	ca_item_code3 = '".$ca_item_code3."'
						,	ca_item_code4 = '".$ca_item_code4."'
						,	ca_item_code5 = '".$ca_item_code5."'
						,	ca_item_code6 = '".$ca_item_code6."'
						,	ca_item_code7 = '".$ca_item_code7."'
						,	ca_item_code8 = '".$ca_item_code8."'
						,	ca_color = '".$ca_color."'
						,	ca_time = '".date("Y-m-d H:i:s")."'
						,	ca_update_time = '".date("Y-m-d H:i:s")."'
						,	ca_ip = '".$_SERVER['REMOTE_ADDR']."'
					";
			//echo"<p>".$sql;
			sql_query($sql);
		}
	}

	sql_query("UPDATE han_shop_item SET it_order = '0' WHERE 1=1 ");

	$ca_order = 9;
	for($j = 1; $j <= 8; $j++){
		$ca_item_code = "ca_item_code".$j;
		if(!$$ca_item_code)
			continue;

		$sQuery = " UPDATE han_shop_item
					 SET it_order = '".($ca_order - $j)."'
					WHERE it_id = '".$$ca_item_code."'
					";
		sql_query($sQuery);
	}
}

goto_url("./banner_category_list.php");
?>
