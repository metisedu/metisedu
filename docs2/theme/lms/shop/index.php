<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MSHOP_PATH.'/index.php');
    return;
}

define("_INDEX_", TRUE);

include_once(G5_THEME_SHOP_PATH.'/shop.head.php');
?>
<link rel="stylesheet" href="<?php echo G5_CSS_URL ?>/swiper.min.css">

<div class="swiper-container top-swiper">
	<div class="main_top_bg"></div>
	<div class="swiper-wrapper">
		<!-- 메인이미지 시작 { -->
		<?php echo display_banner('메인', 'mainbanner.top.skin.php'); ?>
		<!-- } 메인이미지 끝 -->
	</div>
	<!-- Add Pagination -->
	<div class="top-swiper-pagination swiper-pagination"></div>
</div>


<section class="tp-banner-container">

	<div class="tp-banner" >
		<ul class="sliderwrapper"><!-- SLIDE  -->
			<?php
			for($i = 0;$i < 3;$i += 1) { ?>
			<li data-transition="fade" data-slotamount="4" data-masterspeed="1500" >
				<!-- MAIN IMAGE -->
				<img src="/img/main_visual.jpg" alt="slidebg1" data-bgposition="center top" data-bgrepeat="no-repeat" style="height:500px;">
				<!-- LAYER NR. 1 -->
				<div class="tp-caption skewfromrightshort customout"
					data-x="center"
					data-y="center"
					data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
					data-speed="500"
					data-start="1200"
					data-easing="Power4.easeOut"
					data-endspeed="500"
					data-endeasing="Power4.easeIn"
					data-captionhidden="on"
					style="z-index: 2">
				</div>

				<!-- LAYER NR. 2 -->
				<div class="tp-caption medium_bg_darkblue skewfromleft customout"
					data-x="center"
					data-y="190"
					data-hoffset="30"
					data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
					data-speed="800"
					data-start="1500"
					data-easing="Power4.easeOut"
					data-endspeed="300"
					data-endeasing="Power1.easeIn"
					data-captionhidden="on"
					style="z-index: 6">
				</div>

				<!-- LAYER NR. 3 -->
				<div class="tp-caption medium_bg_darkblue skewfromleft customout"
					data-x="center"
					data-y="245"
					data-hoffset="30"
					data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
					data-speed="800"
					data-start="1500"
					data-easing="Power4.easeOut"
					data-endspeed="300"
					data-endeasing="Power1.easeIn"
					data-captionhidden="on"
					style="z-index: 6">
				</div>
			</li>
			<?php } ?>
		</ul>
		<div class="tp-bannertimer"></div>
	</div>
</section>

<section id="main-features">
	<div class="divider_top_black"></div>
	<div class="container">
		<div class="row">
			<div class=" col-md-10 col-md-offset-1 text-center">
				<form name="fsearchbox" id="fsearch" method="get" action="<?php echo G5_BBS_URL ?>/search.php" onsubmit="return search_submit(this);">
					<label for="sch_str" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
					<input type="text" name="q" id="sch_str" class="main_search" placeholder="배우고싶은 강의를 검색하세요.">
					<button type="submit" class="main_search_submit"></button>

					<?php //echo popular(); // 인기검색어, 테마의 스킨을 사용하려면 스킨을 theme/basic 과 같이 지정  ?>
					<ul class="tag_list">
						<?php
						$sql = " select pp_word, count(*) as cnt from {$g5['popular_table']} where trim(pp_word) <> '' group by pp_word order by cnt desc limit 0, 8 ";
						$presult = sql_query($sql);
						while($prow=sql_fetch_array($presult)){
						?>
							<li>#<?php echo $prow['pp_word']; ?></li>
						<?php } ?>
					</ul>
				</form>
				<script>
				$(document).on("click", ".tag_list li", function(){
					$("#sch_str").val( $(this).text() );
					$("#fsearch").submit();
				});

				function search_submit(f) {
	                if (f.q.value.length < 2) {
	                    alert("검색어는 두글자 이상 입력하십시오.");
	                    f.q.select();
	                    f.q.focus();
	                    return false;
	                }
	                return true;
	            }
                </script>
			</div>
		</div>
		<br /><br />

		<?php
			// 메인 상품 전시 1 extend/shop
			echo get_main_banner_item("0");
		?>

		<br /><br /><br />

		<?php
			// 메인 상품 전시 2
			echo get_main_banner_item("1");
		?>

		<br /><br /><br />

		<?php
			// 메인 상품 전시 2
			echo get_main_banner_item("2");
		?>

	</div><!-- End container-->
</section>

<?php
	// 수강후기, 하단배너, 영상 가져오기
	$sQuery = " SELECT *
				FROM han_shop_banner_etc
				WHERE 1=1
				";
	$etc = sql_fetch($sQuery);
?>
<section id="main_review" style="display:none">
	<div class="container">
		<div class="row add_bottom_30">
			<div class="col-md-12 text-center">
			<h2>수강후기</h2>
			<p class="lead"><?php echo get_text($etc['bn_title']); ?></p>
			</div>
		</div>

		<div class="row">
			<?php
				for($i = 1;$i <= 4;$i += 1) {
					$alt_txt = "bn_alt".$i;
					$url_txt = "bn_url".$i;
					$job_txt = "bn_job".$i;
					$sub_txt = "bn_subject".$i;
					$con_txt = "bn_content".$i;

					$bn_alt = $etc[$alt_txt];
					$bn_job = $etc[$job_txt];
					$bn_sub = $etc[$sub_txt];
					$bn_con = $etc[$con_txt];

					if($etc[$url_txt] != ""){
						$bn_url = $etc[$url_txt];
					}else{
						$bn_url = "javascript:void(0)";
					}

					$bimg = G5_DATA_PATH."/review/".$i;
					$bimg_str = '';
					if (file_exists($bimg)) {
						$size = @getimagesize($bimg);
						if($size[0] && $size[0] > 127)
							$width = 127;
						else
							$width = $size[0];

						$bimg_str = '<img src="'.G5_DATA_URL.'/review/'.$i.'" width="'.$width.'">';
					}
			?>
			<div class="col-md-6">

					<div class="media">
					  <a href="<?php echo $bn_url; ?>">
					  <div class="circ-wrapper pull-left"><?php echo $bimg_str; ?></div>
					  <div class="review-info-wrapper pull-left">
						<?php echo $bn_alt; ?>
						  <span class="review-job"><?php echo $bn_job; ?></span>
						  <span class="review-subj"><?php echo get_text($bn_sub); ?></span>
					  </div>
					  </a>
					  <div class="media-body">
						<a href="<?php echo $bn_url; ?>">
						 <p><?php echo get_text($bn_con); ?></p>
						 </a>
					  </div>
					</div>
			</div>
			<?php } ?>

		</div><!-- End row -->
		<!--<div class="row add_bottom_30 btn_more">
			<a href="/shop/review.php">더 많은 후기보기</a>
		</div>-->

	</div>
</section>

<section id="main_banner" style="display:none;">
	<div class="container">
		<div class="row">
			<?php
			for($i = 1;$i <= 3;$i += 1) {
				$url_txt = "bt_url".$i;
				if($etc[$url_txt] != ""){
					$bt_url = $etc[$url_txt];
				}else{
					$bt_url = "javascript:void(0)";
				}

				$bimg = G5_DATA_PATH."/bottom/".$i;
				$bimg_str = '';
				if (file_exists($bimg)) {
					$bimg_str = '<img src="'.G5_DATA_URL.'/bottom/'.$i.'" class="bt_banner">';
				}
			?>
			<div class="col-md-4">
				<a href="<?php echo $bt_url; ?>" target="_blank"><?php echo $bimg_str; ?></a>
			</div>
			<?php } ?>
		</div>
	</div>
</section>

<section id="main_movie">
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<?php
				if($etc['mv_movie_url'] != ""){
					$mv_movie_url = $etc['mv_movie_url'];
				}else{
					$mv_movie_url = "javascript:void(0)";
				}

				if($etc['mv_url1'] != ""){
					$mv_url = $etc['mv_url1'];
				}else{
					$mv_url = "javascript:void(0)";
				}

				$bimg = G5_DATA_PATH."/movie/1";
				$bimg_str = '';
				if (file_exists($bimg)) {
					$bimg_str = '<img src="'.G5_DATA_URL.'/movie/1">';
				}
				?>
				<div id="bt_movie" style="">
					<ul class="hidden-sm hidden-xs">
						<li>Youtube.</li>
						<li class="tit">
							<?php echo get_text($etc['mv_subject1']); ?>
						</li>
						<li class="con"><?php echo conv_content($etc['mv_content1'], 2); ?></li>
						<li class="btn_more2 hidden-sm hidden-xs">
							<a href="<?php echo $mv_url; ?>" target="_blank">유튜브 바로가기</a>
						</li>
					</ul>
					<a href="<?php echo $mv_movie_url; ?>" target="_blank" style="position: relative;">
						<div class="play_icon"><img src="/img/play_icon.png" alt="play_icon" /></div>
						<?php echo $bimg_str; ?>
					</a>
					<ul class="hidden-lg hidden-md">
						<li class="tit">
							<div class="w50"><?php echo get_text($etc['mv_subject1']); ?></div>
							<div class="btn_more2 hidden-lg hidden-md">
								<a href="<?php echo $mv_url; ?>" target="_blank">유튜브 바로가기</a>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>


<?php
include_once(G5_THEME_SHOP_PATH.'/shop.tail.php');
?>