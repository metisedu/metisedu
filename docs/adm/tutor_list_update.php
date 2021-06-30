<?php
$sub_menu = "300150";
include_once('./_common.php');


check_demo();


auth_check($auth[$sub_menu], 'w');

check_admin_token();

$mb_datas = array();

if ($_POST['act_button'] == "선택삭제") {
	
	if (!count($_POST['chk'])) {
		alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
	}

    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
		
		$sql = "DELETE FROM han_write_tutor WHERE wr_id = '".$wr_id[$k]."' ";
		sql_query($sql);
    }
}

if ($_POST['act_button'] == "전체삭제") {
	$sql = "DELETE FROM han_write_tutor ";
	sql_query($sql);
}

goto_url('./han_write_tutor.php?'.$qstr);
?>
