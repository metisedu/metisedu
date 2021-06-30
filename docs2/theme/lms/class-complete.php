<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
define('_NOHEADER_',true);

include_once(G5_THEME_PATH.'/head.php');
?>
<link rel="stylesheet" type="text/css" href="/css/datepicker3.css" />
<script type="text/javascript" src="/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/js/bootstrap-datepicker.kr.js"></script>
<section id="sub_mypage">
	<div class="container">
		<div class="row">
			<?php
			include_once(G5_THEME_PATH.'/mypage.left.php');
			?>
			
			<div class="col-md-9">
				<div class="mypage-class">
					<div class="mypage-class-menu">
						<ul>
							<li><a href="/sub/mypage/" class="">수강중인 강의</a></li>
							<li><a href="/sub/cart/" class="">장바구니</a></li>
							<li><a href="/sub/class-complete/" class="active">수강 완료 강의</a></li>
						</ul>
					</div>
					
					<div class="mypage-class-list responsive no-after">
						<div class="class-filter">
						
							<div class="col-md-6">
								<div class="period">
									<ul>
										<li><a href="#" class="active">3개월</a></li>
										<li><a href="#" class="">6개월</a></li>
										<li><a href="#" class="">12개월</a></li>
									</ul>
								</div>
							</div>
							<div class="col-md-6">
								<div class="period_date">
									<input type="text" name="s_date" id="s_date" class="form_control input_period" value="<?php echo date("Y.m.d")?>"> - <input type="text" name="e_date" id="e_date" class="form_control input_period"  value="<?php echo date("Y.m.d")?>">
									<a href="#" class="btn_date">조회</a>
								</div>
							</div>
						</div>

						<table class="table">
							<thead>
								<tr>
									<th>강의명</th>
									<th  class="text-center">이용기간</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php
								for($i = 0;$i < 3;$i += 1) { 
								?>
								<tr class="item-list">
									<td>
									<div class="media">
										<div class="pull-left thumb">
											<img src="/img/item_thumb_68x68.jpg" class="" alt="">
										</div>
										<div class="item-list-name">
											<a href="#">파이썬을 활용한 데이터 분석 집중 클래스</a>
											<span class="author">이두희 | 온라인</span>
											<span class="date">구매일 2020.01.01</span>
										</div>										
									</div>
									</td>
									<td class="text-center period">기간만료</td>
									<td class="text-center btn_wrap"><a href="/sub/course/" class="btn_class_complete">다시수강하기</a></td>
								</tr>
								<?php
								}
								?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
<script>
$('.input_period').datepicker({
 autoclose: true,
 format: "yyyy.mm.dd",
 language: "kr"		
});
</script>
</section>
<?php
include_once(G5_THEME_PATH.'/tail.php');
?>