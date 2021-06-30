<?php
$menu['menu100'] = array (
    array('100000', '사이트관리', G5_ADMIN_URL.'/config_form.php',   'config'),
    array('100100', '사이트관리', G5_ADMIN_URL.'/config_form.php',   'cf_basic'),
	array('100240', '기타데이터 관리', G5_ADMIN_URL.'/contentlist.php', 'scf_contents', 1),
	array('100200', '운영자 등록', G5_ADMIN_URL.'/auth_list2.php',     'cf_auth'),
	array('100210', '강사 등록', G5_ADMIN_URL.'/auth_list3.php',     'cf_auth'),
	array('100310', '카테고리 관리', G5_ADMIN_URL.'/shop_admin/categorylist.php', 'scf_cate'),
	array('100500', '배너관리', G5_ADMIN_URL.'/shop_admin/banner_main.php', 'scf_banner', 1),
	array('100290', '상단메뉴설정', G5_ADMIN_URL.'/menu_list.php',     'cf_menu', 1),
	array('100700', 'FAQ관리', G5_ADMIN_URL.'/faqmasterlist.php', 'scf_faq', 1),
	array('100800', '인지경로 관리', G5_ADMIN_URL.'/routelist.php', 'scf_faq', 1)

    /*array('100200', '운영자 등록', G5_ADMIN_URL.'/auth_list.php',     'cf_auth')*/
);

if($test_mode){
	array_push($menu['menu100'], array('', '== 개발자영역 ==','#null', '', 1));
	array_push($menu['menu100'], array('100280', '테마설정', G5_ADMIN_URL.'/theme.php',     'cf_theme', 1));

	/*array_push($menu['menu100'], array('100290', '메뉴설정', G5_ADMIN_URL.'/menu_list.php',     'cf_menu', 1));*/
	array_push($menu['menu100'], array('100300', '메일 테스트', G5_ADMIN_URL.'/sendmail_test.php', 'cf_mailtest'));
	array_push($menu['menu100'], array('100310', '팝업레이어관리', G5_ADMIN_URL.'/newwinlist.php', 'scf_poplayer'));

	array_push($menu['menu100'], array('100800', '세션파일 일괄삭제',G5_ADMIN_URL.'/session_file_delete.php', 'cf_session', 1));
    array_push($menu['menu100'], array('100900', '캐시파일 일괄삭제',G5_ADMIN_URL.'/cache_file_delete.php',   'cf_cache', 1));
    array_push($menu['menu100'], array('100910', '캡챠파일 일괄삭제',G5_ADMIN_URL.'/captcha_file_delete.php',   'cf_captcha', 1));
    array_push($menu['menu100'], array('100920', '썸네일파일 일괄삭제',G5_ADMIN_URL.'/thumbnail_file_delete.php',   'cf_thumbnail', 1));
    array_push($menu['menu100'], array('100500', 'phpinfo()',        G5_ADMIN_URL.'/phpinfo.php',       'cf_phpinfo'));

	/*
	if(version_compare(phpversion(), '5.3.0', '>=') && defined('G5_BROWSCAP_USE') && G5_BROWSCAP_USE) {
		$menu['menu100'][] = array('100510', 'Browscap 업데이트', G5_ADMIN_URL.'/browscap.php', 'cf_browscap');
		$menu['menu100'][] = array('100520', '접속로그 변환', G5_ADMIN_URL.'/browscap_convert.php', 'cf_visit_cnvrt');
	}

	$menu['menu100'][] = array('100410', 'DB업그레이드', G5_ADMIN_URL.'/dbupgrade.php', 'db_upgrade');
	$menu['menu100'][] = array('100400', '부가서비스', G5_ADMIN_URL.'/service.php', 'cf_service');
	*/
}
?>