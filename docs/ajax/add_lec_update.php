<?php
include_once("./_common.php");

// order id 설정
$sw_direct = "1";
set_cart_id($sw_direct);

$od_id = get_session('ss_cart_id');
$od_id = ck_od_id($od_id);

$sQuery = " SELECT *
			FROM han_member
			WHERE mb_id = '".$mb_id."'
			";
$mb = sql_fetch($sQuery);

if(!$mb){
	echo"nomember";
	exit;
}

$sQuery = " SELECT *
			FROM han_shop_item
			WHERE it_id = '".$it_id."'
			";
$it = sql_fetch($sQuery);

if(!$it){
	echo"noitem";
	exit;
}

// 장바구니에 Insert
$ct_select = 1;
$ct_select_time = G5_TIME_YMDHIS;
$ct_qty = 1;
$remote_addr = get_real_client_ip();
$od_status = "입금";

$sql = " INSERT INTO {$g5['g5_shop_cart_table']} (
				od_id,
				mb_id,
				it_id,
				it_name,
				it_sc_type,
				it_sc_method,
				it_sc_price,
				it_sc_minimum,
				it_sc_qty,
				ct_status,
				ct_price,
				ct_point,
				ct_point_use,
				ct_stock_use,
				ct_option,
				ct_qty,
				ct_notax,
				io_id,
				io_type,
				io_price,
				ct_time,
				ct_ip,
				ct_send_cost,
				ct_direct,
				ct_select,
				ct_select_time,
				ct_start_date,
				ct_end_date
			) VALUES (
				'$od_id',
				'{$mb_id}',
				'{$it['it_id']}',
				'".addslashes($it['it_name'])."',
				'{$it['it_sc_type']}',
				'{$it['it_sc_method']}',
				'{$it['it_sc_price']}',
				'{$it['it_sc_minimum']}',
				'{$it['it_sc_qty']}',
				'{$od_status}',
				'{$it['it_price']}',
				'$point',
				'0',
				'0',
				'$io_value',
				'$ct_qty',
				'{$it['it_notax']}',
				'$io_id',
				'$io_type',
				'$io_price',
				'".G5_TIME_YMDHIS."',
				'$remote_addr',
				'$ct_send_cost',
				'$sw_direct',
				'$ct_select',
				'$ct_select_time',
				'$fr_date',
				'$to_date'
			)";
$rst = sql_query($sql);

if(!$rst){
	echo"nocart";
	exit;
}

$od_email         = $mb['mb_email'];
$od_name          = $mb['mb_name'];
$od_tel           = $mb['mb_tel'];
$od_hp            = $mb['mb_hp'];
$od_zip           = preg_replace('/[^0-9]/', '', $mb['mb_zip']);
$od_zip1          = substr($od_zip, 0, 3);
$od_zip2          = substr($od_zip, 3);
$od_addr1         = clean_xss_tags($mb['mb_addr1']);
$od_addr2         = clean_xss_tags($mb['mb_addr2']);
$od_addr3         = clean_xss_tags($mb['mb_addr3']);
$od_addr_jibeon   = preg_match("/^(N|R)$/", $mb['mb_addr_jibeon']) ? $mb['mb_addr_jibeon'] : '';
$od_b_name        = clean_xss_tags($mb['mb_name']);
$od_b_tel         = clean_xss_tags($mb['mb_tel']);
$od_b_hp          = clean_xss_tags($mb['mb_hp']);
$od_b_addr1       = clean_xss_tags($mb['mb_addr1']);
$od_b_addr2       = clean_xss_tags($mb['mb_addr2']);
$od_b_addr3       = clean_xss_tags($mb['mb_addr3']);
$od_b_addr_jibeon = preg_match("/^(N|R)$/", $mb['mb_addr_jibeon']) ? $mb['mb_addr_jibeon'] : '';
$od_memo          = "관리자 무료 등록";
$od_deposit_name  = "";
$od_tax_flag      = $default['de_tax_flag_use'];
$od_pwd			  = $mb['mb_password'];

$od_settle_case = "무통장";
$tot_ct_price = $it['it_price'];
$od_receipt_price = 0;
$od_misu = $it['it_price'];
$od_receipt_time = G5_TIME_YMDHIS;

// 주문서에 입력
$sql = " insert {$g5['g5_shop_order_table']}
            set od_id             = '$od_id',
                mb_id             = '{$mb_id}',
                od_pwd            = '$od_pwd',
                od_name           = '$od_name',
                od_email          = '$od_email',
                od_tel            = '$od_tel',
                od_hp             = '$od_hp',
                od_zip1           = '$od_zip1',
                od_zip2           = '$od_zip2',
                od_addr1          = '$od_addr1',
                od_addr2          = '$od_addr2',
                od_addr3          = '$od_addr3',
                od_addr_jibeon    = '$od_addr_jibeon',
                od_b_name         = '$od_b_name',
                od_b_tel          = '$od_b_tel',
                od_b_hp           = '$od_b_hp',
                od_b_zip1         = '$od_b_zip1',
                od_b_zip2         = '$od_b_zip2',
                od_b_addr1        = '$od_b_addr1',
                od_b_addr2        = '$od_b_addr2',
                od_b_addr3        = '$od_b_addr3',
                od_b_addr_jibeon  = '$od_b_addr_jibeon',
                od_deposit_name   = '$od_deposit_name',
                od_memo           = '$od_memo',
                od_cart_count     = '$cart_count',
                od_cart_price     = '$tot_ct_price',
                od_cart_coupon    = '$tot_it_cp_price',
                od_send_cost      = '$od_send_cost',
                od_send_coupon    = '$tot_sc_cp_price',
                od_send_cost2     = '$od_send_cost2',
                od_coupon         = '$tot_od_cp_price',
                od_receipt_price  = '$od_receipt_price',
                od_receipt_point  = '$od_receipt_point',
                od_bank_account   = '$od_bank_account',
                od_receipt_time   = '$od_receipt_time',
                od_misu           = '$od_misu',
                od_pg             = '$od_pg',
                od_tno            = '$od_tno',
                od_app_no         = '$od_app_no',
                od_escrow         = '$od_escrow',
                od_tax_flag       = '$od_tax_flag',
                od_tax_mny        = '$od_tax_mny',
                od_vat_mny        = '$od_vat_mny',
                od_free_mny       = '$od_free_mny',
                od_status         = '$od_status',
                od_shop_memo      = '',
                od_hope_date      = '$od_hope_date',
                od_time           = '".G5_TIME_YMDHIS."',
                od_ip             = '$REMOTE_ADDR',
                od_settle_case    = '$od_settle_case',
                od_test           = '{$default['de_card_test']}',
				od_start_date     = '{$fr_date}',
				od_end_date       = '{$to_date}',
				od_between_day    = '{$between_day}'
                ";
$result = sql_query($sql, false);

if(!$result){
	echo"noinsert";
	exit;
}

// 주문번호제거
set_session('ss_order_id', '');
?>