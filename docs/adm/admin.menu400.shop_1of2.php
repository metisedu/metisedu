<?php
if (!defined('G5_USE_SHOP') || !G5_USE_SHOP) return;

$menu['menu400'] = array (
    array('400000', '콘텐츠관리', G5_ADMIN_URL.'/shop_admin/', 'shop_config'),
	array('400100', '쇼핑몰설정', G5_ADMIN_URL.'/shop_admin/configform.php', 'scf_config'),
    array('400200', '강의관리', G5_ADMIN_URL.'/shop_admin/leclist.php', 'scf_item'),
	array('400300', '판매용 상품관리', G5_ADMIN_URL.'/shop_admin/itemlist.php', 'scf_item'),

    array('400410', '누락주문건 확인', G5_ADMIN_URL.'/shop_admin/inorderlist.php', 'scf_inorder', 1),
);

if($test_mode){
	array_push($menu['menu400'], array('', '== 개발자영역 ==','#null', '', 1));
	array_push($menu['menu400'], array('400100', '쇼핑몰설정', G5_ADMIN_URL.'/shop_admin/configform.php', 'scf_config'));
	array_push($menu['menu400'], array('400440', '개인결제관리', G5_ADMIN_URL.'/shop_admin/personalpaylist.php', 'scf_personalpay', 1));
	array_push($menu['menu400'], array('400660', '상품문의', G5_ADMIN_URL.'/shop_admin/itemqalist.php', 'scf_item_qna'));
    array_push($menu['menu400'], array('400650', '사용후기', G5_ADMIN_URL.'/shop_admin/itemuselist.php', 'scf_ps'));
    array_push($menu['menu400'], array('400610', '상품유형관리', G5_ADMIN_URL.'/shop_admin/itemtypelist.php', 'scf_item_type'));
    array_push($menu['menu400'], array('400750', '추가배송비관리', G5_ADMIN_URL.'/shop_admin/sendcostlist.php', 'scf_sendcost', 1));
}
?>