<?php
include_once("./_common.php");

if($func == "add_bn_main"){ // 배너 관리
	echo'<tr>';
	echo'	<td>';
	echo'		<input type="hidden" name="bn_id[]" value="" />';
	echo'		<input type="hidden" name="bn_position[]" value="메인" />';
	echo'		<input type="hidden" name="bn_device[]" value="both" />';
	echo'		<input type="text" name="bn_alt[]" value="" id="bn_alt" class="frm_input" size="50">';
	echo'	</td>';
	echo'	<td>';
	echo'		<input type="file" name="bn_bimg[]">';
	echo'		<span style="font-size: 10px;color: #3c3c3c;">PC 기준 사이즈 : 1920px X 563px<br>Mo 기준 사이즈 : 640px X 563px</span>';
	echo'	</td>';
	echo'	<td>';
	echo'		<input type="text" name="bn_url[]" size="50" value="" id="bn_url" class="frm_input">';
	echo'	</td>';
	echo'	<td>';
	echo'		<select name="bn_device[]">';
	echo'			<option value="both">PC와 모바일</option>';
	echo'			<option value="pc">PC</option>';
	echo'			<option value="mobile">모바일</option>';
	echo'		</select>';
	echo'	</td>';
	echo'	<td>';
	echo'		<select name="bn_use[]">';
	echo'			<option value="1">노출</option>';
	echo'			<option value="0">비노출</option>';
	echo'		</select>';
	echo'	</td>';
	echo'	<td>';
	echo'		<input type="text" name="bn_order[]" value="" class="frm_input" size="10" />';
	echo'	</td>';
	echo'	<td>';
	echo'		<select name="bn_new_win[]">';
	echo'			<option value="0">사용안함</option>';
	echo'			<option value="1">사용</option>';
	echo'		</select>';
	echo'	</td>';
	echo'	<td><a href="javascript:void(0);" class="btn btn_03">작성중</a></td>';
	echo'</tr>';
}else if($func == "add_bn_main_cate"){ // 배너 관리
	echo'<tr>';
	echo'	<td colspan="5">';
	echo'		<input type="hidden" name="ca_no[]" value="" />';
	echo'		<input type="hidden" name="ca_position[]" value="메인" />';
	echo'		<input type="text" name="ca_title[]" value="" class="frm_input" placeholder="카테고리 타이틀" size="80" />';
	echo'	</td>';
	echo'</tr>';
	echo'<tr>';
	echo'	<td colspan="5"><input type="text" name="ca_s_title[]" value="" class="frm_input" placeholder="카테고리 서브 타이틀" size="80" /></td>';
	echo'</tr>';
	echo'<tr>';
	echo'	<td colspan="5"><input type="text" name="ca_url[]" value="" class="frm_input" placeholder="더보기 링크" size="80" /></td>';
	echo'</tr>';
	echo'<tr>';
	echo'	<th>노출1 : 판매용 강의 코드</th>';
	echo'	<th>노출2 : 판매용 강의 코드</th>';
	echo'	<th>노출3 : 판매용 강의 코드</th>';
	echo'	<th>노출4 : 판매용 강의 코드</th>';
	echo'	<th>카테고리 순서</th>';
	echo'</tr>';
	echo'<tr class="border-bottom-bold">';
	echo'	<td><input type="text" name="ca_item_code1[]" value="" class="frm_input" /></td>';
	echo'	<td><input type="text" name="ca_item_code2[]" value="" class="frm_input" /></td>';
	echo'	<td><input type="text" name="ca_item_code3[]" value="" class="frm_input" /></td>';
	echo'	<td><input type="text" name="ca_item_code4[]" value="" class="frm_input" /></td>';
	echo'	<td><input type="text" name="ca_item_order[]" value="" class="frm_input" /></td>';
	echo'</tr>';
}else if($func == "add_bn_cate_lsit"){ // 배너 관리
	// 분류
	$ca_list  = '<option value="">선택</option>'.PHP_EOL;
	$sql = " select * from {$g5['g5_shop_category_table']} ";
	if ($is_admin != 'super')
		$sql .= " where ca_mb_id = '{$member['mb_id']}' ";
	$sql .= " order by ca_order, ca_id ";
	$result = sql_query($sql);
	for ($i=0; $row=sql_fetch_array($result); $i++)
	{
		$len = strlen($row['ca_id']) / 2 - 1;
		$nbsp = '';
		for ($i=0; $i<$len; $i++) {
			$nbsp .= '&nbsp;&nbsp;&nbsp;';
		}
		$ca_list .= '<option value="'.$row['ca_id'].'">'.$nbsp.$row['ca_name'].'</option>'.PHP_EOL;
	}

	echo'<tr>';
	echo'	<td colspan="4">';
	echo'		<select name="ca_id[]" id="">';
	echo'			<option value="">선택하세요</option>';
	echo'			'.conv_selected_option($ca_list, $row['ca_id']);
	echo'		</select>';
	echo'	</td>';
	echo'</tr>';
	echo'<tr>';
	echo'	<td colspan="4">';
	echo'		<input type="hidden" name="ca_no[]" value="" />';
	echo'		<input type="hidden" name="ca_position[]" value="서브" />';
	echo'		<input type="text" name="ca_title[]" value="" class="frm_input" placeholder="카테고리 타이틀" size="80" />';
	echo'		<input type="text" name="ca_color[]" value="" class="frm_input" placeholder="색상코드 예)#ff6400" size="20" />';
	echo'	</td>';
	echo'</tr>';
	echo'<tr>';
	echo'	<td colspan="4"><input type="text" name="ca_s_title[]" value="" class="frm_input" placeholder="카테고리 서브 타이틀" size="80" /></td>';
	echo'</tr>';
	echo'<tr>';
	echo'	<th>노출1 : 판매용 강의 코드</th>';
	echo'	<th>노출2 : 판매용 강의 코드</th>';
	echo'	<th>노출3 : 판매용 강의 코드</th>';
	echo'	<th>노출4 : 판매용 강의 코드</th>';
	echo'</tr>';
	echo'<tr>';
	echo'	<td><input type="text" name="ca_item_code1[]" value="" class="frm_input" /></td>';
	echo'	<td><input type="text" name="ca_item_code2[]" value="" class="frm_input" /></td>';
	echo'	<td><input type="text" name="ca_item_code3[]" value="" class="frm_input" /></td>';
	echo'	<td><input type="text" name="ca_item_code4[]" value="" class="frm_input" /></td>';
	echo'</tr>';
	echo'<tr>';
	echo'	<th>노출5 : 판매용 강의 코드</th>';
	echo'	<th>노출6 : 판매용 강의 코드</th>';
	echo'	<th>노출7 : 판매용 강의 코드</th>';
	echo'	<th>노출8 : 판매용 강의 코드</th>';
	echo'</tr>';
	echo'<tr class="border-bottom-bold">';
	echo'	<td><input type="text" name="ca_item_code5[]" value="" class="frm_input" /></td>';
	echo'	<td><input type="text" name="ca_item_code6[]" value="" class="frm_input" /></td>';
	echo'	<td><input type="text" name="ca_item_code7[]" value="" class="frm_input" /></td>';
	echo'	<td><input type="text" name="ca_item_code8[]" value="" class="frm_input" /></td>';
	echo'</tr>';
}else if($func == "add_keyword"){ // 키워드 등록
	$it_keyword = get_text(strip_tags($it_keyword));

	$sQuery = " SELECT *
				FROM han_shop_item
				WHERE it_id = '".$it_id."'
				AND   it_keyword LIKE '%".$it_keyword."||%'
				";
	$rst = sql_fetch($sQuery);

	if($rst){
		echo"same";
		exit;
	}else{
		$sQuery = " UPDATE han_shop_item
					SET it_keyword = CONCAT(it_keyword, '".$it_keyword."', '||')
					WHERE it_id = '".$it_id."'
					";
		sql_query($sQuery);
	}
}else if($func == "del_keyword"){ // 키워드 삭제
	$it_keyword = get_text(strip_tags($it_keyword));

	$sQuery = " SELECT *
				FROM han_shop_item
				WHERE it_id = '".$it_id."'
				";
	$rst = sql_fetch($sQuery);

	$keyword = explode("||",$rst['it_keyword']);

	$arr_key = array();
	for($i = 0; $i < count($keyword); $i++){
		if(!$keyword[$i])
			continue;

		if($it_keyword == $keyword[$i])
			continue;

		$arr_key[] = $keyword[$i];
	}

	$arr_key = implode("||", $arr_key);
	$sQuery = " UPDATE han_shop_item
				SET it_keyword = '".$arr_key."'
				WHERE it_id = '".$it_id."'
				";
	sql_query($sQuery);
}else if($func == "it_id_op"){
	$sQuery = " SELECT *
				FROM han_shop_item
				WHERE (ca_id LIKE '".$ca_id."%' OR ca_id2 LIKE '".$ca_id."%' OR ca_id3 LIKE '".$ca_id."%')
				ORDER BY it_order DESC, it_id DESC
				";
	$sql = sql_query($sQuery);

	for($i = 0; $row = sql_fetch_array($sql); $i++){
		echo"<option value='".$row['it_id']."'>".$row['it_name']."</option>";
	}
}
?>