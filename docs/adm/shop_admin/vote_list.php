<?php
$sub_menu = "200451";
include_once('./_common.php');
$g5['title'] = '투표자리스트';
include_once (G5_ADMIN_PATH.'/admin.head.php');

auth_check($auth[$sub_menu], 'r');



$colspan = 9;

if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = '';
if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = '';


$qstr = "token=".$token."&amp;fr_date=".$fr_date."&amp;to_date=".$to_date."&amp;stx=".$stx."&amp;sfl=".$sfl;
$query_string = $qstr ? '?'.$qstr : '';





$sql_common = " from han_board_good ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'pr_pay_name' : 
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        case 'cl_car_number' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
      
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

$sql_search .= " AND bg_od_id = '".$od_id."' ";
$sql_search .= " AND bg_flag = 'good' ";

if (!$sst) {
    $sst  = "bg_id";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";





$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 20;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *
            {$sql_common}
            {$sql_search}
            {$sql_order}
            limit {$from_record}, {$rows} ";
$result = sql_query($sql);



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
			<th scope="col" id="mb_list_id">번호</th>
			<th scope="col">투표일</th>
			<th scope="col">ID</th>
			<th scope="col">적립일</th>
			<th scope="col">내용</th>
			<th scope="col">수수료(투표자 1/N)</th>
			<th scope="col">상품이름</th>
		</tr>
		</thead>
		<tbody>
		<?php
		for ($i=0; $row=sql_fetch_array($result); $i++) {
		
			$bg = 'bg'.($i%2);
			
			$point_sql = "SELECT * FROM han_point WHERE mb_id = '".$row["mb_id"]."' AND po_rel_od_id = '".$od_id."'";
			$point_data = sql_fetch($point_sql);

			$item_sql = "SELECT *FROM han_shop_item WHERE it_id = '".$point_data["po_rel_it_id"]."'";
			$item_data = sql_fetch($item_sql);
			
		?>
		<tr class="<?php echo $bg; ?>">
			<!-- <td class="td_chk">
				<input type="hidden" name="ac_no[<?php echo $i ?>]" value="<?php echo $row['ac_no'] ?>">
				<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['ac_no']) ?></label>
				<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
			</td> -->
			<td class="td_numsmall"><?php echo $total_count - ($i + ( ( $page-1 ) * $rows ) )?></td>
			<td class=""><?php echo $row['bg_datetime']?></td>
			<td class=""><?php echo $row['mb_id']?></td>
			<td class=""><?php echo $point_data['po_datetime']?></td>
			<td class=""><?php echo $point_data['po_content']?></td>
			<td class=""><?php echo number_format($point_data['po_point'])?>원</td>
			<td class=""><?php echo $item_data['it_name']?></td>
		</tr>
		<?php
		}
		if ($i == 0)
			echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없거나 관리자에 의해 삭제되었습니다.</td></tr>';
		?>
		</tbody>
		</table>
	</div>


	<div class="btn_fixed_top">
	    <!-- <?php if ($is_admin == 'super') { ?>
	    <input type="submit" name="act_button" value="전체삭제" onclick="document.pressed=this.value" class="btn_02 btn">
	    		<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn_02 btn">	    
	    <?php } ?> -->
	</div>
</form>


<?php
$qstr .= "&amp;page=";

$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr");
echo $pagelist;
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
