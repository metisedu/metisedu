<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/mailer.lib.php');

if ($months != 3 && $months != 6 && $months != 9 && $months != 12) {
    alert("잘못된 요청입니다.");
}

$discount = $months / 3 * 5;

$sql = " insert han_staking
            set mb_id             = '{$member['mb_id']}',
                hs_months         = '$months',
                hs_discount       = '$discount'
                ";
$result = sql_query($sql, false);

if (!$result) {
    alert('결제등록 요청 후 주문해 주십시오.');
} else {
    alert('스테이킹 신청이 완료되었습니다.');
//    goto_url(G5_SHOP_URL.'/orderinquiryview.php?od_id='.$od_id.'&amp;uid='.$uid);
}