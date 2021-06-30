<?php
$sub_menu = "300160";
/*
print_r($_REQUEST);
foreach($_REQUEST as $key => $value){
	echo $key." = '\".\$$key.\"',";
	echo "<br>";
}
exit;
*/
include_once("./_common.php");
include_once(G5_LIB_PATH."/register.lib.php");
include_once(G5_LIB_PATH.'/thumbnail.lib.php');
/*
if ($w == 'u')
    check_demo();

auth_check($auth[$sub_menu], 'w');

check_admin_token();
*/

$sql = "SELECT COUNT(*) AS cnt FROM han_member WHERE mb_no = '".$mb_no."'";
$row = sql_fetch($sql);

if($row["cnt"] == 1){
$upd_sql = "
						UPDATE 
									han_member 
						SET 
									mb_memo_point = '".$mb_memo_point."'
						WHERE 
									mb_no = '".$mb_no."'";
sql_query($upd_sql);
}


goto_url('./reward_mem_form.php?'.$qstr.'&amp;w=u&amp;mb_no='.$mb_no, false);
?>


    