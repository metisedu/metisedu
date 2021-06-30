<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 상품 구매 완료시 카트아이디 값으로 상품 아이디 추적해서 포인트 분배 LHH 2021-02-25
function metis_point_split($od_id, $before_status){

	$cart_sql = "SELECT * FROM han_shop_cart WHERE od_id = '".$od_id."' ";
	$cart_res = sql_query($cart_sql);
	
	while($row = sql_fetch_array($cart_res)){
		$cart_data = $row;
	
		// 취소 상태에서 -> 완료 하면 포인트 적립
		// 완료 상태에서 -> 완료 다시 한번 하면 포인트 적립 불가
		// 포인트 내역에 같은 od_id, 및 cart_id로 적립된게 있나 중복 확인 LHH 2021-03-05
		$duple_po_sql = " SELECT * FROM han_point WHERE po_rel_cart_id = '".$cart_data['ct_id']."' AND po_rel_od_id = '".$od_id."' LIMIT 1";
		$duple_po_row = sql_fetch($duple_po_sql);
		if($duple_po_row["po_id"] && $before_status != "취소"){ // 취소였던 주문건을 완료로 다시 처리했을때 포인트 다시 적립 해주기 위해서 수정전 od_status가 취소가 아닌경우엔 continue, 취소에서 완료 누른거라면 적립 됨. LHH 2021-03-08
			continue;
		}
		

		// 구매상품 정보(퍼센트요율 가져오기) LHH 2021-02-25
		$item_info_sql = "SELECT * FROM han_shop_item WHERE it_id = '".$cart_data["it_id"]."' ";
		$item_info_data = sql_fetch($item_info_sql);
		
		// 상품 it_1값에 물려있는 컨텐츠제공자(튜터글작성자) 아이디 가져오기 
		$board_tutor_info_sql = "SELECT * FROM han_write_tutor WHERE wr_id = '".$item_info_data["it_1"]."' ";
		$board_tutor_info_data = sql_fetch($board_tutor_info_sql);
		$board_tutor_offer_id = $board_tutor_info_data["mb_id"];

		// 구매자의 추천아이디 가져오기
		$payer_data = get_member($cart_data["mb_id"]);
		$recommend_id = $payer_data["mb_recommend"];
		
		
		// 튜터글 '좋아요' 누른 리스트 가져오기
		$vote_list = get_board_good_list($item_info_data["it_1"]);
		
		// 금액 * 수량
		$item_price = $cart_data["ct_price"] * $cart_data["ct_qty"];

		$it_per_platform = $item_info_data["it_per_platform"];
		$it_per_offerer = $item_info_data["it_per_offerer"];
		$it_per_recommend = $item_info_data["it_per_recommend"];
		$it_per_payer = $item_info_data["it_per_payer"];
		$it_per_voter = $item_info_data["it_per_voter"];

		

		// 플랫폼 아이디
		$flat_id = "admin";
		$flat_point = get_percent($item_price, $it_per_platform);
		
		// 컨텐츠제공자
		$offer_id = $board_tutor_offer_id;
		$offer_point = get_percent($item_price, $it_per_offerer);
		
		// 추천인
		$recom_id = $recommend_id;
		$recom_point = get_percent($item_price, $it_per_recommend);
		
		// 결제자
		$payer_id = $payer_data["mb_id"];
		$payer_point = get_percent($item_price, $it_per_payer);
		
		// 퍼센트 금액 나누기 투표자리스트 명수
		$vote_point = get_percent($item_price, $it_per_voter) / count($vote_list) ;

		
		//플랫폼
		if($it_per_platform > 0) insert_point2($flat_id, $flat_point, $item_info_data["it_name"].' 구매-플랫폼 포인트 '.$it_per_platform."%", '@passive', $flat_id, $payer_id.'-'.uniqid(''), $expire, $cart_data["ct_id"], $od_id, $item_info_data["it_id"], 0, $it_per_platform);
		//컨텐츠제공자(튜터)
		if($it_per_offerer > 0) insert_point2($offer_id, $offer_point, $item_info_data["it_name"].' 구매-컨텐츠제공자(튜터) 포인트 '.$it_per_offerer."%", '@passive', $offer_id, $payer_id.'-'.uniqid(''), $expire, $cart_data["ct_id"], $od_id, $item_info_data["it_id"], 1, $it_per_offerer);
		//추천인(결제자 가입시 입력한 추천인)
		if($it_per_recommend > 0) insert_point2($recom_id, $recom_point, $item_info_data["it_name"].' 구매-추천인 포인트 '.$it_per_recommend."%", '@passive', $recom_id, $payer_id.'-'.uniqid(''), $expire, $cart_data["ct_id"], $od_id, $item_info_data["it_id"], 2, $it_per_recommend);
		//결제자(구매자)
		if($it_per_payer > 0) insert_point2($payer_id, $payer_point, $item_info_data["it_name"].' 구매-결제자 포인트 '.$it_per_payer."%", '@passive', $payer_id, $payer_id.'-'.uniqid(''), $expire, $cart_data["ct_id"], $od_id, $item_info_data["it_id"], 3, $it_per_payer);
		
		//투표자(상품-강의 에 연결 되어있는 튜터게시글에 추천 누른 사람들 엔빵)
		if($it_per_voter > 0){
			for($i=0; $i<count($vote_list); $i++){
				update_good_od_id($vote_list[$i]["bg_id"], $od_id);
				insert_point2($vote_list[$i]["mb_id"], $vote_point, $item_info_data["it_name"]." 구매-튜터 투표자 배분 ".$it_per_voter."% ".count($vote_list)."명 1/n" , '@passive', $vote_list[$i]["mb_id"], $payer_id.'-'.uniqid(''), $expire, $cart_data["ct_id"], $od_id, $item_info_data["it_id"], 4, $it_per_voter);
			}
		}

		// 모든 관련된 유저 포인트 지급 후 퍼센트 합이 100% 안넘을 경우 나머지 퍼센트는 플랫폼에게 다시 지금하기. ( 추천인 없을 경우, 투표자 없을 경우 ) 대응 LHH 2021-03-08
		$per_tot = $it_per_platform + $it_per_offerer + $it_per_recommend + $it_per_payer + $it_per_voter;
		if($per_tot < 100){
			$rest_per = 100 - $per_tot;
			$rest_point = get_percent($item_price, $rest_per);
			//플랫폼
			if($rest_per > 0) insert_point2($flat_id, $rest_point, $item_info_data["it_name"].' 구매-플랫폼 +a 포인트 '.$rest_per."%", '@passive', $flat_id, $payer_id.'-'.uniqid(''), $expire, $cart_data["ct_id"], $od_id, $item_info_data["it_id"], 0, $rest_per);
		
		}
		//echo $per_tot;
		//exit;

	}

	/*

	echo "<BR>";
	echo "<BR>it_per_platform ";
	echo get_percent($item_price, $it_per_platform);
	echo "<BR>";
	echo $it_per_platform;
	
	
	echo "<BR>";
	echo "<BR>it_per_offerer ";
	echo get_percent($item_price, $it_per_offerer);
	echo "<BR>";
	echo $it_per_offerer;
	
	
	echo "<BR>";
	echo "<BR>it_per_recommend ";
	echo get_percent($item_price, $it_per_recommend);
	echo "<BR>";
	echo $it_per_recommend;
	
	
	echo "<BR>";
	echo "<BR>it_per_payer ";
	echo get_percent($item_price, $it_per_payer);
	echo "<BR>";
	echo $it_per_payer;
	
	
	echo "<BR>";
	echo "<BR>it_per_voter ";
	echo get_percent($item_price, $it_per_voter);
	echo "<BR>";
	echo $it_per_voter;
	
	echo "<BR>";
	echo "<BR>";
	echo "<BR>";
	
	
	echo get_percent($item_price, $it_per_voter) / count($vote_list) ;
	
	echo "<BR>";
	echo "<BR>";
	echo "플랫폼 : ".$flat_id."<br>";
	echo "추천인아이디 : ".$recom_id."<br>";
	echo "구매자아이디 : ".$payer_id."<br>";
	echo "투표자리스트 : <br>";
	echo "<BR>";
	echo "구매금액 : ".$item_price;
	exit;
	*/
}

// 상품 구매 취소시 적립된 포인트 기준으로 다 취소 LHH 2021-03-05
function metis_point_split_cancel($od_id){

	$cart_sql = "SELECT * FROM han_shop_cart WHERE od_id = '".$od_id."' ";
	$cart_res = sql_query($cart_sql);
	
	while($row = sql_fetch_array($cart_res)){
		$cart_data = $row;
	
		// 포인트 내역에 같은 od_id, 및 cart_id로 적립된게 있나 중복 확인 LHH 2021-03-05
		$po_sql = " SELECT * FROM han_point WHERE po_rel_cart_id = '".$cart_data['ct_id']."' AND po_rel_od_id = '".$od_id."' ";
		$po_res = sql_query($po_sql);
		for($i=0; $row = sql_fetch_array($po_res); $i++) {
			echo $cancel_point = $row["po_point"] * (-1);
			echo "<BR>";
			print_R($row);
			echo "<BR>";
			echo "<BR>";
			
			insert_point2($row["mb_id"], $cancel_point, $row["it_name"].' 구매 포인트 취소 ', '@passive', $row["mb_id"], $row["mb_id"].'-'.uniqid(''), $expire, $row["po_rel_cart_id"], $row["pr_rel_od_id"], $row["pr_rel_it_id"]);
		}
		
		
		/*
		//플랫폼
		insert_point2($flat_id, $flat_point, $item_info_data["it_name"].' 구매-플랫폼 포인트 '.$it_per_platform."%", '@passive', $flat_id, $payer_id.'-'.uniqid(''), $expire, $cart_data["ct_id"], $od_id, $item_info_data["it_id"]);
		//컨텐츠제공자(튜터)
		insert_point2($offer_id, $offer_point, $item_info_data["it_name"].' 구매-컨텐츠제공자(튜터) 포인트 '.$it_per_offerer."%", '@passive', $offer_id, $payer_id.'-'.uniqid(''), $expire, $cart_data["ct_id"], $od_id, $item_info_data["it_id"]);
		//추천인(결제자 가입시 입력한 추천인)
		insert_point2($recom_id, $recom_point, $item_info_data["it_name"].' 구매-추천인 포인트 '.$it_per_recommend."%", '@passive', $recom_id, $payer_id.'-'.uniqid(''), $expire, $cart_data["ct_id"], $od_id, $item_info_data["it_id"]);
		//결제자(구매자)
		insert_point2($payer_id, $payer_point, $item_info_data["it_name"].' 구매-결제자 포인트 '.$it_per_payer."%", '@passive', $payer_id, $payer_id.'-'.uniqid(''), $expire, $cart_data["ct_id"], $od_id, $item_info_data["it_id"]);
		
		//투표자(상품-강의 에 연결 되어있는 튜터게시글에 추천 누른 사람들 엔빵)
		for($i=0; $i<count($vote_list); $i++){
			update_good_od_id($vote_list[$i]["bg_id"], $od_id);
			insert_point2($vote_list[$i]["mb_id"], $vote_point, $item_info_data["it_name"]." 구매-튜터 투표자 배분 ".$it_per_voter."% ".count($vote_list)."명 1/n" , '@passive', $vote_list[$i]["mb_id"], $payer_id.'-'.uniqid(''), $expire, $cart_data["ct_id"], $od_id, $item_info_data["it_id"]);
		}
		*/
	}

}

// 지정된 퍼센트 요율에 따른 구매값 배분 LHH 2021-02-26
function get_percent($price, $percent){
	return $price * $percent / 100;
}

//tutor 게시판 good 누른 사람 리스트 가져오기 LHH 2021-02-26
function get_board_good_list($wr_id){
	// 튜터글 '좋아요' 누른 리스트 가져오기
	$vote_list_sql = "SELECT * FROM han_board_good WHERE bo_table = 'tutor' AND bg_flag = 'good' AND wr_id = '".$wr_id."'";
	$vote_list_res = sql_query($vote_list_sql);
	while($row = sql_fetch_array($vote_list_res)){
		$vote_list[] = $row;
	}

	return $vote_list;
}

// 결제완료 떨어질때 good 테이블에 주문번호 기록 LHH 2021-02-26
function update_good_od_id($bg_id, $od_id){
	$sql = "UPDATE han_board_good SET bg_od_id = '".$od_id."' WHERE bg_id = '".$bg_id."' ";
	sql_query($sql);
}

// 좋아요 누를때 튜터에 연동된 상품 좋아여 컬럼에 추가하기. LHH 2021-02-26
function item_good_list_merge($it_id, $good_voter_id){

	$it_upd_data = sql_fetch("SELECT * FROM han_shop_item WHERE it_id = '".$it_id."'");
	if($it_upd_data["it_good_list"]){
		$good_list = implode("," ,array_filter(explode(",", $it_upd_data["it_good_list"]))).",".$good_voter_id;
	}else{
		$good_list = $good_voter_id;
	}
	sql_query("UPDATE han_shop_item SET it_good_list = '".$good_list."' WHERE it_id ='".$it_upd_data["it_id"]."' ");
	
}

function get_grade($grade){
	switch($grade){
		case "0" : $res = "플랫폼"; break;
		case "1" : $res = "컨텐츠제공자(튜터)"; break;
		case "2" : $res = "추천인"; break;
		case "3" : $res = "결제자"; break;
		case "4" : $res = "투표자"; break;
		default : $res = "기타"; break;
	}
	return $res;
}
?>