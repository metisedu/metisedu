<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
define('_NOHEADER_',true);

include_once(G5_THEME_PATH.'/head.php');
?>

<section id="sub_payment">
	<div class="container">
		<div class="row">
			<div class="col-md-12 add_bottom_60">
				<span class="sub-top-title">강의 결제하기</span>
			</div>
		</div>
		<div class="row">
			<div class="col-md-9">
				<div class="class_info add_bottom_60">
					<div class="payment_sub_tit">
						수강정보
					</div>
					<div class="media">
						<div class="pull-left thumb">
							<img src="/img/item_thumb_68x68.jpg" class="" alt="">
						</div>
						<div class="item-list-name">
							<a href="#">파이썬을 활용한 데이터 분석 집중 클래스</a>
							<span class="author">평생 소장, 이두희 ㅣ 온라인  </span>
						</div>										
					</div>
				</div>
				<div class="coupon_info add_bottom_60">
					<div class="payment_sub_tit">
						쿠폰정보
					</div>
					<div class="media">
						<span class="coupon_text"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> 보유쿠폰이 없습니다</span>
						<span class="coupon_text">신규가입 축하 할인 쿠폰 <span class="coupon_price"><?php echo number_format(10000)?>원</span> <button type="button" class="coupon_use">쿠폰사용하기</button></span>
						<div class="coupon_input">
							쿠폰번호 입력 <input type="text" name="cp_no" class="cp_no"><input type="submit" value="쿠폰등록" class="cp_submit">
						</div>
					</div>
				</div>
				<div class="payment_info add_bottom_60">
					<div class="payment_sub_tit">
						결제정보
					</div>
					<div class="row">

						<div class="col-md-6">
							<div class="media payment_input">
								<label for="od_name">이름</label>
								<input type="text" id="od_name" name="od_name" class="" placeholder="이름을 입력해 주세요.">
								<label for="od_email">이메일</label>
								<input type="text" id="od_email" name="od_email" class="" placeholder="이메일을 입력해 주세요.">
								<label for="od_email">연락처</label>
								<input type="text" id="od_hp" name="od_hp" class="" placeholder="연락처를 입력해 주세요.">
							</div>
						</div>
						<div class="col-md-6">
							<div class="media payment_method">
								<label>결제방식</label>
								<div class="payment_method_sel">
								<input type="hidden" name="pay_method" value="CARD">
									<ul>
										<li><a href="javascript://" class="pay_method_sel active" method="CARD">신용카드</a></li>
										<li><a href="javascript://" class="pay_method_sel" method="VBANK">가상계좌</a></li>
										<li><a href="javascript://" class="pay_method_sel" method="BANK">무통장입금</a></li>
									</ul>
								</div>
								<label>결제 약관 동의</label>
								<div class="agree_box">
									<ul>
										<li><a href="javascript://">쇼핑몰 구매 약관 동의 상세보기 <span class="">보기 <i class="fa fa-angle-down" aria-hidden="true"></i></a></span>
											<div class="agree_text">
											쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기
											</div>
										</li>
										<li><a href="javascript://">위 상품의 구매조건 확인 및 결제진행 동의 <span class="">보기 <i class="fa fa-angle-down" aria-hidden="true"></i></a></span>
											<div class="agree_text">
											쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기
											</div>
										</li>
									</ul>
								</div>
								<div class="agree_check">
									<div class="page__toggle">
									<label class="toggle">
									  <input class="toggle__input" type="checkbox" name="diff" value="1">
									  <span class="toggle__label">
										<span class="toggle__text">주문할 상품의 상품명, 상품 가격, 배송 정보를 확인 하였으며, 구매에 모두 동의 합니다</span>
									  </span>
									</label>
									</div>
									<div class="require_tax">* 세금계산서 요청은 duhee.lee@hancommds.com으로 해주세요</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<aside class="col-md-3">
				<div class="box_style_4 sub_detail_action payment_aside">
					<div class="payment_tit">
						결제금액
					</div>
					<div class="payment_price">
						<dl>
							<dt>수강료</dt>
							<dd><?php echo number_format(500000)?>원</dd>
						</dl>
					</div>
					
					<div class="payment_discount">
						<i class="fa fa-minus-circle" aria-hidden="true"></i>
						<dl>
							<dt>강의할인</dt>
							<dd>-<?php echo number_format(100000)?>원</dd>
							<dt>쿠폰할인</dt>
							<dd>-<?php echo number_format(100000)?>원</dd>
						</dl>

					</div>

					<div class="sub_detail_action_btn">
						<div class="payment_tot_price">
						총결제금액
						<span><?php echo number_format(300000)?>원</span>
						</div>
						<a href="javascript://" class="direct_buy">결제하기</a>

					</div>
					
				</div>
			</aside>
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