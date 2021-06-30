<?php
include_once("./_common.php");

$sQuery = " UPDATE {$g5['member_table']}
			SET mb_profile = '{$_POST['mb_profile']}'
			,	mb_interest1 = '{$_POST['mb_interest1']}'
			,	mb_interest2 = '{$_POST['mb_interest2']}'
			,	mb_interest3 = '{$_POST['mb_interest3']}'
			WHERE mb_id = '".$member['mb_id']."'
			";
sql_query($sQuery);

alert('수정 되었습니다.', G5_SHOP_URL.'/profile/');
?>