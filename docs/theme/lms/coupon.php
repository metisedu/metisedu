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
					<div class="mypage-small-tit">
						<!-- 쿠폰내역 -->Coupon details
						
					</div>
					
					<div class="mypage-coupon-list responsive">
						<div class="mypage-coupon-input">
							<!-- 쿠폰번호 입력 -->Enter Coupon Number <input type="text" name="cp_id" class="frm_input"> <button type="submit"><!-- 쿠폰등록 -->Register</button>
						</div>
						
						<div class="mypage-class-null text-center">
							<i class="fa fa-exclamation-circle" aria-hidden="true"></i>
							<br />
							사용할 수 있는 쿠폰이 없습니다.

						</div>
						

						<table class="table" style="display:;">
							<thead>
								<tr>
									<th>쿠폰 명</th>
									<th style="width:15%"  class="text-center">종류</th>
									<th style="width:15%" class="text-center">기간</th>
									<th style="width:25%"></th>
								</tr>
							</thead>
							<tbody>
								<?php
								for($i = 0;$i < 3;$i += 1) { 
								?>
								<tr class="item-list">
									<td>
									<div class="media">
										
										<div class="item-list-name">
											<span class="coupon-name">파이썬을 활용한 데이터 분석 집중 클래스</span>
											<span class="date">지급일 2020.01.01</span>
										</div>										
									</div>
									</td>
									<td class="text-center type">패키지할인</td>
									<td class="text-center period">남은기간 : 5일</td>
									<td class="text-center btn_wrap"><a href="#" class="btn_coupon">쿠폰 사용하기</a></td>
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