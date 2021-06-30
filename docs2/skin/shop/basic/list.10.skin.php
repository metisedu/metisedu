<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_SKIN_URL.'/style.css">', 0);

// 장바구니 또는 위시리스트 ajax 스크립트
add_javascript('<script src="'.G5_JS_URL.'/shop.list.action.js"></script>', 10);
?>

<!-- 상품진열 10 시작 { -->
<?php
$i = 0;

$this->view_star = (method_exists($this, 'view_star')) ? $this->view_star : true;

foreach((array) $list as $row){
    if( empty($row) ) continue;

	$it = get_item($row['it_id']);

    $item_link_href = shop_item_url($row['it_id']);     // 상품링크
    $star_score = $row['it_use_avg'] ? (int) get_star($row['it_use_avg']) : '';     //사용자후기 평균별점
	$star_score = (!$it['it_score'])? $star_score:$it['it_score']; // 관리자에서 입력한 값이 있는 경우 우선
    $list_mod = $this->list_mod;    // 분류관리에서 1줄당 이미지 수 값 또는 파일에서 지정한 가로 수
    $is_soldout = is_soldout($row['it_id'], true);   // 품절인지 체크

    $classes = array();

    $classes[] = 'col-row-'.$list_mod;

    if( $i && ($i % $list_mod == 0) ){
        $classes[] = 'row-clear';
    }

    $i++;   // 변수 i 를 증가

    if ($i === 1) {
        if ($this->css) {
            //echo "<ul class=\"{$this->css}\">\n";
        } else {
            //echo "<ul class=\"sct sct_10 lists-row\">\n";
        }
    }

	$this->img_width = "285";
	$this->img_height = "285";

	echo'<div class="col-lg-3 col-md-6 col-sm-6 list-item">';
	echo'	<div class="col-item">';
	echo'	<span class="ribbon_course">';

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

	echo'	</span>';
	echo'		<div class="photo">';
	if ($this->href) {
        echo "<a href=\"{$item_link_href}\">\n";
    }
	if ($this->view_it_img) {
        echo get_it_image($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name']))."\n";
    }
    if ($this->href) {
        echo "</a>\n";
    }
	//echo'			<a href="/sub/view/"><img src="/img/item285x285.png" alt=""></a>';
	echo'		</div>';
	echo'		<div class="info">';
	echo'			<div class="row">';
	echo'				<div class="course_info col-md-12 col-sm-12">';
	echo'					<h4>';
							if ($this->view_it_name) {
								echo stripslashes($row['it_name'])."\n";
							}
	echo'					</h4>';
	echo'					<div class="rating">';
							// 사용후기 평점표시
							get_rating($star_score);
	//echo'					<i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class=" icon-star-empty"></i>';
	echo'					</div>';
	echo'					<div class="clearfix">';
	echo'						<span class="pull-left teacher">'.$it['it_t_name'].' | '.$ca_id_slt[$it['ca_id']].'</span>';
	echo'						<span class="pull-right price">';
								if ($this->view_it_cust_price || $this->view_it_price) {

									echo "<div class=\"sct_cost\">\n";
									if ($this->view_it_price) {
										echo display_price(get_price($row), $row['it_tel_inq'])."\n";
									}
									if ($this->view_it_cust_price && $row['it_cust_price']) {
										//echo "<span class=\"sct_dict\">".display_price($row['it_cust_price'])."</span>\n";
									}
									echo "</div>\n";
								}
	echo'						</span>';
	echo'					</div>';

	echo'				</div>';
	echo'			</div>';

	echo'		</div>';
	echo'	</div>';
	echo'</div>';

	/*
    echo "<li class=\"sct_li ".implode(' ', $classes)."\" data-css=\"nocss\" style=\"height:auto\">\n";
	echo "<div class=\"sct_img\">\n";

    if ($this->href) {
        echo "<a href=\"{$item_link_href}\">\n";
    }

    if ($this->view_it_img) {
        echo get_it_image($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name']))."\n";
    }

    if ($this->href) {
        echo "</a>\n";
    }

    if ( !$is_soldout ){    // 품절 상태가 아니면 출력합니다.
        echo "<div class=\"sct_btn list-10-btn\">
            <button type=\"button\" class=\"btn_cart sct_cart\" data-it_id=\"{$row['it_id']}\"><i class=\"fa fa-shopping-cart\" aria-hidden=\"true\"></i> 장바구니</button>\n";
        echo "</div>\n";
	}

	echo "<div class=\"cart-layer\"></div>\n";

	if ($this->view_it_icon) {
        // 품절
        if ($is_soldout) {
            echo '<span class="shop_icon_soldout"><span class="soldout_txt">SOLD OUT</span></span>';
        }
    }
    echo "</div>\n";

	echo "<div class=\"sct_ct_wrap\">\n";

	// 사용후기 평점표시
	if ($this->view_star && $star_score) {
        echo "<div class=\"sct_star\"><span class=\"sound_only\">고객평점</span><img src=\"".G5_SHOP_URL."/img/s_star".$star_score.".png\" alt=\"별점 ".$star_score."점\" class=\"sit_star\"></div>\n";
    }

    if ($this->view_it_id) {
        echo "<div class=\"sct_id\">&lt;".stripslashes($row['it_id'])."&gt;</div>\n";
    }

    if ($this->href) {
        echo "<div class=\"sct_txt\"><a href=\"{$item_link_href}\">\n";
    }

    if ($this->view_it_name) {
        echo stripslashes($row['it_name'])."\n";
    }

    if ($this->href) {
        echo "</a></div>\n";
    }

	if ($this->view_it_basic && $row['it_basic']) {
        echo "<div class=\"sct_basic\">".stripslashes($row['it_basic'])."</div>\n";
    }

    echo "<div class=\"sct_bottom\">\n";

        if ($this->view_it_cust_price || $this->view_it_price) {

            echo "<div class=\"sct_cost\">\n";
            if ($this->view_it_price) {
                echo display_price(get_price($row), $row['it_tel_inq'])."\n";
            }
            if ($this->view_it_cust_price && $row['it_cust_price']) {
                echo "<span class=\"sct_dict\">".display_price($row['it_cust_price'])."</span>\n";
            }
            echo "</div>\n";
        }

        // 위시리스트 + 공유 버튼 시작
        echo "<div class=\"sct_op_btn\">\n";
        echo "<button type=\"button\" class=\"btn_wish\" data-it_id=\"{$row['it_id']}\"><span class=\"sound_only\">위시리스트</span><i class=\"fa fa-heart-o\" aria-hidden=\"true\"></i></button>\n";
        echo "<button type=\"button\" class=\"btn_share\"><span class=\"sound_only\">공유하기</span><i class=\"fa fa-share-alt\" aria-hidden=\"true\"></i></button>\n";

        echo "<div class=\"sct_sns_wrap\">";
        if ($this->view_sns) {
            $sns_top = $this->img_height + 10;
            $sns_url  = $item_link_href;
            $sns_title = get_text($row['it_name']).' | '.get_text($config['cf_title']);
            echo "<div class=\"sct_sns\">";
            echo "<h3>SNS 공유</h3>";
            echo get_sns_share_link('facebook', $sns_url, $sns_title, G5_SHOP_SKIN_URL.'/img/facebook.png');
            echo get_sns_share_link('twitter', $sns_url, $sns_title, G5_SHOP_SKIN_URL.'/img/twitter.png');
            echo get_sns_share_link('googleplus', $sns_url, $sns_title, G5_SHOP_SKIN_URL.'/img/gplus.png');
            echo "<button type=\"button\" class=\"sct_sns_cls\"><span class=\"sound_only\">닫기</span><i class=\"fa fa-times\" aria-hidden=\"true\"></i></button>";
            echo "</div>\n";
        }
        echo "<div class=\"sct_sns_bg\"></div>";
        echo "</div></div>\n";
        // 위시리스트 + 공유 버튼 끝

    echo "</div>";

        if ($this->view_it_icon) {
            echo "<div class=\"sit_icon_li\">".item_icon($row)."</div>\n";
        }

	echo "</div>\n";

    echo "</li>\n";
	*/
}   //end foreach
/*
if ($i >= 1) echo "</ul>\n";
*/
if($i === 0) echo "<p class=\"sct_noitem\">등록된 상품이 없습니다.</p>\n";
?>
<!-- } 상품진열 10 끝 -->

<script>
//SNS 공유
$(function (){
	$(".btn_share").on("click", function() {
		$(this).parent("div").children(".sct_sns_wrap").show();
	});
    $('.sct_sns_bg, .sct_sns_cls').click(function(){
	    $('.sct_sns_wrap').hide();
	});
});
</script>