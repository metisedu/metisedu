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
				<span class="sub-top-title">회원 가입을 축하합니다.
				</span>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12 add_bottom_60 text-center">
				<!--<a href="/bbs/login.php" class="long_login">로그인</a>-->
				<a href="/shop/list.php?ca_id=1010" class="long_more_class">튜터 등록하기</a>
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