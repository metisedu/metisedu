<?php
$sub_menu = '100500';
include_once('./_common.php');

check_demo();

if ($W == 'd')
    auth_check($auth[$sub_menu], "d");
else
    auth_check($auth[$sub_menu], "w");

check_admin_token();

@mkdir(G5_DATA_PATH."/review", G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH."/review", G5_DIR_PERMISSION);

$bn_bimg1      = $_FILES['bn_bimg1']['tmp_name'];
$bn_bimg1_name = $_FILES['bn_bimg1']['name'];
$bn_bimg2      = $_FILES['bn_bimg2']['tmp_name'];
$bn_bimg2_name = $_FILES['bn_bimg2']['name'];
$bn_bimg3      = $_FILES['bn_bimg3']['tmp_name'];
$bn_bimg3_name = $_FILES['bn_bimg3']['name'];
$bn_bimg4      = $_FILES['bn_bimg4']['tmp_name'];
$bn_bimg4_name = $_FILES['bn_bimg4']['name'];

if ($bn_bimg_del1)  @unlink(G5_DATA_PATH."/review/1");
if ($bn_bimg_del2)  @unlink(G5_DATA_PATH."/review/2");
if ($bn_bimg_del3)  @unlink(G5_DATA_PATH."/review/3");
if ($bn_bimg_del4)  @unlink(G5_DATA_PATH."/review/4");

//파일이 이미지인지 체크합니다.
if( $bn_bimg1 || $bn_bimg1_name ){

	if( !preg_match('/\.(gif|jpe?g|bmp|png)$/i', $bn_bimg1_name) ){
		alert("이미지 파일만 업로드 할수 있습니다.");
	}

	$timg = @getimagesize($bn_bimg1);
	if ($timg['2'] < 1 || $timg['2'] > 16){
		alert("이미지 파일만 업로드 할수 있습니다.");
	}
}
if( $bn_bimg2 || $bn_bimg2_name ){

	if( !preg_match('/\.(gif|jpe?g|bmp|png)$/i', $bn_bimg2_name) ){
		alert("이미지 파일만 업로드 할수 있습니다.");
	}

	$timg = @getimagesize($bn_bimg2);
	if ($timg['2'] < 1 || $timg['2'] > 16){
		alert("이미지 파일만 업로드 할수 있습니다.");
	}
}
if( $bn_bimg3 || $bn_bimg3_name ){

	if( !preg_match('/\.(gif|jpe?g|bmp|png)$/i', $bn_bimg3_name) ){
		alert("이미지 파일만 업로드 할수 있습니다.");
	}

	$timg = @getimagesize($bn_bimg3);
	if ($timg['2'] < 1 || $timg['2'] > 16){
		alert("이미지 파일만 업로드 할수 있습니다.");
	}
}
if( $bn_bimg4 || $bn_bimg4_name ){

	if( !preg_match('/\.(gif|jpe?g|bmp|png)$/i', $bn_bimg4_name) ){
		alert("이미지 파일만 업로드 할수 있습니다.");
	}

	$timg = @getimagesize($bn_bimg4);
	if ($timg['2'] < 1 || $timg['2'] > 16){
		alert("이미지 파일만 업로드 할수 있습니다.");
	}
}

$bn_title = addslashes(strip_tags($bn_title));
$bn_url1 = clean_xss_tags($bn_url1);
$bn_url2 = clean_xss_tags($bn_url2);
$bn_url3 = clean_xss_tags($bn_url3);
$bn_url4 = clean_xss_tags($bn_url4);
$bn_job1 = addslashes(strip_tags($bn_job1));
$bn_job2 = addslashes(strip_tags($bn_job2));
$bn_job3 = addslashes(strip_tags($bn_job3));
$bn_job4 = addslashes(strip_tags($bn_job4));
$bn_subject1 = addslashes(strip_tags($bn_subject1));
$bn_subject2 = addslashes(strip_tags($bn_subject2));
$bn_subject3 = addslashes(strip_tags($bn_subject3));
$bn_subject4 = addslashes(strip_tags($bn_subject4));
$bn_content1 = addslashes(strip_tags($bn_content1));
$bn_content2 = addslashes(strip_tags($bn_content2));
$bn_content3 = addslashes(strip_tags($bn_content3));
$bn_content4 = addslashes(strip_tags($bn_content4));
$bn_alt1 = function_exists('clean_xss_attributes') ? clean_xss_attributes(strip_tags($bn_alt1)) : strip_tags($bn_alt1);
$bn_alt2 = function_exists('clean_xss_attributes') ? clean_xss_attributes(strip_tags($bn_alt2)) : strip_tags($bn_alt2);
$bn_alt3 = function_exists('clean_xss_attributes') ? clean_xss_attributes(strip_tags($bn_alt3)) : strip_tags($bn_alt3);
$bn_alt4 = function_exists('clean_xss_attributes') ? clean_xss_attributes(strip_tags($bn_alt4)) : strip_tags($bn_alt4);

$sql = " update han_shop_banner_etc
			set bn_title       = '$bn_title',
				bn_alt1        = '$bn_alt1',
				bn_alt2        = '$bn_alt2',
				bn_alt3        = '$bn_alt3',
				bn_alt4        = '$bn_alt4',
				bn_job1        = '$bn_job1',
				bn_job2        = '$bn_job2',
				bn_job3        = '$bn_job3',
				bn_job4        = '$bn_job4',
				bn_subject1    = '$bn_subject1',
				bn_subject2    = '$bn_subject2',
				bn_subject3    = '$bn_subject3',
				bn_subject4    = '$bn_subject4',
				bn_content1    = '$bn_content1',
				bn_content2    = '$bn_content2',
				bn_content3    = '$bn_content3',
				bn_content4    = '$bn_content4',
				bn_url1        = '$bn_url1',
				bn_url2        = '$bn_url2',
				bn_url3        = '$bn_url3',
				bn_url4        = '$bn_url4'
		  where 1 ";
sql_query($sql);

if ($_FILES['bn_bimg1']['name']) upload_file($_FILES['bn_bimg1']['tmp_name'], '1', G5_DATA_PATH."/review");
if ($_FILES['bn_bimg2']['name']) upload_file($_FILES['bn_bimg2']['tmp_name'], '2', G5_DATA_PATH."/review");
if ($_FILES['bn_bimg3']['name']) upload_file($_FILES['bn_bimg3']['tmp_name'], '3', G5_DATA_PATH."/review");
if ($_FILES['bn_bimg4']['name']) upload_file($_FILES['bn_bimg4']['tmp_name'], '4', G5_DATA_PATH."/review");

goto_url("./banner_main_review.php");
?>
