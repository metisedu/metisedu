<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- 회원가입결과 시작 { -->
<section id="sub_register">
	<div class="container">
		<div class="row">
			<div class="col-md-12 add_bottom_60 text-center">
				<span class="sub-top-title">회원 가입을 축하합니다.<br /><?php echo get_text($mb['mb_name']); ?> 님의 지식 레벨업을 위한 쿠폰이 지급되었습니다.</span>
			</div>
		</div>
		<div class="row add_bottom_60">

			<!-- 상품 시작 { -->
			<?php
			$list = new item_list();
			$list->set_category('1010', 2);
			$list->set_list_skin($_SERVER['DOCUMENT_ROOT'].'/skin/shop/basic/main.item2.skin.php');
			$list->set_img_size('285','285');
			$list->set_list_row('4');
			$list->set_list_mod('1');
			$list->set_view('it_img', true);
			$list->set_view('it_id', false);
			$list->set_view('it_name', true);
			$list->set_view('it_basic', false);
			$list->set_view('it_cust_price', false);
			$list->set_view('it_price', true);
			$list->set_view('it_icon', false);
			$list->set_view('sns', false);
			echo $list->run();
			?>
			<!-- } 상품 끝 -->

		</div><!-- End row -->
		<div class="row">
			<div class="col-md-12 add_bottom_60 text-center">
				<!--<a href="/bbs/login.php" class="long_login">로그인</a>-->
				<a href="/shop/list.php?ca_id=1010" class="long_more_class">더 많은 강의보기</a>
			</div>
		</div>
	</div>
</div>

<script>
	$(".pay_agree > ul > li > a").on("click",function(){
		$(this).next(".agree_text").toggle();
	});
</script>
</section>