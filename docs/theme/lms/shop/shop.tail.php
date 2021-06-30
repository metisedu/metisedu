<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
    return;
}

$admin = get_admin("super");

// 사용자 화면 우측과 하단을 담당하는 페이지입니다.
// 우측, 하단 화면을 꾸미려면 이 파일을 수정합니다.
?>
<footer>
	<div class="container">
		<div class="row text-left">

			<div class="col-md-3 col-sm-6">
				<div class="footer-contents">
				<a href="/" class="footer-subj online">Metise Online</a>
				<ul class="online">
					<li>
						E-Learning Platform
						<!-- 체계적인 교육과정을 갖춘<br>
						전문 교육 플랫폼입니다. -->
					</li>
				</ul>
				</div>
			</div>

			<div class="col-md-3 col-sm-6">
				<div class="footer-contents">
				<a href="/bbs/board.php?bo_table=notice" class="footer-subj notice"><!-- 공지사항 -->Notice</a>
				<ul class="notice">
					<?php
					$sQuery = " SELECT *
								FROM han_write_notice
								WHERE wr_is_comment = 0
								ORDER BY wr_id DESC
								LIMIT 3
								";
					$sql = sql_query($sQuery);
					for($i = 0; $row = sql_fetch_array($sql); $i += 1) {
						$subject = conv_subject($row['wr_subject'], 12, '…');
						$l_href = get_pretty_url('notice', $row['wr_id'], $qstr);
					?>
					<li><a href="<?php echo $l_href; ?>"><?php echo $subject; ?><span class="date"><?php echo date("M d, Y", strtotime($row['wr_datetime'])); ?></span></a></li>
					<?php } ?>
				</ul>
				</div>
			</div>

			<div class="col-md-3 col-sm-6">
				<div class="footer-contents">
				<a href="/bbs/board.php?bo_table=around" class="footer-subj"><!-- 둘러 보기 -->Platform Policy</a>
				<ul class="view">
					<!-- <li>
						<a href="/bbs/content.php?co_id=info" target="_blank" class="">메티스 소개</a>
					</li>
					<li>
						<a href="/bbs/content.php?co_id=teacher" class="">강사소개</a>
					</li> -->
					<li>
						<a href="/bbs/content.php?co_id=terms" class=""><!-- 이용약관 -->User Agreement</a>
					</li>
					<li>
						<a href="/bbs/content.php?co_id=privacy" class=""><!-- 개인정보취급방침 -->Privacy Policy</a>
					</li>
                    <li>
                        <a href="/shop/staking.php" class=""><!-- 개인정보취급방침 -->Staking</a>
                    </li>
				</ul>
				</div>
			</div>

			<div class="col-md-3 col-sm-6">
				<div class="footer-contents">
				<a href="javascript:void(0);" class="footer-subj"><!-- 고객센터 -->CS Center</a>
				<ul class="">
					<li><!-- 고객센터 문의 가능시간 -->Available Time<br ><!-- 평일 08:00~18:00 -->Weekdays 08:00~18:00</li>
					<li style="margin-top:40px">E-mail : <?php echo $config['cf_admin_email']; ?><br> <!-- <a href="/bbs/faq.php">FAQ</a> --></li>
				</ul>
				</div>
			</div>
		</div>

		<div class="col-md-8 col-sm-12">
			<div class="footer-copyright">
				<p style="margin: 0px; display:none;">
				  <?php echo $default['de_admin_company_name']; ?> 
				  <?php echo $default['de_admin_company_zip']; ?> 
				  <?php echo $default['de_admin_company_addr']; ?>   
				  TEL. <?php echo $default['de_admin_company_tel']; ?>
				</p>
				<p style="margin: 0px; display:none;">
				  Business license number: <?php echo $default['de_admin_company_saupja_no']; ?>    
				  Representative: <?php echo $default['de_admin_company_owner']; ?>    
				  Communication sales report number: <?php echo $default['de_admin_tongsin_no']; ?>
				</p>
				<p class="copyright-text" style="letter-spacing:-0.5px; text-align:center;">Copyright <?php echo date("Y"); ?>. Metis Enterprise Ltd. All rights reserved.</p>
				<div class="col-sm-12 col-sx-12 hidden-lg hidden-md pull-right icons" style="margin: 0px; display:none;">
					<!-- <a href="#" class="icon facebook"></a> -->
					<a href="#" class="icon kakao"></a>
					<!-- <a href="#" class="icon naver"></a> -->
				</div>
			</div>
		</div>
		<div class="col-md-4 hidden-sm hidden-xs pull-right icons">
			<!-- <a href="#" class="icon facebook"></a> -->
			<a href="#" class="icon kakao"></a>
			<!-- <a href="#" class="icon naver"></a> -->
		</div>
	</div>
</footer>
<?php
$sec = get_microtime() - $begin_time;
$file = $_SERVER['SCRIPT_NAME'];

if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>

<script src="<?php echo G5_JS_URL; ?>/sns.js"></script>
<script src="<?php echo G5_JS_URL ?>/superfish.js"></script>
<script>
  jQuery(document).ready(function() {
    jQuery('ul.sf-menu').superfish();
  });
</script>
<?php
if(defined('_INDEX_')) {
?>
<!--<script type="text/javascript" src="<?php echo G5_JS_URL ?>/jquery.themepunch.plugins.min.js"></script>
<script type="text/javascript" src="<?php echo G5_JS_URL ?>/jquery.themepunch.revolution.min.js"></script>-->

<script src="<?php echo G5_JS_URL ?>/swiper.min.js"></script>
<script type="text/javascript">
	var swiper = new Swiper('.top-swiper', {
	  autoplay: {
		delay: 4000,
	  },
      speed: 600,
      parallax: true,
	  loop: true,
	  pagination: {
		el: '.top-swiper-pagination',
		clickable: true,
	  },

    });

	/*
	var revapi;

	jQuery(document).ready(function() {

		   revapi = jQuery('.tp-banner').revolution(
			{
				delay:9000,
				startwidth:1700,
				startheight:600,
				hideThumbs:true,
				navigationType:"none",
				fullWidth:"on",
				forceFullWidth:"on"
			});

	});	//ready
	*/
</script>
<?php } ?>
<!-- } 하단 끝 -->

<?php
include_once(G5_THEME_PATH.'/tail.sub.php');
?>
