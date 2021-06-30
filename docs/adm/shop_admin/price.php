<?php
$sub_menu = '500210';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '가격비교사이트';
include_once (G5_ADMIN_PATH.'/admin.head.php');
$pg_anchor = '<ul class="nav nav-tabs bg-warning-a800">
<li class="nav-item"><a href="#anc_pricecompare_info" class="nav-link">가격비교사이트 연동 안내</a></li>
<li class="nav-item"><a href="#anc_pricecompare_engine" class="nav-link">사이트별 엔진페이지 URL</a></li>
</ul>';
?>
<style>
#anc_pricecompare_info li {margin:0}
</style>
<div class="card card-inverse card-flat">
	<div class="card-header">
		<div class="card-title">가격비교사이트 연동 안내</div>
	</div>
	<div class="card-block">
		<div class="row">
			<div class="col-md-12">

				<section id="anc_pricecompare_info">
					<?php echo $pg_anchor; ?>

					<div class="local_desc01 local_desc">
						<ol>
							<li>가격비교사이트는 네이버 지식쇼핑, 다음 쇼핑하우 등이 있습니다.</li>
							<li>앞서 나열한 가격비교사이트 중 희망하시는 사이트에 입점합니다.</li>
							<li><strong>사이트별 엔진페이지 URL</strong>을 참고하여 해당 엔진페이지 URL 을 입점하신 사이트에 알려주시면 됩니다.</li>
						</ol>
					</div>
				</section>
			</div>
		</div>
	</div>
</div>
<div class="card card-inverse card-flat">
	<div class="card-header">
		<div class="card-title">사이트별 엔진페이지 URL</div>
	</div>
	<div class="card-block">
		<div class="row">
			<div class="col-md-12">

				<section id="anc_pricecompare_engine">
					<?php echo $pg_anchor; ?>

					<div class="local_desc01 local_desc">
						<p>사이트 명을 클릭하시면 해당 사이트로 이동합니다.</p>

						<dl class="price_engine">
							<dt><a href="http://shopping.naver.com/" target="_blank">네이버 지식쇼핑</a></dt>
							<dd>
								<ul>
									<li>입점 안내 : <a href="http://join.shopping.naver.com/join/intro.nhn" target="_blank">http://join.shopping.naver.com/join/intro.nhn</a></li>
									<li>전체상품 URL : <a href="<?php echo G5_SHOP_URL; ?>/price/naver.php" target="_blank"><?php echo G5_SHOP_URL; ?>/price/naver.php</a></li>
									<li>요약상품 URL : <a href="<?php echo G5_SHOP_URL; ?>/price/naver_summary.php" target="_blank"><?php echo G5_SHOP_URL; ?>/price/naver_summary.php</a></li>
								</ul>
							</dd>
							<dt><a href="http://shopping.daum.net/" target="_blank">다음 쇼핑하우</a></dt>
							<dd>
								<ul>
									<li>입점 안내 : <a href="http://commerceone.biz.daum.net/join/intro.daum" target="_blank">http://commerceone.biz.daum.net/join/intro.daum</a></li>
									<li>전체상품 URL : <a href="<?php echo G5_SHOP_URL; ?>/price/daum.php" target="_blank"><?php echo G5_SHOP_URL; ?>/price/daum.php</a></li>
									<li>요약상품 URL : <a href="<?php echo G5_SHOP_URL; ?>/price/daum_summary.php" target="_blank"><?php echo G5_SHOP_URL; ?>/price/daum_summary.php</a></li>
								</ul>
							</dd>
						</dl>
					</div>
				</section>
			</div>
		</div>
	</div>
</div>
<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
