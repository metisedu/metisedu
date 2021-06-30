<?php
$sub_menu = '100500';
include_once('./_common.php');

check_demo();

if ($W == 'd')
    auth_check($auth[$sub_menu], "d");
else
    auth_check($auth[$sub_menu], "w");

check_admin_token();

@mkdir(G5_DATA_PATH."/banner", G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH."/banner", G5_DIR_PERMISSION);

for($i = 0; $i < count($_POST['bn_id']); $i++){
	$bn_id = (int) $_POST['bn_id'][$i];

	$bn_bimg      = $_FILES['bn_bimg']['tmp_name'][$i];
	$bn_bimg_name = $_FILES['bn_bimg']['name'][$i];

	if ($bn_bimg_del[$i])  @unlink(G5_DATA_PATH."/banner/$bn_id");
	else{
		if ($_FILES['bn_bimg']['name'][$i] && $bn_id){
			@unlink(G5_DATA_PATH."/banner/$bn_id");
		}
	}

	//파일이 이미지인지 체크합니다.
	if( $bn_bimg || $bn_bimg_name ){

		if( !preg_match('/\.(gif|jpe?g|bmp|png)$/i', $bn_bimg_name) ){
			alert("이미지 파일만 업로드 할수 있습니다.");
		}

		$timg = @getimagesize($bn_bimg);
		if ($timg['2'] < 1 || $timg['2'] > 16){
			alert("이미지 파일만 업로드 할수 있습니다.");
		}
	}

	$bn_use = $_POST['bn_use'][$i];
	$bn_order = $_POST['bn_order'][$i];
	$bn_new_win = $_POST['bn_new_win'][$i];
	$bn_position = $_POST['bn_position'][$i];
	$bn_device = $_POST['bn_device'][$i];
	$bn_url = clean_xss_tags($_POST['bn_url'][$i]);
	$bn_alt = $_POST['bn_alt'][$i];
	//$bn_alt = function_exists('clean_xss_attributes') ? clean_xss_attributes(strip_tags($bn_alt)) : strip_tags($bn_alt); // 2019-04-29 줄바꿈 때문에 관리자만 사용 해서 일단 주석

	if($bn_id){ // 수정
		$sql = " update {$g5['g5_shop_banner_table']}
					set bn_alt        = '$bn_alt',
						bn_url        = '$bn_url',
						bn_device     = '$bn_device',
						bn_position   = '$bn_position',
						bn_new_win    = '$bn_new_win',
						bn_order      = '$bn_order',
						bn_use        = '$bn_use'
				  where bn_id = '$bn_id' ";
		//echo"<p>".$sql;
		sql_query($sql);
	}else{ // 등록
		if($bn_alt != ""){
			sql_query(" alter table {$g5['g5_shop_banner_table']} auto_increment=1 ");

			$sql = " insert into {$g5['g5_shop_banner_table']}
						set bn_alt        = '$bn_alt',
							bn_url        = '$bn_url',
							bn_device     = '$bn_device',
							bn_position   = '$bn_position',
							bn_new_win    = '$bn_new_win',
							bn_time       = '$now',
							bn_hit        = '0',
							bn_order      = '$bn_order',
							bn_use        = '$bn_use' ";
			//echo"<p>".$sql;
			sql_query($sql);

			$bn_id = sql_insert_id();
		}
	}

	if ($_FILES['bn_bimg']['name'][$i]){
		upload_file($_FILES['bn_bimg']['tmp_name'][$i], $bn_id, G5_DATA_PATH."/banner");
	}
}

goto_url("./banner_main.php");
?>
