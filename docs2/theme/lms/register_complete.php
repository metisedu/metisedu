<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
define('_NOHEADER_',true);

include_once(G5_THEME_PATH.'/head.php');
?>

<section id="sub_register">
	<div class="container">
		<div class="row">
			<div class="col-md-12 add_bottom_60 text-center">
				<span class="sub-top-title">회원 가입을 축하합니다.<br />이두희 님의 지식 레벨업을 위한 쿠폰이 지급되었습니다.</span>				
			</div>
		</div>
		<div class="row add_bottom_60">

			<!-- 상품 시작 { -->
			<?php
			$list = new item_list();
			$list->set_category('1010', 2);
			$list->set_list_skin($_SERVER['DOCUMENT_ROOT'].'/skin/shop/basic/main.item.skin.php');
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
				<a href="/login" class="long_login">로그인</a>
				<a href="/login" class="long_more_class">더 많은 강의보기</a>
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
<?php
include_once(G5_THEME_PATH.'/tail.php');
?>