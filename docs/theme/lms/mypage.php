<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
define('_NOHEADER_',true);

include_once(G5_THEME_PATH.'/head.php');
?>

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
							<li><a href="/sub/mypage/" class="active"><!-- 수강중인 강의 -->Course</a></li>
							<li><a href="/sub/cart/" class=""><!-- 장바구니 -->Cart</a></li>
							<li><a href="/sub/class-complete/" class=""><!-- 수강 완료 강의 -->Finished course</a></li>
						</ul>
					</div>
					<div class="mypage-class-list responsive">

						<div class="mypage-class-null text-center">
							<i class="fa fa-exclamation-circle" aria-hidden="true"></i>
							<br />
							<!-- 현재 수강중인 강의가 없습니다. -->There are no classes currently in progress.

						</div>

						<table class="table" style="display:;">
							<thead>
								<tr>
									<th><!-- 강의명 -->Course name</th>
									<th  class="text-center"><!-- 이용기간 -->Period of use</th>
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
									<td class="text-center period">평생소장</td>
									<td class="text-center btn_wrap"><a href="/sub/course/" class="btn_course">수강하기</a></td>
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

</section>
<?php
include_once(G5_THEME_PATH.'/tail.php');
?>