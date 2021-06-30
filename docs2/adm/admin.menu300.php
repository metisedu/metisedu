<?php
$menu['menu300'] = array (
    array('300000', '매출관리', ''.G5_ADMIN_URL.'/board_list.php', 'board'),
	array('300110', '주문내역', G5_ADMIN_URL.'/shop_admin/orderlist.php', 'scf_order', 1),
	array('300120', '주문내역출력', G5_ADMIN_URL.'/shop_admin/orderprint.php', 'sst_print_order', 1),
	array('300130', '환불관리', G5_ADMIN_URL.'/shop_admin/refundlist.php', 'scf_order', 1),
);

if($test_mode){
	array_push($menu['menu300'], array('', '== 개발자영역 ==','#null', '', 1));
	array_push($menu['menu300'], array('300100', '게시판관리', ''.G5_ADMIN_URL.'/board_list.php', 'bbs_board'));
    array_push($menu['menu300'], array('300200', '게시판그룹관리', ''.G5_ADMIN_URL.'/boardgroup_list.php', 'bbs_group'));
    array_push($menu['menu300'], array('300300', '인기검색어관리', ''.G5_ADMIN_URL.'/popular_list.php', 'bbs_poplist', 1));
    array_push($menu['menu300'], array('300400', '인기검색어순위', ''.G5_ADMIN_URL.'/popular_rank.php', 'bbs_poprank', 1));
    array_push($menu['menu300'], array('300500', '1:1문의설정', ''.G5_ADMIN_URL.'/qa_config.php', 'qa'));
    array_push($menu['menu300'], array('300820', '글,댓글 현황', G5_ADMIN_URL.'/write_count.php', 'scf_write_count'));
}
?>