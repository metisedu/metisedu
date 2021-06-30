<?php
$sub_menu = '100500';
include_once('./_common.php');

check_demo();

if ($W == 'd')
    auth_check($auth[$sub_menu], "d");
else
    auth_check($auth[$sub_menu], "w");

check_admin_token();

@mkdir(G5_DATA_PATH."/movie", G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH."/movie", G5_DIR_PERMISSION);

$mv_bimg1      = $_FILES['mv_bimg1']['tmp_name'];
$mv_bimg1_name = $_FILES['mv_bimg1']['name'];

if ($mv_bimg_del1)  @unlink(G5_DATA_PATH."/movie/1");

//파일이 이미지인지 체크합니다.
if( $mv_bimg1 || $mv_bimg1_name ){

	if( !preg_match('/\.(gif|jpe?g|bmp|png)$/i', $mv_bimg1_name) ){
		alert("이미지 파일만 업로드 할수 있습니다.");
	}

	$timg = @getimagesize($mv_bimg1);
	if ($timg['2'] < 1 || $timg['2'] > 16){
		alert("이미지 파일만 업로드 할수 있습니다.");
	}
}

$mv_movie_url = clean_xss_tags($mv_movie_url);
$mv_url1 = clean_xss_tags($mv_url1);
//$mv_alt = function_exists('clean_xss_attributes') ? clean_xss_attributes(strip_tags($mv_alt)) : strip_tags($mv_alt); // 2019-04-29 줄바꿈 때문에 관리자만 사용 해서 일단 주석

$sql = " update han_shop_banner_etc
			set mv_movie_url   = '$mv_movie_url',
				mv_url1        = '$mv_url1',
				mv_subject1    = '$mv_subject1',
				mv_content1    = '$mv_content1'
		  where 1 ";
sql_query($sql);

if ($_FILES['mv_bimg1']['name']) upload_file($_FILES['mv_bimg1']['tmp_name'], '1', G5_DATA_PATH."/movie");

goto_url("./banner_main_movie.php");
?>
