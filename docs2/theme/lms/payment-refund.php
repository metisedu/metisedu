<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
define('_NOHEADER_',true);

include_once(G5_THEME_PATH.'/head.php');
?>
<link rel="stylesheet" type="text/css" href="/css/datepicker3.css" />
<script type="text/javascript" src="/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/js/bootstrap-datepicker.kr.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
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
							<li><a href="/sub/payment-log/" class="">결제내역</a></li>
							<li><a href="/sub/payment-refund/" class="active">환불내역</a></li>
						</ul>
					</div>
					
					<div class="mypage-payment-list no-after">
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
							
							<span class="payment-month">2019.12</span>
							<?php for($i = 0;$i < 5;$i += 1) { ?>
							<div class="col-md-12 payment-list-item refund">
								<div class="row">
									<div class="col-md-4">
										<div class="payment-list-status">
										환불완료
										</div>
										<div class="item-list-name">
											<a href="#">파이썬을 활용한 데이터 분석 집중 클래스</a>
											<span class="author">이두희 | 온라인</span>
											<span class="date">구매일 2020.01.01</span>
										</div>
									</div>
									<div class="col-md-3">
										
										<div class="payment_price">
											<dl>
												<dt>결제금액</dt>
												<dd><?php echo number_format(500000)?>원</dd>
												<dt>공제금액</dt>
												<dd>-<?php echo number_format(100000)?>원</dd>
											</dl>
											<dl class="last">
												<dt>최종 환불금액</dt>
												<dd><?php echo number_format(300000)?>원</dd>
											</dl>
										</div>										
									</div>
									<div class="col-md-5">
										<div class="payment_price refund">
											<dl>
												<dt>환불형태</dt>
												<dd>신용카드</dd>
												<dt>환불완료</dt>
												<dd><?php echo date("Y.m.d H:i")?></dd>
											</dl>
											<div class="caution">
												<ul>
													<li>* 공제 금액은 환불 규정에 따라 제외된 금액입니다</li>
													<li>* 무통장 입금 환불은 본인 명의 계좌만 가능합니다</lio>
												</ul>
											</div>
										</div>
										
										
									</div> 

								</div>
								
							</div>
							<?php } ?>
						</div>

						
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