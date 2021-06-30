<?php
if (!defined('G5_USE_SHOP') || !G5_USE_SHOP) return;

$menu['menu500'] = array (
    array('500000', '통계', G5_ADMIN_URL.'/shop_admin/itemsellrank.php', 'shop_stats'),
	/*array('500100', '상품판매순위', G5_ADMIN_URL.'/shop_admin/itemsellrank.php', 'sst_rank'),*/
	array('500120', '회원가입통계', G5_ADMIN_URL.'/register_list.php', 'mb_visit', 1),
	array('500110', '매출통계', G5_ADMIN_URL.'/shop_admin/sale_list.php', 'sst_order_stats'),
	/*
	array('500110', '매출통계', G5_ADMIN_URL.'/shop_admin/sale1.php', 'sst_order_stats'),
	*/

);

if($test_mode){
	array_push($menu['menu500'], array('', '== 개발자영역 ==','#null', '', 1));
    array_push($menu['menu500'], array('500100', '상품판매순위', G5_ADMIN_URL.'/shop_admin/itemsellrank.php', 'sst_rank'));
    array_push($menu['menu500'], array('500400', '재입고SMS알림', G5_ADMIN_URL.'/shop_admin/itemstocksms.php', 'sst_stock_sms', 1));
    array_push($menu['menu500'], array('500300', '이벤트관리', G5_ADMIN_URL.'/shop_admin/itemevent.php', 'scf_event'));
    array_push($menu['menu500'], array('500310', '이벤트일괄처리', G5_ADMIN_URL.'/shop_admin/itemeventlist.php', 'scf_event_mng'));
    array_push($menu['menu500'], array('500140', '보관함현황', G5_ADMIN_URL.'/shop_admin/wishlist.php', 'sst_wish'));
    array_push($menu['menu500'], array('500210', '가격비교사이트', G5_ADMIN_URL.'/shop_admin/price.php', 'sst_compare', 1));
}
?>