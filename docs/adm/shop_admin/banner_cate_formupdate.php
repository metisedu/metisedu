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

	$ca_position	= $_POST['ca_position'][$i];
	$ca_title		= addslashes(strip_tags($_POST['ca_title'][$i]));
	$ca_s_title		= addslashes(strip_tags($_POST['ca_s_title'][$i]));
	$ca_url			= clean_xss_tags($_POST['ca_url'][$i]);
	$ca_item_code1	= $_POST['ca_item_code1'][$i];
	$ca_item_code2	= $_POST['ca_item_code2'][$i];
	$ca_item_code3	= $_POST['ca_item_code3'][$i];
	$ca_item_code4	= $_POST['ca_item_code4'][$i];
	$ca_item_order	= $_POST['ca_item_order'][$i];

	if($ca_no){ // 수정
		$sql = " update han_shop_banner_cate
					set ca_position  = '".$ca_position."'
					,	ca_title = '".$ca_title."'
					,	ca_s_title = '".$ca_s_title."'
					,	ca_url = '".$ca_url."'
					,	ca_item_code1 = '".$ca_item_code1."'
					,	ca_item_code2 = '".$ca_item_code2."'
					,	ca_item_code3 = '".$ca_item_code3."'
					,	ca_item_code4 = '".$ca_item_code4."'
					,	ca_item_order = '".$ca_item_order."'
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
						,	ca_url = '".$ca_url."'
						,	ca_item_code1 = '".$ca_item_code1."'
						,	ca_item_code2 = '".$ca_item_code2."'
						,	ca_item_code3 = '".$ca_item_code3."'
						,	ca_item_code4 = '".$ca_item_code4."'
						,	ca_item_order = '".$ca_item_order."'
						,	ca_time = '".date("Y-m-d H:i:s")."'
						,	ca_update_time = '".date("Y-m-d H:i:s")."'
						,	ca_ip = '".$_SERVER['REMOTE_ADDR']."'
					";
			//echo"<p>".$sql;
			sql_query($sql);
		}
	}
}

goto_url("./banner_main_cate.php");
?>
