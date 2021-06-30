<?php

include_once("./_common.php");

auth_check($auth[$sub_menu], "w");



if($w == "u" && $po_id){
	$sql = "DELETE FROM han_point WHERE po_id = '".$po_id."' ";
	sql_query($sql);
	alert("삭제되었습니다.", "./reward_list.php?".$qstr."&amp;w=u&amp");
}



?>


    