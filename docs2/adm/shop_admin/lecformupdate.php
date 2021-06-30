<?php
$sub_menu = '400200';
include_once('./_common.php');

if ($w == "u" || $w == "d")
    check_demo();

if ($w == '' || $w == 'u')
    auth_check($auth[$sub_menu], "w");
else if ($w == 'd')
    auth_check($auth[$sub_menu], "d");

/*
check_admin_token();
*/

@mkdir(G5_DATA_PATH."/lec", G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH."/lec", G5_DIR_PERMISSION);

// input vars 체크
check_input_vars();

$ca_id = isset($ca_id) ? preg_replace('/[^0-9a-z]/i', '', $ca_id) : '';
$ca_id2 = isset($ca_id2) ? preg_replace('/[^0-9a-z]/i', '', $ca_id2) : '';
$ca_id3 = isset($ca_id3) ? preg_replace('/[^0-9a-z]/i', '', $ca_id3) : '';

// 파일정보
if($w == "u") {
    $sql = " select it_img1, it_img2, it_img3, it_img4, it_img5, it_img6, it_img7, it_img8, it_img9, it_img10
                from han_shop_lec
                where lt_id = '$lt_id' ";
    $file = sql_fetch($sql);

    $it_img1    = $file['it_img1'];
    $it_img2    = $file['it_img2'];
    $it_img3    = $file['it_img3'];
    $it_img4    = $file['it_img4'];
    $it_img5    = $file['it_img5'];
    $it_img6    = $file['it_img6'];
    $it_img7    = $file['it_img7'];
    $it_img8    = $file['it_img8'];
    $it_img9    = $file['it_img9'];
    $it_img10   = $file['it_img10'];
}

$it_img_dir = G5_DATA_PATH.'/lec';

if ($w == "" || $w == "u")
{
    // 다음 입력을 위해서 옵션값을 쿠키로 한달동안 저장함
    //@setcookie("ck_ca_id",  $ca_id,  time() + 86400*31, $default[de_cookie_dir], $default[de_cookie_domain]);
    //@setcookie("ck_maker",  stripslashes($it_maker),  time() + 86400*31, $default[de_cookie_dir], $default[de_cookie_domain]);
    //@setcookie("ck_origin", stripslashes($it_origin), time() + 86400*31, $default[de_cookie_dir], $default[de_cookie_domain]);
    @set_cookie("ck_ca_id", $ca_id, time() + 86400*31);
    @set_cookie("ck_ca_id2", $ca_id2, time() + 86400*31);
    @set_cookie("ck_ca_id3", $ca_id3, time() + 86400*31);
    @set_cookie("ck_maker", stripslashes($it_maker), time() + 86400*31);
    @set_cookie("ck_origin", stripslashes($it_origin), time() + 86400*31);
}

// 관련상품을 우선 삭제함
sql_query(" delete from {$g5['g5_shop_item_relation_table']} where it_id = '$lt_id' ");

// 관련상품의 반대도 삭제
sql_query(" delete from {$g5['g5_shop_item_relation_table']} where it_id2 = '$lt_id' ");

// 상품요약정보
$value_array = array();
for($i=0; $i<count($_POST['ii_article']); $i++) {
    $key = $_POST['ii_article'][$i];
    $val = $_POST['ii_value'][$i];
    $value_array[$key] = $val;
}
$it_info_value = addslashes(serialize($value_array));

// 포인트 비율 값 체크
if(($it_point_type == 1 || $it_point_type == 2) && $it_point > 99)
    alert("포인트 비율을 0과 99 사이의 값으로 입력해 주십시오.");

$it_name = strip_tags(trim($_POST['it_name']));

// KVE-2019-0708
$check_sanitize_keys = array(
'it_order',             // 출력순서
'it_maker',             // 제조사
'it_origin',            // 원산지
'it_brand',             // 브랜드
'it_model',             // 모델
'it_tel_inq',           // 전화문의
'it_use',               // 판매가능
'it_nocoupon',          // 쿠폰적용안함
'ec_mall_pid',          // 네이버쇼핑 상품ID
'it_sell_email',        // 판매자 e-mail
'it_price',             // 판매가격
'it_cust_price',        // 시중가격
'it_point_type',        // 포인트 유형
'it_point',             // 포인트
'it_supply_point',      // 추가옵션상품 포인트
'it_soldout',           // 상품품절
'it_stock_sms',         // 재입고SMS 알림
'it_stock_qty',         // 재고수량
'it_noti_qty',          // 재고 통보수량
'it_buy_min_qty',       // 최소구매수량
'it_notax',             // 상품과세 유형
'it_sc_type',           // 배송비 유형
'it_sc_method',         // 배송비 결제
'it_sc_price',          // 기본배송비
'it_sc_minimum',        // 배송비 상세조건
);

foreach( $check_sanitize_keys as $key ){
    $$key = isset($_POST[$key]) ? strip_tags($_POST[$key]) : '';
}

if ($it_name == "")
    alert("강의명을 입력해 주십시오.");

$sql_common = " ca_id               = '$ca_id',
                ca_id2              = '$ca_id2',
                ca_id3              = '$ca_id3',
                mb_id               = '$mb_id',
                it_skin             = '$it_skin',
                it_mobile_skin      = '$it_mobile_skin',
                it_name             = '$it_name',
                it_maker            = '$it_maker',
                it_origin           = '$it_origin',
                it_brand            = '$it_brand',
                it_model            = '$it_model',
                it_option_subject   = '$it_option_subject',
                it_supply_subject   = '$it_supply_subject',
                it_type1            = '$it_type1',
                it_type2            = '$it_type2',
                it_type3            = '$it_type3',
                it_type4            = '$it_type4',
                it_type5            = '$it_type5',
                it_basic            = '$it_basic',
                it_explan           = '$it_explan',
                it_explan2          = '".strip_tags(trim($_POST['it_explan']))."',
                it_mobile_explan    = '$it_mobile_explan',
                it_cust_price       = '$it_cust_price',
                it_price            = '$it_price',
                it_point            = '$it_point',
                it_point_type       = '$it_point_type',
                it_supply_point     = '$it_supply_point',
                it_notax            = '$it_notax',
                it_sell_email       = '$it_sell_email',
                it_use              = '$it_use',
                it_nocoupon         = '$it_nocoupon',
                it_soldout          = '$it_soldout',
                it_stock_qty        = '$it_stock_qty',
                it_stock_sms        = '$it_stock_sms',
                it_noti_qty         = '$it_noti_qty',
                it_sc_type          = '$it_sc_type',
                it_sc_method        = '$it_sc_method',
                it_sc_price         = '$it_sc_price',
                it_sc_minimum       = '$it_sc_minimum',
                it_sc_qty           = '$it_sc_qty',
                it_buy_min_qty      = '$it_buy_min_qty',
                it_buy_max_qty      = '$it_buy_max_qty',
                it_head_html        = '$it_head_html',
                it_tail_html        = '$it_tail_html',
                it_mobile_head_html = '$it_mobile_head_html',
                it_mobile_tail_html = '$it_mobile_tail_html',
                it_ip               = '{$_SERVER['REMOTE_ADDR']}',
                it_order            = '$it_order',
                it_tel_inq          = '$it_tel_inq',
                it_info_gubun       = '$it_info_gubun',
                it_info_value       = '$it_info_value',
                it_shop_memo        = '$it_shop_memo',
                ec_mall_pid         = '$ec_mall_pid',
                it_img1             = '$it_img1',
                it_img2             = '$it_img2',
                it_img3             = '$it_img3',
                it_img4             = '$it_img4',
                it_img5             = '$it_img5',
                it_img6             = '$it_img6',
                it_img7             = '$it_img7',
                it_img8             = '$it_img8',
                it_img9             = '$it_img9',
                it_img10            = '$it_img10',
                it_1_subj           = '$it_1_subj',
                it_2_subj           = '$it_2_subj',
                it_3_subj           = '$it_3_subj',
                it_4_subj           = '$it_4_subj',
                it_5_subj           = '$it_5_subj',
                it_6_subj           = '$it_6_subj',
                it_7_subj           = '$it_7_subj',
                it_8_subj           = '$it_8_subj',
                it_9_subj           = '$it_9_subj',
                it_10_subj          = '$it_10_subj',
                it_1                = '$it_1',
                it_2                = '$it_2',
                it_3                = '$it_3',
                it_4                = '$it_4',
                it_5                = '$it_5',
                it_6                = '$it_6',
                it_7                = '$it_7',
                it_8                = '$it_8',
                it_9                = '$it_9',
                it_10               = '$it_10'
                ";

if ($w == "")
{
    $lt_id = $_POST['lt_id'];

    if (!trim($lt_id)) {
        alert('상품 코드가 없으므로 상품을 추가하실 수 없습니다.');
    }

    $t_lt_id = preg_replace("/[A-Za-z0-9\-_]/", "", $lt_id);
    if($t_lt_id)
        alert('상품 코드는 영문자, 숫자, -, _ 만 사용할 수 있습니다.');

    $sql_common .= " , it_time = '".G5_TIME_YMDHIS."' ";
    $sql_common .= " , it_update_time = '".G5_TIME_YMDHIS."' ";
    $sql = " insert han_shop_lec
                set lt_id = '$lt_id',
					$sql_common	";
    sql_query($sql);
}
else if ($w == "u")
{
    $sql_common .= " , it_update_time = '".G5_TIME_YMDHIS."' ";
    $sql = " update han_shop_lec
                set $sql_common
              where lt_id = '$lt_id' ";
    sql_query($sql);
}

$mv_img_dir = G5_DATA_PATH.'/movie';
// 챕터정보 수정
for($k = 0; $k < count($cp_id); $k++){
	// 이미지업로드
	$cp_img = "";
	if ($_FILES['cp_file']['name'][$k]) {
		$cp_img = it_img_upload($_FILES['cp_file']['tmp_name'][$k], $_FILES['cp_file']['name'][$k], $mv_img_dir.'/'.$it_id);
	}
	if(!$cp_img && $cp_file_name[$k]){
		$cp_img = $cp_file_name[$k];
	}

	if($cp_order[$k] == '') $cp_order_etc = $k;
	else $cp_order_etc = $cp_order[$k];

	$sQuery = " UPDATE han_shop_chapter
					SET cp_name = '".$cp_name[$k]."'
					,	cp_order = '".$cp_order_etc."'
					,	cp_file = '".$cp_img."'
				WHERE cp_id = '".$cp_id[$k]."'
				";
	sql_query($sQuery);
}

// 삭제된 챕터 삭제
$sQuery = " DELETE
			FROM han_shop_chapter
			WHERE cp_id not in (".implode(",", $cp_id).")
			AND   lt_id = '".$lt_id."'
			";
sql_query($sQuery);

// 영상정보 삭제하고 재 등록
$sQuery = " DELETE
			FROM han_shop_movie
			WHERE lt_id = '".$lt_id."'
			";
sql_query($sQuery);

for($i = 0; $i < count($mv_name); $i++){
	// 파일 업로드
	$mv_img = "";
	if ($_FILES['mv_file']['name'][$i]) {
		$mv_img = it_img_upload($_FILES['mv_file']['tmp_name'][$i], $_FILES['mv_file']['name'][$i], $mv_img_dir.'/'.$it_id);
	}
	if(!$mv_img && $mv_file_name[$i]){
		$mv_img = $mv_file_name[$i];
	}

	$add_sql = "";
	if($_FILES['mv_file']['name'][$i] != ""){
		$add_sql = " ,	mv_file_name = '".$_FILES['mv_file']['name'][$i]."' ";
	}

	if($mv_order[$i] == '') $mv_order_etc = $i;
	else $mv_order_etc = $mv_order[$i];

	$sQuery = " INSERT INTO han_shop_movie
					SET lt_id		= '".$lt_id."'
					,	cp_id		= '".$mv_cp_id[$i]."'
					,	mv_name		= '".$mv_name[$i]."'
					,	mv_url		= '".$mv_url[$i]."'
					,	mv_preview	= '".$mv_preview[$i]."'
					,	mv_file		= '".$mv_img."'
					,	mv_time		= '".$mv_time[$i]."'
					,	mv_order	= '".$mv_order_etc."'
					,	mv_use		= '".$mv_use[$i]."'
					,	mv_date		= '".date("Y-m-d H:i:s")."'
					{$add_sql}
				";
	//echo"<p>".$sQuery;
	sql_query($sQuery);
}

/*
else if ($w == "d")
{
    if ($is_admin != 'super')
    {
        $sql = " select lt_id from han_shop_lec a, {$g5['g5_shop_category_table']} b
                  where a.lt_id = '$lt_id'
                    and a.ca_id = b.ca_id
                    and b.ca_mb_id = '{$member['mb_id']}' ";
        $row = sql_fetch($sql);
        if (!$row['lt_id'])
            alert("\'{$member['mb_id']}\' 님께서 삭제 할 권한이 없는 상품입니다.");
    }

    itemdelete($lt_id);
}
*/
/*
if ($w == "" || $w == "u")
{
    // 관련상품 등록
    $lt_id2 = explode(",", $it_list);
    for ($i=0; $i<count($lt_id2); $i++)
    {
        if (trim($lt_id2[$i]))
        {
            $sql = " insert into {$g5['g5_shop_item_relation_table']}
                        set it_id  = '$it_id',
                            it_id2 = '$it_id2[$i]',
                            ir_no = '$i' ";
            sql_query($sql, false);

            // 관련상품의 반대로도 등록
            $sql = " insert into {$g5['g5_shop_item_relation_table']}
                        set it_id  = '$it_id2[$i]',
                            it_id2 = '$it_id',
                            ir_no = '$i' ";
            sql_query($sql, false);
        }
    }
}
*/

run_event('shop_admin_lecformupdate', $lt_id, $w);

$qstr = "$qstr&amp;sca=$sca&amp;page=$page";

if ($w == "u") {
    goto_url("./lecform.php?w=u&amp;lt_id=$lt_id&amp;$qstr");
} else if ($w == "d")  {
    $qstr = "ca_id=$ca_id&amp;sfl=$sfl&amp;sca=$sca&amp;page=$page&amp;stx=".urlencode($stx)."&amp;save_stx=".urlencode($save_stx);
    goto_url("./itemlist.php?$qstr");
}

echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">";
?>
<script>
    if (confirm("계속 입력하시겠습니까?"))
        //location.href = "<?php echo "./lecform.php?lt_id=$lt_id&amp;sort1=$sort1&amp;sort2=$sort2&amp;sel_ca_id=$sel_ca_id&amp;sel_field=$sel_field&amp;search=$search&amp;page=$page"?>";
        location.href = "<?php echo "./lecform.php?".str_replace('&amp;', '&', $qstr); ?>";
    else
        location.href = "<?php echo "./leclist.php?".str_replace('&amp;', '&', $qstr); ?>";
</script>
