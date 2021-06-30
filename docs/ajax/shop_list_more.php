<?php
include_once("./_common.php");

// 상품 출력순서가 있다면
if ($sort != "")
	$order_by = $sort.' '.$sortodr.' , it_order, it_id desc';
else
	$order_by = 'it_order desc, it_id desc';

// 총몇개 = 한줄에 몇개 * 몇줄
$ca['ca_list_mod'] = "4"; // 몇개
$ca['ca_list_row'] = "4"; // 몇줄
$items = $ca['ca_list_mod'] * $ca['ca_list_row'];
// 페이지가 없으면 첫 페이지 (1 페이지)
if ($page < 1) $page = 1;
// 시작 레코드 구함
$from_record = ($page - 1) * $items;

$where = array();
$where[] = " it_use = '1' ";
if($it_level){
	$it_level = substr($it_level, 1);
	$where[] = " it_level IN (".$it_level.") ";
}

$sql_having = "";
if($it_type){
	$it_type = substr($it_type, 1);
	$arr_type = explode(",", $it_type);
	for($i = 0; $i < count($arr_type); $i++){
		if($arr_type[$i] == "it_dc_rate"){
			$where[] = " it_dc_rate = '20' ";
		}else if($arr_type[$i] == "it_type17"){
			$where[] = " it_type17 = '1' ";
		}else if($arr_type[$i] == "it_coupon"){
			$add_sql = ", ( SELECT it_id FROM han_shop_coupon WHERE cp_target = han_shop_item.it_id ) AS it_coupon";
			$sql_having = " HAVING it_coupon != '' ";
		}
	}
}

if ($ca_id) {
	$where_ca_id[] = " ca_id2 like '{$ca_id}%' ";
	$where_ca_id[] = " ca_id3 like '{$ca_id}%' ";
	$where[] = " ( " . implode(" or ", $where_ca_id) . " ) ";
}

if ($type) {
	$where[] = " it_type{$type} = '1' ";
}

$sql_order = " order by {$order_by} ";

if ($event) {
	$sql_select = " select * ";
	$sql_common = " from `{$g5['g5_shop_event_item_table']}` a left join `{$g5['g5_shop_item_table']}` b on (a.it_id = b.it_id) ";
	$where[] = " a.ev_id = '{$event}' ";
} else {
	$sql_select = " select * ".$add_sql;
	$sql_common = " from `{$g5['g5_shop_item_table']}` ";
}
$sql_where = " where " . implode(" and ", $where);
$sql_limit = " limit " . $from_record . " , " . $items;

// 전체 페이지 계산
$sql = $sql_select . $sql_common . $sql_where . $sql_having;
$total_count = sql_num_rows(sql_query($sql));

$total_page  = ceil($total_count / $items);
echo $total_page."||";

$sql = $sql_select . $sql_common . $sql_where . $sql_having . $sql_order . $sql_limit;
$result = sql_query($sql);

if( isset($result) && $result ){
	while ($row=sql_fetch_array($result)) {
		if( isset($row['it_seo_title']) && ! $row['it_seo_title'] ){
			shop_seo_title_update($row['it_id']);
		}
		$list[] = $row;
	}
}

foreach((array) $list as $row){
    if( empty($row) ) continue;

	$it = get_item($row['it_id']);

    $item_link_href = shop_item_url($row['it_id']);     // 상품링크
    $star_score = $row['it_use_avg'] ? (int) get_star($row['it_use_avg']) : '';     //사용자후기 평균별점
	$star_score = (!$it['it_score'])? $star_score:$it['it_score']; // 관리자에서 입력한 값이 있는 경우 우선
    $list_mod = $ca['ca_list_mod'];    // 분류관리에서 1줄당 이미지 수 값 또는 파일에서 지정한 가로 수
    $is_soldout = is_soldout($row['it_id'], true);   // 품절인지 체크

    $classes = array();

    $classes[] = 'col-row-'.$list_mod;

    if( $i && ($i % $list_mod == 0) ){
        $classes[] = 'row-clear';
    }

    $i++;   // 변수 i 를 증가

	$img_width = "285";
	$img_height = "285";

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
					echo "<a href=\"{$item_link_href}\">\n";
					echo get_it_image($row['it_id'], $img_width, $img_height, '', '', stripslashes($row['it_name']))."\n";
					echo "</a>\n";
	//echo'			<a href="/sub/view/"><img src="/img/item285x285.png" alt=""></a>';
	echo'		</div>';
	echo'		<div class="info">';
	echo'			<div class="row">';
	echo'				<div class="course_info col-md-12 col-sm-12">';
	echo'					<h4>';
							echo stripslashes($row['it_name'])."\n";
	echo'					</h4>';
	echo'					<div class="rating">';
							// 사용후기 평점표시
							$t_star_score = (5 - $star_score);
							for($i = 0; $i < $star_score; $i++){
								// <i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class=" icon-star-empty icon-star-half"></i>
								echo'<i class="icon-star"></i>';
							}
							for($i = 0; $i < $t_star_score; $i++){
								// <i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class=" icon-star-empty icon-star-half"></i>
								echo'<i class="icon-star-empty"></i>';
							}
	//echo'					<i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class=" icon-star-empty"></i>';
	echo'					</div>';
	echo'					<div class="clearfix">';
	echo'						<span class="pull-left teacher">'.$it['it_t_name'].' | '.$ca_id_slt[$it['ca_id']].'</span>';
	echo'						<span class="pull-right price">';
								echo "<div class=\"sct_cost\">\n";
								echo display_price(get_price($row), $row['it_tel_inq'])."\n";
								echo "</div>\n";
	echo'						</span>';
	echo'					</div>';

	echo'				</div>';
	echo'			</div>';

	echo'		</div>';
	echo'	</div>';
	echo'</div>';
}

if($total_count === 0) echo "<p class=\"sct_noitem\">등록된 상품이 없습니다.</p>\n";
?>