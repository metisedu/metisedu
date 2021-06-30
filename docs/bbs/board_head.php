<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
define('_NOHEADER_',true);
// 게시판 관리의 상단 내용
if (G5_IS_MOBILE) {
    // 모바일의 경우 설정을 따르지 않는다.
    include_once(G5_BBS_PATH.'/_head.php');
    echo html_purifier(stripslashes($board['bo_mobile_content_head']));
} else {
    if(is_include_path_check($board['bo_include_head'])) {  //파일경로 체크
        @include ($board['bo_include_head']);
    } else {    //파일경로가 올바르지 않으면 기본파일을 가져옴
        include_once(G5_BBS_PATH.'/_head.php');
    }
    echo html_purifier(stripslashes($board['bo_content_head']));
}
?>
<section id="sub_community">
	<div class="container">
		<div class="row">
			<?php
			include_once(G5_THEME_PATH.'/community.left.php');
			?>
			
			<div class="col-md-9">
				<div class="community">
					<div class="community-tit">
						<?php echo $board['bo_subject']?>				
					</div>
					
					<div class="community-wrap">
						<div class="row add_bottom_30">
							<div class="col-md-12">