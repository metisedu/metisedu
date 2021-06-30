<?php
$sub_menu = "300150";
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
if ($w == 'u')
    check_demo();

auth_check($auth[$sub_menu], 'w');

check_admin_token();

$sql = "SELECT COUNT(*) AS cnt FROM han_write_tutor WHERE wr_id = '".$wr_id."'";
$row = sql_fetch($sql);

if($row["cnt"] == 1){
	$upd_sql = "
						UPDATE 
									han_write_tutor 
						SET 
									wr_subject = '".$wr_subject."',
									wr_content = '".$wr_content."',
									wr_1 = '".$wr_1."',
									wr_good = '".$wr_good."',
									wr_nogood = '".$wr_nogood."',
									wr_2 = '".$wr_2."',
									wr_3 = '".$wr_3."',
									wr_link1 = '".$wr_link1."'
						WHERE 
									wr_id = '".$wr_id."'";

	sql_query($upd_sql);
}


goto_url('./tutor_form.php?'.$qstr.'&amp;w=u&amp;wr_id='.$wr_id, false);
?>