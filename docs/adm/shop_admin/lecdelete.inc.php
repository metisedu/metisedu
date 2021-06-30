<?php
// itemlistdelete.php 에서 include 하는 파일

if (!defined('_GNUBOARD_')) exit;
if (!defined('_ITEM_DELETE_')) exit; // 개별 페이지 접근 불가

if (!function_exists("itemdelete")) {

    // 상품삭제
    // 메세지출력후 주문개별내역페이지로 이동
    function itemdelete($lt_id)
    {
        global $g5, $is_admin;

        // 상품 삭제
        $sql = " delete from han_shop_lec where lt_id = '$lt_id' ";
        sql_query($sql);
    }
}

itemdelete($lt_id);
?>