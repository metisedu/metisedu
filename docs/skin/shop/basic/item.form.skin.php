<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$t_day = date("Y-m-d");

$sQuery = " SELECT *
			FROM g5_shop_cart
			WHERE mb_id = '".$member['mb_id']."'
			AND   ct_status IN ('입금','배송','완료')
			AND   it_id = '".$it_id."'
			AND   ct_start_date <= '".$t_day."'
			AND   ct_end_date >= '".$t_day."'
			";
$rst = sql_fetch($sQuery);

if($rst){
	$mv_icon = "fa-play";
}else{
	//$mv_icon = "fa-lock";
	$mv_icon = "";
}

//$it = get_item($rst['it_id']);
$ca = get_cate($it['ca_id']);

$image = get_it_image($rst['it_id'], 520, 293);

// 강의 첫번째 영상/진행된 영상 가져오기
$first_mv = get_use_mv_check($it_id, $member['mb_id']);

// 리뷰작성 내역 확인
$sQuery = " SELECT *
			FROM g5_shop_item_use
			WHERE mb_id = '".$member['mb_id']."'
			AND   it_id = '".$it_id."'
			";
$review_check = sql_fetch($sQuery);

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_CSS_URL.'/style.css">', 0);
?>
<style>
button.direct_buy{border:0px;padding:15px 0;text-align:center;width:100%;display:block;border-radius:0;box-sizing:border-box;color:#ffffff;background-color:#0a0a09;font-size:1.3em;font-weight:300;margin:8px auto;letter-spacing:-1px;}
button.go_cart{padding:10px 0;text-align:center;width:100%;display:block;box-sizing:border-box;color:#0a0a09;border:4px solid #edad58;border-radius:0;font-size:1.3em;font-weight:300;margin:8px auto 15px auto;letter-spacing:-1px;background:#fff;}
button.btn.bg-blue{}
</style>
<section id="sub-header">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<?php
				$it_img = G5_DATA_PATH.'/item/'.$it['it_img2'];
				$it_img_exists = file_exists($it_img);

				$thumb = "";
				if($it_img_exists && $it['it_img2']) {
					$thumb = get_it_thumbnail($it['it_img2'], 1199, 328);

					if(!$thumb){
						$thumb = G5_DATA_URL.'/item/'.$it['it_img2'];
					}else{
						preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $thumb, $matches);
						$thumb = $matches[1][0];
					}
					$thumb = "background-image:url(".$thumb.");";
				}
				?>
				<div class="sub_cover" style="<?php echo $thumb; ?>">
					<div class="sub_cover_contents">
						<div class="ribbon">
						<?php
						if($it['it_type1'])
							echo'		<span class="ribbon01">신규</span>'.PHP_EOL;
						if($it['it_type2'])
							echo'		<span class="ribbon02">인기</span>'.PHP_EOL;
						if($it['it_type3'])
							echo'		<span class="ribbon03">무료</span>'.PHP_EOL;
						if($it['it_type4'])
							echo'		<span class="ribbon04">마감임박</span>'.PHP_EOL;
						if($it['it_type5'])
							echo'		<span class="ribbon05">얼리버드</span>'.PHP_EOL;
						if($it['it_type6'])
							echo'		<span class="ribbon06">할인</span>'.PHP_EOL;
						if($it['it_type7'])
							echo'		<span class="ribbon07">추천</span>'.PHP_EOL;
						if($it['it_type8'])
							echo'		<span class="ribbon08">예정</span>'.PHP_EOL;
						if($it['it_type9'])
							echo'		<span class="ribbon09">코스</span>'.PHP_EOL;
						if($it['it_type10'])
							echo'		<span class="ribbon10">플립러닝</span>'.PHP_EOL;
						if($it['it_type11'])
							echo'		<span class="ribbon11">기업전용</span>'.PHP_EOL;
						if($it['it_type12'])
							echo'		<span class="ribbon12">사업주</span>'.PHP_EOL;
						if($it['it_type13'])
							echo'		<span class="ribbon13">한정</span>'.PHP_EOL;
						if($it['it_type14'])
							echo'		<span class="ribbon14">온라인</span>'.PHP_EOL;
						if($it['it_type15'])
							echo'		<span class="ribbon15">패키지</span>'.PHP_EOL;
						if($it['it_type16'])
							echo'		<span class="ribbon16">프리패스</span>'.PHP_EOL;
						if($it['it_type17'])
							echo'		<span class="ribbon17">환급</span>'.PHP_EOL;
						if($it['it_type18'])
							echo'		<span class="ribbon18">무료배송</span>'.PHP_EOL;
						if($it['it_type19'])
							echo'		<span class="ribbon19">LIVE</span>'.PHP_EOL;
						if($it['it_type20'])
							echo'		<span class="ribbon20">BEST</span>'.PHP_EOL;
						if($it['it_type21'])
							echo'		<span class="ribbon21">NEW</span>'.PHP_EOL;
						if($it['it_type22'])
							echo'		<span class="ribbon22">트렌드</span>'.PHP_EOL;
						if($it['it_type23'])
							echo'		<span class="ribbon23">강연</span>'.PHP_EOL;
						if($it['it_type24'])
							echo'		<span class="ribbon24">자기계발</span>'.PHP_EOL;
						if($it['it_type25'])
							echo'		<span class="ribbon25">토론</span>'.PHP_EOL;
						if($it['it_type26'])
							echo'		<span class="ribbon26">저자직강</span>'.PHP_EOL;
						?>
						</div>
						<div class="sub_cover_tit"><?php echo stripslashes($it['it_name']); ?></div>
						<div class="sub_cover_rating">
							<div class="rating">
								<?php
								get_rating($star_score);
								?>
								<!--<i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class=" icon-star-empty"></i>-->
							</div>
						</div>
						<? /*
						<div class="sub_cover_score"><?php echo $star_score; ?></div>
						<div class="sub_cover_price"><?php echo display_price(get_price($it)); ?></div>
						<div class="sub_cover_txt1">
							<?php
							if($it['it_lect_type'] == "기간"){
								echo $it['it_lect_date']."일";
							}else if($it['it_lect_type'] == "일수"){
								echo $it['it_lect_day'];
							}else if($it['it_lect_type'] == "평생"){
								echo"평생소장";
							}
							?>
						</div>
						*/ ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="sub_view_content">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="sub_detail_tit"><!-- 강의소개 -->Lecture Information</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-9">
				<div class="sub_detail_content add_bottom_60">
					<div class="sub_detail_content_html">
						<?php echo conv_content($it['it_explan'], 1); //$it['it_explan']; ?>
					</div>
				</div>


				<!-- 커리큘럼 시작 { -->
				<div class="sub_detail_content add_bottom_60">
					<div class="sub_detail_tit" style="border:0px;margin-bottom: 0px;"><!-- 커리큘럼 -->Curriculum</div>
					<?php
					// 등록된 강의 정보 가져오기
					$sQuery = " SELECT *
								FROM han_shop_item_list
								WHERE it_id = '".$it['it_id']."'
								ORDER BY ir_order ASC, ir_no ASC
								";
					$sql_s = sql_query($sQuery);

					for($k = 0; $han = sql_fetch_array($sql_s); $k++){
						$it_id2 = $han['it_id2'];

						$lec = sql_fetch("SELECT * FROM han_shop_lec WHERE lt_id = '".$it_id2."' ");

						echo'<div class="sub_detail_course_subj">'.stripslashes($lec['it_name']).' <span><img src="/img/arrow_1.png"></span></div>';

						$sQuery = " SELECT *
									FROM han_shop_chapter
									WHERE lt_id = '".$it_id2."'
									ORDER BY (cp_order + 0) ASC
									";
						$result = sql_query($sQuery);

						for($i = 0; $row = sql_fetch_array($result); $i++){

							if ($i == 0) echo '<ol class="sit_cur_ol">';
					?>
						<li class="sit_cur_li">
							<div class="cur_mv_img" style="">
							<?php
							$thumb = get_mv_thumbnail($row['cp_file'], 167, 167); // 250,167
							if($thumb){
								echo $thumb;
							}else{
								echo"<img src='/img/임시커리큘럼.png' alt='임시커리큘럼' style='width:167px;height:auto;' />";
							}
							?>
							</div>
							<div class="cur_mv_list" style="">
								<p>CHAPTER <?php echo sprintf("%02d", ($i + 1)); ?></p>
								<h4><?php echo stripslashes($row['cp_name']); ?></h4>
								<ul>
								<?php
								$sQuery = " SELECT *
											FROM han_shop_movie
											WHERE lt_id = '".$it_id2."'
											AND   cp_id = '".$row['cp_id']."'
											AND   mv_use = '1'
											ORDER BY (mv_order + 0) ASC, mv_id DESC ";
								$sql = sql_query($sQuery);

								for($k = 0; $rst = sql_fetch_array($sql); $k++){
								?>
									<li>
										<span><?php echo sprintf("%02d", ($k + 1)); ?></span> <?php echo $rst['mv_name']; ?>
										<?php if($rst['mv_preview']){ ?>
										<span class="s go_preview" data-id="<?php echo $rst['mv_id']; ?>"><i class="fa fa-circle"></i> <!-- 미리보기 -->Preview</span>
										<?php } ?>
									</li>
								<?php } ?>
								</ul>
							</div>
						</li>
					<?php
						}

						if ($i > 0) echo '</ol>';
					}

					if($i == 0){
						echo"<li>커리큘럼 준비중입니다</li>";
					}
					?>
				</div>

				<!-- 수강후기 시작 { -->
				<div class="sub_detail_review add_bottom_60">
					<div class="sub_detail_tit"><!-- 수강후기 -->Review</div>
					<div id="comments">
						<ol>
							<?php include_once(G5_SHOP_PATH.'/itemuse.php'); ?>
						</ol>
					</div>
					<div class="btn_more"><a href="/shop/review.php"><!-- 다른 수강평 더보기 -->See more</a></div>
				</div>
				<!-- } 수강후기 끝 -->

				<?php
				if ($default['de_rel_list_use']) { ?>
				<!-- 관련상품 시작 { -->
				<div class="sub_detail_relation add_bottom_60" style="overflow: hidden;">
					<div class="sub_detail_tit"><!-- 함께 수강하면 좋은 강의 -->A class that is good to take</div>
					<?php
					$rel_skin_file = $skin_dir.'/'.$default['de_rel_list_skin'];
					if(!is_file($rel_skin_file))
						$rel_skin_file = G5_SHOP_SKIN_PATH.'/'.$default['de_rel_list_skin'];

					$sql = " select b.* from {$g5['g5_shop_item_relation_table']} a left join {$g5['g5_shop_item_table']} b on (a.it_id2=b.it_id) where a.it_id = '{$it['it_id']}' and b.it_use='1' ";
					$list = new item_list($rel_skin_file, $default['de_rel_list_mod'], 0, $default['de_rel_img_width'], $default['de_rel_img_height']);
					$list->set_query($sql);
					echo $list->run();

				echo'</div>';
				}
				?>
				<!-- } 관련상품 끝 -->
			</div>

			<!-- 우측 메뉴 -->
			<form name="fitem" method="post" action="<?php echo $action_url; ?>" onsubmit="return fitem_submit(this);">
			<input type="hidden" name="it_id[]" value="<?php echo $it_id; ?>">
			<input type="hidden" name="sw_direct">
			<input type="hidden" name="url">

			<aside class="col-md-3">
				<div class="box_style_4 sub_detail_action">
					<div class="media">
						<div class="pull-left">
							<?php
							$big_img_count = 0;
							$thumbnails = array();
							for($i=1; $i<=1; $i++) {
								if(!$it['it_img'.$i])
									continue;

								$img = get_it_thumbnail($it['it_img'.$i], $default['de_mimg_width'], $default['de_mimg_height']);

								if($img) {
									// 썸네일
									$thumb = get_it_thumbnail($it['it_img'.$i], 68, 68);
									$big_img_count++;

									echo '<a href="'.G5_SHOP_URL.'/largeimage.php?it_id='.$it['it_id'].'&amp;no='.$i.'" target="_blank" class="popup_item_image">'.$thumb.'</a>';
								}
							}

							if($big_img_count == 0) {
								echo '<img src="'.G5_SHOP_URL.'/img/no_image.gif" alt="">';
							}
							?>
							<!--<img src="/img/item_thumb_68x68.jpg" class="" alt="">-->
						</div>
						<div class="media-body">
							<h5 class="media-heading"><a href="#"><?php echo stripslashes($it['it_name']); ?></a></h5>
						</div>
						<div class="sub_detail_action_price">
							<?php if ($it['it_cust_price']) { ?>
							<strike><?php echo display_price($it['it_cust_price']); ?></strike><br />
							<?php } // 시중가격 끝 ?>

							<?php echo display_price(get_price($it)); ?>
							<input type="hidden" id="it_price" value="<?php echo get_price($it); ?>">
						</div>
					</div>
					<div class="sub_detail_action_info">
						<dl>
							<dt><!-- 수강기간 -->Course Time</dt>
							<dd>
								<?php
								if($it['it_lect_type'] == "기간"){
									echo $it['it_lect_date']." day";
								}else if($it['it_lect_type'] == "일수"){
									echo $it['it_lect_day'];
								}else if($it['it_lect_type'] == "평생"){
									echo"평생소장";
								}
								?>
							</dd>
							<? /*
							<dt>수강평</dt>
							<dd class="">
								<?php
								get_rating($star_score);
								?>
								<!--<i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class=" icon-star-empty"></i>--> <?php echo $star_score; ?>
							</dd>
							*/ ?>
							<dt><!-- 강사 -->Teacher</dt>
							<dd><?php echo $it['it_t_name']; ?></dd>
						</dl>
					</div>
					<?php
					if($option_item) {
					?>
					<!-- 선택옵션 시작 { -->
					<section class="sit_option">
						<h3>선택옵션</h3>

						<?php // 선택옵션
						echo $option_item;
						?>
					</section>
					<!-- } 선택옵션 끝 -->
					<?php
					}
					?>

					<?php
					if($supply_item) {
					?>
					<!-- 추가옵션 시작 { -->
					<section  class="sit_option">
						<h3>추가옵션</h3>
						<?php // 추가옵션
						echo $supply_item;
						?>
					</section>
					<!-- } 추가옵션 끝 -->
					<?php
					}
					?>

					<?php if ($is_orderable) { ?>
					<!-- 선택된 옵션 시작 { -->
					<section id="sit_sel_option" style="display:none;">
						<h3>선택된 옵션</h3>
						<?php
						if(!$option_item) {
							if(!$it['it_buy_min_qty'])
								$it['it_buy_min_qty'] = 1;
						?>
						<ul id="sit_opt_added">
							<li class="sit_opt_list">
								<input type="hidden" name="io_type[<?php echo $it_id; ?>][]" value="0">
								<input type="hidden" name="io_id[<?php echo $it_id; ?>][]" value="">
								<input type="hidden" name="io_value[<?php echo $it_id; ?>][]" value="<?php echo $it['it_name']; ?>">
								<input type="hidden" class="io_price" value="0">
								<input type="hidden" class="io_stock" value="<?php echo $it['it_stock_qty']; ?>">
								<div class="opt_name">
									<span class="sit_opt_subj"><?php echo $it['it_name']; ?></span>
								</div>
								<div class="opt_count">
									<label for="ct_qty_<?php echo $i; ?>" class="sound_only">수량</label>
									<button type="button" class="sit_qty_minus"><i class="fa fa-minus" aria-hidden="true"></i><span class="sound_only">감소</span></button>
									<input type="text" name="ct_qty[<?php echo $it_id; ?>][]" value="<?php echo $it['it_buy_min_qty']; ?>" id="ct_qty_<?php echo $i; ?>" class="num_input" size="5">
									<button type="button" class="sit_qty_plus"><i class="fa fa-plus" aria-hidden="true"></i><span class="sound_only">증가</span></button>
									<span class="sit_opt_prc">+0원</span>
								</div>
							</li>
						</ul>
						<script>
						$(function() {
							price_calculate();
						});
						</script>
						<?php } ?>
					</section>
					<!-- } 선택된 옵션 끝 -->

					<!-- 총 구매액 -->
					<div id="sit_tot_price" style="display:none;"></div>
					<?php } ?>

					<?php if($is_soldout) { ?>
					<p id="sit_ov_soldout">상품의 재고가 부족하여 구매할 수 없습니다.</p>
					<?php } ?>

					<div class="sub_detail_action_btn">
						<?php
						if ($is_orderable) {
							if($is_member){
						?>
						<button type="submit" onclick="document.pressed=this.value;" value="바로구매" class=" direct_buy"><!-- 강의 구매하기 -->Lessons Order</button>
						<button type="submit" onclick="document.pressed=this.value;" value="장바구니" class=" go_cart"><!-- 장바구니 -->Cart</button>
						<?php
							}else{
						?>
						<button type="button" onclick="location.href='/bbs/login.php';" class=" direct_buy"><!-- 로그인 -->Login</button>
						<button type="button" onclick="location.href='/bbs/register.php';" class=" go_cart"><!-- 회원가입 -->Sign up</button>
						<?php
							}
						}
						?>

						<?php if ($naverpay_button_js) { ?>
						<div class="itemform-naverpay"><?php echo $naverpay_request_js.$naverpay_button_js; ?></div>
						<?php } ?>
					</div>
				</div>
			</aside>
			</form>
			<!-- 우측 메뉴 -->

		</div>
	</div>
</section>

<script>
$(function(){
    // 상품이미지 첫번째 링크
    $("#sit_pvi_big a:first").addClass("visible");

    // 상품이미지 미리보기 (썸네일에 마우스 오버시)
    $("#sit_pvi .img_thumb").bind("mouseover focus", function(){
        var idx = $("#sit_pvi .img_thumb").index($(this));
        $("#sit_pvi_big a.visible").removeClass("visible");
        $("#sit_pvi_big a:eq("+idx+")").addClass("visible");
    });

    // 상품이미지 크게보기
    $(".popup_item_image").click(function() {
        var url = $(this).attr("href");
        var top = 10;
        var left = 10;
        var opt = 'scrollbars=yes,top='+top+',left='+left;
        popup_window(url, "largeimage", opt);

        return false;
    });
});

function fsubmit_check(f)
{
    // 판매가격이 0 보다 작다면
    if (document.getElementById("it_price").value < 0) {
        alert("전화로 문의해 주시면 감사하겠습니다.");
        return false;
    }

    if($(".sit_opt_list").size() < 1) {
        alert("상품의 선택옵션을 선택해 주십시오.");
        return false;
    }

    var val, io_type, result = true;
    var sum_qty = 0;
    var min_qty = parseInt(<?php echo $it['it_buy_min_qty']; ?>);
    var max_qty = parseInt(<?php echo $it['it_buy_max_qty']; ?>);
    var $el_type = $("input[name^=io_type]");

    $("input[name^=ct_qty]").each(function(index) {
        val = $(this).val();

        if(val.length < 1) {
            alert("수량을 입력해 주십시오.");
            result = false;
            return false;
        }

        if(val.replace(/[0-9]/g, "").length > 0) {
            alert("수량은 숫자로 입력해 주십시오.");
            result = false;
            return false;
        }

        if(parseInt(val.replace(/[^0-9]/g, "")) < 1) {
            alert("수량은 1이상 입력해 주십시오.");
            result = false;
            return false;
        }

        io_type = $el_type.eq(index).val();
        if(io_type == "0")
            sum_qty += parseInt(val);
    });

    if(!result) {
        return false;
    }

    if(min_qty > 0 && sum_qty < min_qty) {
        alert("선택옵션 개수 총합 "+number_format(String(min_qty))+"개 이상 주문해 주십시오.");
        return false;
    }

    if(max_qty > 0 && sum_qty > max_qty) {
        alert("선택옵션 개수 총합 "+number_format(String(max_qty))+"개 이하로 주문해 주십시오.");
        return false;
    }

    return true;
}

// 바로구매, 장바구니 폼 전송
function fitem_submit(f)
{
    f.action = "<?php echo $action_url; ?>";
    f.target = "";

    if (document.pressed == "장바구니") {
        f.sw_direct.value = 0;
    } else { // 바로구매
        f.sw_direct.value = 1;
    }

    // 판매가격이 0 보다 작다면
    if (document.getElementById("it_price").value < 0) {
        alert("전화로 문의해 주시면 감사하겠습니다.");
        return false;
    }

    if($(".sit_opt_list").size() < 1) {
        alert("상품의 선택옵션을 선택해 주십시오.");
        return false;
    }

    var val, io_type, result = true;
    var sum_qty = 0;
    var min_qty = parseInt(<?php echo $it['it_buy_min_qty']; ?>);
    var max_qty = parseInt(<?php echo $it['it_buy_max_qty']; ?>);
    var $el_type = $("input[name^=io_type]");

    $("input[name^=ct_qty]").each(function(index) {
        val = $(this).val();

        if(val.length < 1) {
            alert("수량을 입력해 주십시오.");
            result = false;
            return false;
        }

        if(val.replace(/[0-9]/g, "").length > 0) {
            alert("수량은 숫자로 입력해 주십시오.");
            result = false;
            return false;
        }

        if(parseInt(val.replace(/[^0-9]/g, "")) < 1) {
            alert("수량은 1이상 입력해 주십시오.");
            result = false;
            return false;
        }

        io_type = $el_type.eq(index).val();
        if(io_type == "0")
            sum_qty += parseInt(val);
    });

    if(!result) {
        return false;
    }

    if(min_qty > 0 && sum_qty < min_qty) {
        alert("선택옵션 개수 총합 "+number_format(String(min_qty))+"개 이상 주문해 주십시오.");
        return false;
    }

    if(max_qty > 0 && sum_qty > max_qty) {
        alert("선택옵션 개수 총합 "+number_format(String(max_qty))+"개 이하로 주문해 주십시오.");
        return false;
    }

    return true;
}
/*
$(".sub_detail_course_subj").click(function(){
	$(this).next(".sub_detail_course_dep1").next(".sub_detail_course_dep2").slideToggle();
	$(this).next(".sub_detail_course_dep1").slideToggle();
});
*/
$(".sub_detail_course_subj").click(function(){
	if($(this).children('span').html() == '<img src="/img/arrow_1.png">'){
		$(this).children('span').html('<img src="/img/arrow_2.png">');
	}else{
		$(this).children('span').html('<img src="/img/arrow_1.png">');
	}
	$(this).next(".sit_cur_ol").slideToggle();
});

$(".box_cate_tit").on("click",function(){

	if($(this).hasClass("active")) {
		$(this).removeClass("active");
		$(this).parent().parent().removeClass("active");
		$(this).parent().next(".box_category").slideUp();
	}else{
		$(this).addClass("active");
		$(this).parent().parent().addClass("active");
		$(this).parent().next(".box_category").slideDown();
	}
});

$(".sub_detail_course_dep1").on("click",function(){
	$(this).next(".sub_detail_course_dep2").slideToggle();
});

function form_box_scroll(){
	var form_act = $(".sub_detail_action").offset().top;

	$(window).scroll(function() {
		var position = $(window).scrollTop(); // 현재 스크롤바의 위치값을 반환합니다.
		if(position >= form_act){
			if($.browser.msie && $.browser.version < 7){
				$(".sub_detail_action").stop().animate({"top":position+currentPosition-600+"px"},1000);
			}else{
				$(".sub_detail_action").css({'position':'fixed', 'top':'0px', 'width':'270px'});
			}
		}else{
			$('.sub_detail_action').attr('style','');
		}
	});
}

var windowWidth = $( window ).width();

$(document).ready(function(){
	if(windowWidth >= 990) {
		form_box_scroll();
	}else{ // 모바일에서
		$('.sub_detail_action').attr('style','');
	}

	$(".sub_detail_course_subj").eq(0).trigger('click');
});

$( window ).resize(function() { // 반응형
	windowWidth = $( window ).width();

	if(windowWidth >= 990) {
		form_box_scroll();
	}else{
		$('.sub_detail_action').attr('style','');
	}
});
</script>
<?php /* 2017 리뉴얼한 테마 적용 스크립트입니다. 기존 스크립트를 오버라이드 합니다. */ ?>
<script src="<?php echo G5_JS_URL; ?>/shop.override.js"></script>