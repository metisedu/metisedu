<?php
$menu['menu600'] = array (
    array('600000', '쿠폰관리', ''.G5_ADMIN_URL.'/board_list.php', 'board'),
	array('600100', '쿠폰관리', G5_ADMIN_URL.'/shop_admin/couponlist.php', 'scf_coupon'),
	/*
    array('600110', '쿠폰존관리', G5_ADMIN_URL.'/shop_admin/couponzonelist.php', 'scf_coupon_zone'),
	*/
);

if($test_mode){
	array_push($menu['menu600'], array('', '== 개발자영역 ==','#null', '', 1));
	array_push($menu['menu600'], array('600120', '난수쿠폰관리', G5_ADMIN_URL.'/shop_admin/couponlist2.php', 'scf_coupon2'));
}
?>