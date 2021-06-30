<?php
$sub_menu = "100200";
include_once('./_common.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

$sQuery = " SELECT *
			FROM han_member
			WHERE mb_email = '".$mb_id."'
			";
$mail = sql_fetch($sQuery);

if($mail){
	$mb_id = $mail['mb_id'];
}

$mb = get_member($mb_id);
if (!$mb['mb_id'])
    alert('존재하는 회원아이디가 아닙니다.');
/*
check_admin_token();
*/
if($_POST['g']){ // 강사등록
	$sQuery = " UPDATE han_member
					SET mb_level = '3'
					,	mb_auth = '".$_POST['g']."'
				WHERE mb_id = '".$mb['mb_id']."' ";
	sql_query($sQuery);
}else{
	$sQuery = " DELETE
				FROM {$g5['auth_table']}
				WHERE mb_id = '{$mb['mb_id']}'
				";
	sql_query($sQuery);

	for($i = 0; $i < count($auth); $i++){
		$sql = " insert into {$g5['auth_table']}
				set mb_id   = '{$mb['mb_id']}',
					au_menu = '".$auth[$i]."',
					au_auth = 'r,w,d' ";
		$result = sql_query($sql, FALSE);

		if (!$result) {
			$sql = " update {$g5['auth_table']}
						set au_auth = 'r,w,d'
					  where mb_id   = '{$mb['mb_id']}'
						and au_menu = '".$auth[$i]."' ";
			sql_query($sql);
		}
	}

	// 관리자 권한으로 변경
	$sQuery = " UPDATE han_member
				SET mb_level = '10'
				WHERE mb_id = '".$mb['mb_id']."'
				";
	sql_query($sQuery);
}

//sql_query(" OPTIMIZE TABLE `$g5['auth_table']` ");

goto_url('./auth_list2.php?'.$qstr);
?>
