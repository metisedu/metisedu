<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_SKIN_URL.'/style.css">', 0);
add_javascript('<script src="'.G5_JS_URL.'/jquery.bxslider.js"></script>', 10);
?>

<!-- 관련상품 10 시작 { -->
<?php
// 대부류
$ca_id_slt = array(
	"10" => "온라인강의",
	"20" => "패키지강의",
	"30" => "프리패스",
	"40" => "맞춤강의",
	"50" => "1:1 강의"
);
for ($i=1; $row=sql_fetch_array($result); $i++) {
	$it = get_item($row['it_id']);

    if ($this->list_mod >= 2) { // 1줄 이미지 : 2개 이상
        if ($i%$this->list_mod == 0) $sct_last = ' sct_last'; // 줄 마지막
        else if ($i%$this->list_mod == 1) $sct_last = ' sct_clear'; // 줄 첫번째
        else $sct_last = '';
    } else { // 1줄 이미지 : 1개
        $sct_last = ' sct_clear';
    }
?>
	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 list-item">
		<div class="col-item">
		<span class="ribbon_course">
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
		</span>
			<div class="photo">
			<?php
			if ($this->href) {
				echo "<a href=\"{$this->href}{$row['it_id']}\" class=\"sct_a\">\n";
			}
			if ($this->view_it_img) {
				$this->img_width = "285";
				$this->img_height = "285";
				echo get_it_image($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name']))."\n";
			}
			if ($this->href) {
				echo "</a>\n";
			}
			?>
			</div>
			<div class="info">
				<div class="row">
					<div class="course_info col-md-12 col-sm-12">
						<h4>
						<?php
						if ($this->view_it_name) {
							echo stripslashes($row['it_name'])."\n";
						}
						?>
						</h4>
						<div class="rating">
							<?php
							// 사용후기 평점표시
							$t_star_score = (5 - $star_score);
							for($i = 0; $i < $star_score; $i++){
								echo'<i class="icon-star"></i>';
							}
							for($i = 0; $i < $t_star_score; $i++){
								echo'<i class="icon-star-empty"></i>';
							}
							?>
							<!--<i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class=" icon-star-empty"></i>-->
						</div>
						<div class="clearfix">
							<span class="pull-left teacher"><?php echo $it['it_t_name']; ?> | <?php echo $ca_id_slt[$it['ca_id']]; ?></span>
							<span class="pull-right price"><?php echo display_price(get_price($row), $row['it_tel_inq'])."\n"; ?></span>
						</div>

					</div>
				</div>

			</div>
		</div>
	</div>
<?php
}
if($i == 1) echo "<p class=\"sct_noitem\">함께 수강하면 좋은 강의 내역이 없습니다.</p>\n";
?>
<!-- } 관련상품 10 끝 -->
<script>
/*
$(document).ready(function(){
    $('.scr_10').bxSlider({
        slideWidth:175,
        minSlides:6,
        maxSlides:6,
        slideMargin:20,
        pager:false
    });
});
*/
</script>