<?php
$sub_menu = "200100";

include_once("./_common.php");
include_once(G5_LIB_PATH."/register.lib.php");
include_once(G5_LIB_PATH.'/thumbnail.lib.php');
/*
if ($w == 'u')
    check_demo();

auth_check($auth[$sub_menu], 'w');

check_admin_token();
*/

$sql = "SELECT COUNT(*) AS cnt FROM han_shop_item WHERE it_id = '".$it_id."'";
$row = sql_fetch($sql);


if($row["cnt"] == 1){
				
				$upd_sql = "UPDATE han_shop_item SET it_1 = '".$wr_id."' WHERE it_id = '".$it_id."'";
				sql_query($upd_sql);
				
				// 상품과 연동되어 있는 기존 튜터 영상 it_id 값을 지움
				$upd_sql = "UPDATE han_write_tutor SET wr_10 = '' WHERE wr_10 = '".$it_id."'";
				sql_query($upd_sql);
				
				// 새로 선택한 튜터 영상에 it_id 값을 대입
				$upd_sql = "UPDATE han_write_tutor SET wr_10 = '".$it_id."' WHERE wr_id = '".$wr_id."' ";
				sql_query($upd_sql);

}


alert("완료 되었습니다.", './pop_vimeo.php?'.$qstr.'&amp;w=u&amp;it_id='.$it_id);

?>


    