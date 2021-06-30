<?php

include_once("./_common.php");

auth_check($auth[$sub_menu], "w");



if($w == "u" && $wr_id){
	$sql = "DELETE FROM han_write_tutor WHERE wr_id = '".$wr_id."' ";
	sql_query($sql);
	alert("삭제되었습니다.", "./han_write_tutor.php?".$qstr."&amp;w=u&amp");
}



?>


    