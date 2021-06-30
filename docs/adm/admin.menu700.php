<?php
$menu['menu700'] = array (
    array('700000', '공지사항', ''.G5_ADMIN_URL.'/board_list.php', 'board'),
	array('700200', '공지사항 게시판', G5_ADMIN_URL.'/bbs/board.php?bo_table=notice', 'news'),
);

if($test_mode){
	array_push($menu['menu700'], array('', '== 개발자영역 ==','#null', '', 1));
	array_push($menu['menu700'], array('700100', '게시판관리', ''.G5_ADMIN_URL.'/board_list.php', 'bbs_board'));
}
?>