<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if(G5_IS_MOBILE) {
    include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');
    return;
}

include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');

//add_javascript('<script src="'.G5_JS_URL.'/owlcarousel/owl.carousel.min.js"></script>', 10);
//add_stylesheet('<link rel="stylesheet" href="'.G5_JS_URL.'/owlcarousel/owl.carousel.css">', 0);

if($PHP_SELF == "/bbs/content.php"){
	define("_NOHEADER_", TRUE);
}
?>
<style>
input[type="text"].main_search {
    border: 0px !important;
}
</style>
<header>
	<nav>
	<div class="container">
		<div class="row hidden-lg hidden-md">
			<div class="col-md-2 col-sm-2 col-xs-2" id="m_open"><img src="/img/m_menu.png" style="width:90%;" /></div>
			<div class="col-md-8 col-sm-8 col-xs-8 text_center"><a href="<?php echo G5_URL?>"><img src="/img/logo.png" style="width:50%;" /></a></div>
			<?php if ($is_member) {  ?>
			<div class="col-md-2 col-sm-2 col-xs-2"><a href="<?php echo G5_SHOP_URL ?>/mypage.php"><img src="/img/mypage_btn.png" style="width:90%;" /></a></div>
			<?php } else {  ?>
			<div class="col-md-2 col-sm-2 col-xs-2"><a href="<?php echo G5_BBS_URL ?>/login.php"><img src="/img/mypage_btn.png" style="width:90%;" /></a></div>
			<?php }  ?>

			<div class="m_area">
				<a href="<?php echo G5_URL?>"><h2><?php echo $config['cf_title']; ?></h2></a>
				<ul class="mm-menu sf-js-enabled sf-arrows">
					<?php
					// 메인메뉴
					$menu_datas = get_menu_db(0, true);
					$gnb_zindex = 999; // gnb_1dli z-index 값 설정용
					$i = 0;
					foreach( $menu_datas as $row ){
						if( empty($row) ) continue;
						$add_class = (isset($row['sub']) && $row['sub']) ? 'gnb_al_li_plus' : '';
						if(isset($row['sub']) && $row['sub']){
							$mm_href = "javascript:void(0);";
							$mm_onclick = "onclick=$(this).parent('li.normal_drop_down').children('ul').toggle()";
						}else{
							$mm_href = $row['me_link'];
							$mm_onclick = "";
						}
					?>
					<li class="normal_drop_down">
						<a href="<?php echo $mm_href; ?>" target="_<?php echo $row['me_target']; ?>" class="sf-with-ul <?php echo $add_class; ?>" <?php echo $mm_onclick; ?>><?php echo $row['me_name'] ?></a>
						<?php
						$k = 0;
						foreach( (array) $row['sub'] as $row2 ){

							if( empty($row2) ) continue;

							if($k == 0)
								echo '<div class="mobnav-subarrow1"></div><ul style="display: none">'.PHP_EOL;
						?>
							<li><a href="<?php echo $row2['me_link']; ?>" target="_<?php echo $row2['me_target']; ?>" class="gnb_2da"><?php echo $row2['me_name'] ?></a></li>
						<?php
						$k++;
						}   //end foreach $row2

						if($k > 0)
							echo '</ul>'.PHP_EOL;
						?>
					</li>
					<?php
					$i++;
					}   //end foreach $row
					?>
				</ul>
			</div>
			<script>
				$("#m_open").click(function(){
					$("body").css("position","fixed");
					$(".m_area").show();
				});
			</script>
		</div>
		<div class="row hidden-sm hidden-xs">
			<div class="col-md-2 col-sm-2 col-xs-2">
				<a href="<?php echo G5_URL?>/index.php" id="logo"><img src="/img/logo.png"></a>
			</div>
			<div class="col-md-10">
				<div id="mobnav-btn"></div>
				<ul class="sf-menu sf-js-enabled sf-arrows" style="z-index: 10000;">
					<?php
					// 메인메뉴
					$menu_datas = get_menu_db(0, true);
					$gnb_zindex = 999; // gnb_1dli z-index 값 설정용
					$i = 0;
					foreach( $menu_datas as $row ){
						if( empty($row) ) continue;
						$add_class = (isset($row['sub']) && $row['sub']) ? 'gnb_al_li_plus' : '';
					?>
					<li class="normal_drop_down">
						<a href="<?php echo $row['me_link']; ?>" target="_<?php echo $row['me_target']; ?>" class="sf-with-ul"><?php echo $row['me_name'] ?></a>
						<?php
						$k = 0;
						foreach( (array) $row['sub'] as $row2 ){

							if( empty($row2) ) continue;

							if($k == 0)
								echo '<div class="mobnav-subarrow"></div><ul style="display: none">'.PHP_EOL;
						?>
							<li><a href="<?php echo $row2['me_link']; ?>" target="_<?php echo $row2['me_target']; ?>" class="gnb_2da"><?php echo $row2['me_name'] ?></a></li>
						<?php
						$k++;
						}   //end foreach $row2

						if($k > 0)
							echo '</ul>'.PHP_EOL;
						?>
					</li>
					<?php
					$i++;
					}   //end foreach $row
					?>

					<?php if ($is_member) {  ?>
					<li class="mypage"><a href="<?php echo G5_BBS_URL ?>/logout.php" class="first">로그아웃</a><a href="<?php echo G5_SHOP_URL ?>/mypage.php">마이페이지</a></li>
					<?php if ($is_admin) {  ?>
					<!--<li><a href="<?php echo G5_BBS_URL ?>/logout.php">로그아웃</a></li>
					<li class="tnb_admin"><a href="<?php echo correct_goto_url(G5_ADMIN_URL); ?>">관리자</a></li>-->
					<?php }  ?>
					<?php } else {  ?>
					<li class="mypage"><a href="<?php echo G5_BBS_URL ?>/login.php" class="first">로그인</a><a href="<?php echo G5_BBS_URL ?>/register.php">회원가입</a></li>
					<?php }  ?>
				</ul>
				<!--
				<div class="col-md-3 pull-right hidden-sm hidden-xs">
						<div id="sb-search" class="sb-search">
							<form>
								<input class="sb-search-input" placeholder="Enter your search term..." type="text" value="" name="search" id="search">
								<input class="sb-search-submit" type="submit" value="">
								<span class="sb-icon-search"></span>
							</form>
						</div>
				</div><!-- End search -->
			</div>
		</div><!-- End row -->
	</div><!-- End container -->
	</nav>
</header>
<?php if(!defined('_INDEX_') && !defined('_NOHEADER_')) { ?>
<section id="sub-header">
	<div class="container">
		<div class="row">
			<?php
			if($ca_id){ // 타이틀이 등록된 경우에만
				// 서브 상단 타이틀
				$sQuery = " SELECT *
							FROM han_shop_banner_cate
							WHERE ca_id = '".$ca_id."'
							";
				$sub_tit_check = sql_num_rows(sql_query($sQuery));
				if(!$sub_tit_check){
					$sQuery = " SELECT *
								FROM han_shop_banner_cate
								WHERE ca_id = '".substr($ca_id,0,4)."'
								";
				}
				$ca_bn_list = sql_fetch($sQuery);

				if($ca_bn_list['ca_color'])
					$ca_color = $ca_bn_list['ca_color'];
				else
					$ca_color = "#0066b3";
			?>
			<div class="col-md-7 text-left">
				<div class="sub_tit">
					<h3 style="color:<?php echo $ca_color; ?>;"><?php echo $ca_bn_list['ca_title']; ?></h3>
					<p><?php echo $ca_bn_list['ca_s_title']; ?></p>
				</div>
			</div>
			<?php } ?>
			<div class="col-md-5 text-right">
				<form name="fsearchbox" id="fsearch" method="get" action="<?php echo G5_BBS_URL ?>/search.php" onsubmit="return fsearchbox_submit(this);">
					<label for="sch_stx" class="sound_only">검색어 필수</label>
					<input type="text" name="q" id="sch_stx" class="main_search" placeholder="배우고싶은 강의를 검색하세요." autocomplete="off">
					<button type="submit" class="main_search_submit"></button>
				</form>
				<script>
                function fsearchbox_submit(f)
                {
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

	</div>
</section>
<?php } ?>

