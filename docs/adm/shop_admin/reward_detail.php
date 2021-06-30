<?php
$sub_menu = "300110";
include_once('./_common.php');
$g5['title'] = '리워드상세';
include_once (G5_ADMIN_PATH.'/admin.head.php');

auth_check($auth[$sub_menu], 'r');



$colspan = 9;

if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = '';
if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = '';


$qstr = "token=".$token."&amp;fr_date=".$fr_date."&amp;to_date=".$to_date."&amp;stx=".$stx."&amp;sfl=".$sfl;
$query_string = $qstr ? '?'.$qstr : '';





// 주문건의 카트 아이템별로 정보가져오기 LHH 2021-03-05
$cart_sql = "SELECT * FROM han_shop_cart WHERE od_id = '".$od_id."'"; 
$cart_res = sql_query($cart_sql);

for($i=0; $row = sql_fetch_array($cart_res); $i++){
	$list[$i] = $row;
	// 해당 주문건에 의해 적립받은 유저값 쌓기 LHH 2021-03-05
	$po_sql = "SELECT * FROM han_point WHERE po_rel_it_id = '".$list[$i]["it_id"]."' AND po_rel_od_id = '".$od_id."'  ORDER BY po_order ASC";
	$po_res = sql_query($po_sql);
	while($po_row = sql_fetch_array($po_res)){
		$list2[] = $po_row;
	}
}
/*
echo "<PRE>";
print_r($list2);
echo "</PRE>";
*/

?>		

<form name="frm" id="frm" action="./pay_request_update.php" onsubmit="return frm_submit(this);" method="post">
	<input type="hidden" name="sst" value="<?php echo $sst ?>">
	<input type="hidden" name="sod" value="<?php echo $sod ?>">
	<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
	<input type="hidden" name="stx" value="<?php echo $stx ?>">
	<input type="hidden" name="page" value="<?php echo $page ?>">
	<input type="hidden" name="qstr" value="<?php echo $qstr ?>">
	<input type="hidden" name="token" value="<?php echo $token ?>">

	<div class="tbl_head01 tbl_wrap">
		<table>
		<caption><?php echo $g5['title']; ?> 목록</caption>
		<thead>
		<tr>
			<!-- <th scope="col">
				<label for="chkall" class="sound_only">게시판 전체</label>
				<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
			</th> -->

			<th scope="col">분류</th>
			<th scope="col">퍼센트율</th>
			<th scope="col">ID</th>
			<th scope="col">적립일</th>
			<th scope="col">적립내용</th>
			<th scope="col">상품이름</th>
			
		</tr>
		</thead>
		<tbody>
		<?php

		for ($i=0; $i<count($list2); $i++) {
			$grade = get_grade($list2[$i]["po_order"]);
		?>
		<tr class="<?php echo $bg; ?>">
			<!-- <td class="td_chk">
				<input type="hidden" name="ac_no[<?php echo $i ?>]" value="<?php echo $row['ac_no'] ?>">
				<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['ac_no']) ?></label>
				<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
			</td> -->

			<td class=""><?php echo $grade; ?></td>
			<td class=""><?php echo $list2[$i]['po_per']?>%</td>
			<td class=""><?php echo $list2[$i]['mb_id']?></td>
			<td class=""><?php echo $list2[$i]['po_datetime']?></td>
			<td class=""><?php echo $list2[$i]['po_content']?></td>
			<td class=""><?php echo number_format($list2[$i]['po_point'])?>원</td>
			
		</tr>
		<?php
		}
		if ($i == 0)
			echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없거나 관리자에 의해 삭제되었습니다.</td></tr>';
		?>
		</tbody>
		</table>
	</div>



</form>


<?php
$qstr .= "&amp;page=";

$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr");
echo $pagelist;
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
