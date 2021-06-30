<?php
$sub_menu = '100500';
include_once('./_common.php');

check_demo();

if ($W == 'd')
    auth_check($auth[$sub_menu], "d");
else
    auth_check($auth[$sub_menu], "w");

check_admin_token();

@mkdir(G5_DATA_PATH."/bottom", G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH."/bottom", G5_DIR_PERMISSION);

$bt_bimg1      = $_FILES['bt_bimg1']['tmp_name'];
$bt_bimg1_name = $_FILES['bt_bimg1']['name'];
$bt_bimg2      = $_FILES['bt_bimg2']['tmp_name'];
$bt_bimg2_name = $_FILES['bt_bimg2']['name'];
$bt_bimg3      = $_FILES['bt_bimg3']['tmp_name'];
$bt_bimg3_name = $_FILES['bt_bimg3']['name'];

if ($bt_bimg_del1)  @unlink(G5_DATA_PATH."/bottom/1");
if ($bt_bimg_del2)  @unlink(G5_DATA_PATH."/bottom/2");
if ($bt_bimg_del3)  @unlink(G5_DATA_PATH."/bottom/3");

//파일이 이미지인지 체크합니다.
if( $bt_bimg1 || $bt_bimg1_name ){

	if( !preg_match('/\.(gif|jpe?g|bmp|png)$/i', $bt_bimg1_name) ){
		alert("이미지 파일만 업로드 할수 있습니다.");
	}

	$timg = @getimagesize($bt_bimg1);
	if ($timg['2'] < 1 || $timg['2'] > 16){
		alert("이미지 파일만 업로드 할수 있습니다.");
	}
}
if( $bt_bimg2 || $bt_bimg2_name ){

	if( !preg_match('/\.(gif|jpe?g|bmp|png)$/i', $bt_bimg2_name) ){
		alert("이미지 파일만 업로드 할수 있습니다.");
	}

	$timg = @getimagesize($bt_bimg2);
	if ($timg['2'] < 1 || $timg['2'] > 16){
		alert("이미지 파일만 업로드 할수 있습니다.");
	}
}
if( $bt_bimg3 || $bt_bimg3_name ){

	if( !preg_match('/\.(gif|jpe?g|bmp|png)$/i', $bt_bimg3_name) ){
		alert("이미지 파일만 업로드 할수 있습니다.");
	}

	$timg = @getimagesize($bt_bimg3);
	if ($timg['2'] < 1 || $timg['2'] > 16){
		alert("이미지 파일만 업로드 할수 있습니다.");
	}
}

$bt_title = addslashes(strip_tags($bt_title));
$bt_url1 = clean_xss_tags($bt_url1);
$bt_url2 = clean_xss_tags($bt_url2);
$bt_url3 = clean_xss_tags($bt_url3);
$bt_url4 = clean_xss_tags($bt_url4);
//$bt_alt = function_exists('clean_xss_attributes') ? clean_xss_attributes(strip_tags($bt_alt)) : strip_tags($bt_alt); // 2019-04-29 줄바꿈 때문에 관리자만 사용 해서 일단 주석

$sql = " update han_shop_banner_etc
			set bt_title       = '$bt_title',
				bt_alt1        = '$bt_alt1',
				bt_alt2        = '$bt_alt2',
				bt_alt3        = '$bt_alt3',
				bt_url1        = '$bt_url1',
				bt_url2        = '$bt_url2',
				bt_url3        = '$bt_url3'
		  where 1 ";
sql_query($sql);

if ($_FILES['bt_bimg1']['name']) upload_file($_FILES['bt_bimg1']['tmp_name'], '1', G5_DATA_PATH."/bottom");
if ($_FILES['bt_bimg2']['name']) upload_file($_FILES['bt_bimg2']['tmp_name'], '2', G5_DATA_PATH."/bottom");
if ($_FILES['bt_bimg3']['name']) upload_file($_FILES['bt_bimg3']['tmp_name'], '3', G5_DATA_PATH."/bottom");

goto_url("./banner_main_bottom.php");
?>
