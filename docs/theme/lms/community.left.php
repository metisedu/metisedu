<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if($bo_table == 'notice'){
	$act1 = "active";
}else if($PHP_SELF == '/bbs/faq.php'){
	$act2 = "active";
}else if($bo_table == 'tutor'){
	$act3 = "active";
}else if($PHP_SELF == '/shop/review.php'){
	$act4 = "active";
}
?>
<aside class="col-md-3">
	<div class="box_style_4" style="border:0px;">
		<div class="mypage-menu">
			<ul>
				<li><a href="/bbs/board.php?bo_table=notice" class="<?php echo $act1; ?>"><!-- 공지사항 -->Notice</a></li>
				<li><a href="/bbs/faq.php?fm_id=1" class="<?php echo $act2; ?>"><!-- 자주묻는 질문 -->FAQ</a></li>
				<li><a href="/bbs/write.php?bo_table=tutor" class="<?php echo $act3; ?>"><!-- 자주묻는 질문 -->Tutor</a></li>
				<li><a href="/shop/review.php" class="<?php echo $act4; ?>"><!-- 수강후기 -->Review</a></li>
			</ul>
		</div>
	</div>
</aside>