<?php
include_once('./_common.php');
define('_NOHEADER_',true);

if (isset($_SESSION['ss_mb_reg']))
    $mb = get_member($_SESSION['ss_mb_reg']);
/*
// 회원정보가 없다면 초기 페이지로 이동
if (!$mb['mb_id'])
    goto_url(G5_URL);
*/
$g5['title'] = 'Membership';
include_once('./_head.php');
include_once($member_skin_path.'/register_result.skin.php');
include_once('./_tail.php');
?>