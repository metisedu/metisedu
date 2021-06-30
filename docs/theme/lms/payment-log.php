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
							<li><a href="/sub/payment-log/" class="active"><!-- 결제내역 -->Payment list</a></li>
							<li><a href="/sub/payment-refund/" class=""><!-- 환불내역 -->Refund</a></li>
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
							<div class="col-md-12 payment-list-item">
								<div class="row">
									<div class="col-md-4">
										<div class="payment-list-status">
										결제완료
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
												<dt>판매금액</dt>
												<dd><?php echo number_format(500000)?>원</dd>
												<dt>할인금액</dt>
												<dd>-<?php echo number_format(100000)?>원</dd>
											</dl>
											<dl class="last">
												<dt>최종 결제금액</dt>
												<dd><?php echo number_format(300000)?>원</dd>
											</dl>
										</div>										
									</div>
									<div class="col-md-5">
										<div class="payment_price refund">
											<dl>
												<dt>결제수단</dt>
												<dd>신용카드</dd>
												<dt>결제일시</dt>
												<dd><?php echo date("Y.m.d H:i")?></dd>
											</dl>
										</div>
										<a href="#" class="btn_refund" data-toggle="modal" data-target="#refundModal">환불 신청하기 <i class="fa fa-angle-right" aria-hidden="true"></i></a>	
										<style>
											.modal-content{border-radius:0;}
											.modal-header{background-color:#0065af;color:#ffffff;text-align:left;font-size:1.4em}
											.modal-content{border:2px solid #0065af}
											.modal-body{color:#787878;}
											.modal-dialog {width:400px;max-width:95%}


										</style>
										<div class="modal fade" id="refundModal" tabindex="-1" role="dialog" aria-labelledby="refundModalLabel" aria-hidden="true">
										  <div class="modal-dialog" role="document">
											<div class="modal-content">
											  <div class="modal-header">
												환불안내
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												  <span aria-hidden="true">&times;</span>
												</button>
											  </div>
											  <div class="modal-body text-center">
											  <br>
												안녕하세요! 한컴 MDS아카데미입니다.<br><br>

												환불 신청을 원하시면 <a href="mailto:edu@hancommds.com">edu@hancommds.com</a> 으로<br>
												메일 주시면 신속히 처리해 드리겠습니다.<br><br>
												<a href="mailto:edu@hancommds.com" class="refund_email">이메일 환불 신청</a>
											  </div>
											  <!--
											  <div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
											  </div>
											  -->
											</div>
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