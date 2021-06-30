<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (!defined('G5_USE_SHOP') || !G5_USE_SHOP) return;

/*
배송업체에 데이터를 추가하는 경우 아래 형식으로 추가하세요.
.'(배송업체명^택배조회URL^연락처)'
*/
define('G5_DELIVERY_COMPANY',
     '(경동택배^https://kdexp.com/basicNewDelivery.kd?barcode=^080-873-2178)'
    .'(대신택배^http://home.daesinlogistics.co.kr/daesin/jsp/d_freight_chase/d_general_process2.jsp?billno1=^043-222-4582)'
    .'(동부택배^http://www.dongbups.com/delivery/delivery_search_view.jsp?item_no=^1588-8848)'
    .'(로젠택배^http://www.ilogen.com/iLOGEN.Web.New/TRACE/TraceView.aspx?gubun=slipno&slipno=^1588-9988)'
    .'(우체국^http://service.epost.go.kr/trace.RetrieveRegiPrclDeliv.postal?sid1=^1588-1300)'
    .'(이노지스택배^http://www.innogis.co.kr/tracking_view.asp?invoice=^1566-4082)'
    .'(한진택배^http://www.hanjin.co.kr/Delivery_html/inquiry/result_waybill.jsp?wbl_num=^1588-0011)'
    .'(롯데택배^https://www.lotteglogis.com/open/tracking?invno=^1588-2121)'
    .'(CJ대한통운^https://www.doortodoor.co.kr/parcel/doortodoor.do?fsp_action=PARC_ACT_002&fsp_cmd=retrieveInvNoACT&invc_no=^1588-1255)'
    .'(CVSnet편의점택배^http://was.cvsnet.co.kr/_ver2/board/ctod_status.jsp?invoice_no=^1577-1287)'
    .'(KG옐로우캡택배^http://www.yellowcap.co.kr/custom/inquiry_result.asp?invoice_no=^1588-0123)'
    .'(KGB택배^http://www.kgbls.co.kr/sub5/trace.asp?f_slipno=^1577-4577)'
    .'(KG로지스^http://www.kglogis.co.kr/contents/waybill.jsp?item_no=^1588-8848)'
    .'(건영택배^http://www.kunyoung.com/goods/goods_01.php?mulno=^031-460-2700)'
    .'(호남택배^http://www.honamlogis.co.kr/04estimate/songjang_list.php?c_search1=^031-376-6070)'
);

include_once(G5_LIB_PATH.'/shop.data.lib.php');
include_once(G5_LIB_PATH.'/shop.lib.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

//==============================================================================
// 쇼핑몰 미수금 등의 주문정보
//==============================================================================
/*
$info = get_order_info($od_id);

$info['od_cart_price']      // 장바구니 주문상품 총금액
$info['od_send_cost']       // 배송비
$info['od_coupon']          // 주문할인 쿠폰금액
$info['od_send_coupon']     // 배송할인 쿠폰금액
$info['od_cart_coupon']     // 상품할인 쿠폰금액
$info['od_tax_mny']         // 과세 공급가액
$info['od_vat_mny']         // 부가세액
$info['od_free_mny']        // 비과세 공급가액
$info['od_cancel_price']    // 주문 취소상품 총금액
$info['od_misu']            // 미수금액
*/
//==============================================================================
// 쇼핑몰 미수금 등의 주문정보
//==============================================================================

// 매출전표 url 설정
if($default['de_card_test']) {
    define('G5_BILL_RECEIPT_URL', 'https://testadmin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=');
    define('G5_CASH_RECEIPT_URL', 'https://testadmin8.kcp.co.kr/Modules/Service/Cash/Cash_Bill_Common_View.jsp?term_id=PGNW');
} else {
    define('G5_BILL_RECEIPT_URL', 'https://admin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=');
    define('G5_CASH_RECEIPT_URL', 'https://admin.kcp.co.kr/Modules/Service/Cash/Cash_Bill_Common_View.jsp?term_id=PGNW');
}

// 상품상세 페이지에서 재고체크 실행 여부 선택
// 상품의 옵션이 많아 로딩 속도가 느린 경우 false 로 설정
define('G5_SOLDOUT_CHECK', true);

// 주문폼의 상품이 재고 차감에 포함되는 기준 시간설정
// 0 이면 재고 차감에 계속 포함됨
define('G5_CART_STOCK_LIMIT', 3);

// 아이코드 코인 최소금액 설정
// 코인 잔액이 설정 금액보다 작을 때는 주문시 SMS 발송 안함
define('G5_ICODE_COIN', 100);

include_once(G5_LIB_PATH.'/shop.uri.lib.php');

add_replace('get_pretty_url', 'add_pretty_shop_url', 10, 5);
add_replace('false_short_url_clean', 'shop_short_url_clean', 10, 4);
add_replace('add_nginx_conf_rules', 'add_shop_nginx_conf_rules', 10, 3);
add_replace('add_mod_rewrite_rules', 'add_shop_mod_rewrite_rules', 10, 3);
add_replace('admin_dbupgrade', 'add_shop_admin_dbupgrade', 10, 3);
add_replace('exist_check_seo_title', 'shop_exist_check_seo_title', 10, 4);

// 메인 상품 전시
function get_main_banner_item($order=0, $ca_position="메인",$img_width="285",$img_height="285"){
	//global $ca_id_slt;
	$ca_id_slt = array(
	/*
	"10" => "온라인강의",
	"20" => "패키지강의",
	"30" => "프리패스",
	*/
	
	"10" => "English",
	"20" => "Chinese",
	"30" => "Coding",
	"40" => "맞춤강의",
	"50" => "1:1 강의"
);

	$sQuery = " SELECT *
				FROM han_shop_banner_cate
				WHERE ca_position = '".$ca_position."'
				AND   ca_item_order = '{$order}'
				ORDER BY ca_item_order ASC, ca_id DESC
				LIMIT 1";
	$rst = sql_fetch($sQuery);

	if($rst){
		echo'<div class="row">'.PHP_EOL;
		echo'	<div class="col-md-12">'.PHP_EOL;
		echo'		<h2>'.$rst['ca_title'].'</h2>'.PHP_EOL;
		if($rst['ca_url'] != ""){ // See more
			//echo shop_type_url('4');
			echo'		<div class="pull-right btn_more"><a href="'.$rst['ca_url'].'">See more</a></div>'.PHP_EOL;
		}
		echo'		<p class="lead">'.$rst['ca_s_title'].'</p>'.PHP_EOL;
		echo'	</div>'.PHP_EOL;
		echo'</div>'.PHP_EOL;

		echo'<div class="row">'.PHP_EOL;

		for ($j=1; $j <= 4; $j++) {
			$item_code = "ca_item_code".$j;
			$it_id = $rst[$item_code];
			$it = get_item($it_id);

			$item_link_href = shop_item_url($it_id);
			$star_score = $row['it_use_avg'] ? (int) get_star($row['it_use_avg']) : '';
			$star_score = (!$it['it_score'])? $star_score:$it['it_score'];

			echo "<div class=\"col-lg-3 col-md-6 col-sm-6 list-item\">\n";

			// 연동된 튜터영상이 있을경우 LHH 2021-03-22
			/*
			if($it["it_1"]){
				echo "<a href=\"".G5_BBS_URL."/board.php?bo_table=tutor&wr_id=".$it["it_1"]."\">\n";
			}else{
				echo "<a href=\"{$item_link_href}\">\n";
			}
			*/
			echo "<a href=\"{$item_link_href}\">\n";
			echo'<div class="col-item">'.PHP_EOL;
			echo'	<span class="ribbon_course">'.PHP_EOL;

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

			echo'	</span>'.PHP_EOL;
			echo'	<div class="photo">'.PHP_EOL;
					echo get_it_image($it_id, $img_width, $img_height, '', '', stripslashes($it['it_name']))."\n";
			echo'	</div>'.PHP_EOL;
			echo'	<div class="info">'.PHP_EOL;
			echo'		<div class="row">'.PHP_EOL;
			echo'			<div class="course_info col-md-12 col-sm-12">'.PHP_EOL;
			echo'				<h4>'.stripslashes($it['it_name']).'</h4>'.PHP_EOL;
			echo'				<div class="rating">'.PHP_EOL;

							// 사용후기 평점표시
							get_rating($star_score);

			echo'				</div>'.PHP_EOL;
			echo'				<div class="clearfix">'.PHP_EOL;
			echo'					<span class="pull-left teacher">'.$it['it_t_name'].' | '.$ca_id_slt[$it['ca_id']].'</span>'.PHP_EOL;
			echo'					<span class="pull-right price">'.PHP_EOL;

									echo "<div class=\"sct_cost\">\n";
									echo display_price(get_price($it), $it['it_tel_inq'])."\n";
									if ($it['it_cust_price']) {
										//echo "<span class=\"sct_dict\">".display_price($row['it_cust_price'])."</span>\n";
									}
									echo "</div>\n";

			echo'					</span>'.PHP_EOL;
			echo'				</div>'.PHP_EOL;
			echo'			</div>'.PHP_EOL;
			echo'		</div>'.PHP_EOL;

			echo'	</div>'.PHP_EOL;
			echo'</div>'.PHP_EOL;

				echo "</a>\n";
			echo "</div>\n";
		}
		echo'</div>'.PHP_EOL;
	}
}

function get_tutor_list(){
	$html = "";
	$tutor_sql = "SELECT * FROM han_write_tutor WHERE wr_3 = 'Y' ORDER BY wr_id LIMIT 4";
	$tutor_res = sql_query($tutor_sql);


	$html .='<br><br><br>
				<div class="row">
					<div class="col-md-12">
						<h2>Tutor Sample Video</h2>
						<div class="pull-right btn_more"><a href="/poll_tutor.php">See more</a></div>
						<p class="lead"></p>
					</div>
				</div>';

	$html .= "<div class='row'>";
	for($i=0; $row = sql_fetch_array($tutor_res); $i++){
		$vimeo_key_arr = explode("/",$row["wr_link1"]);
		$vimeo_key = $vimeo_key_arr[3];

		$thumb = get_list_thumbnail("tutor", $row["wr_id"], "270", "270", true, true);
		if($thumb['src']) {
			$img_content = '<img src="'.$thumb['src'].'" alt="'.$thumb['alt'].'" >';
		} else {
			$img_content = '<span class="no_image">no image</span>';
		}

		$html .= "
		<div class='col-lg-3 col-md-6 col-sm-6 list-item'>
			<a href='".G5_BBS_URL."/board.php?bo_table=tutor&wr_id=".$row["wr_id"]."'>
				<div class='col-item'>
					<span class='ribbon_course'>
					</span>
					<div class='photo'>
						".$img_content."
					</div>
					<div class='info'>
						<div class='row'>
							<div class='course_info col-md-12 col-sm-12'>
								<h4>".$row["wr_subject"]."</h4>
							</div>
						</div>
					</div>
				</div>
			</a>
		</div>
		";
	}
	$html .= "</div>";
	return $html;
}
?>