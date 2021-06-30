<?php
include_once("./_common.php");

$sql_common = " from han_shop_order WHERE mb_id = '".$mb_id."' ";

$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 5;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sQuery = " SELECT *
			{$sql_common}
			ORDER BY od_id DESC
			limit $from_record, $rows ";
$sql = sql_query($sQuery);
?>
<div style="padding:5px;line-height:30px;font-weight:bold;">
	구매내역
</div>
<table>
<caption>구매내역 목록</caption>
<thead>
<tr>
	<th scope="col">상품명</th>
	<th scope="col">구매일시</th>
	<th scope="col">금액</th>
	<th scope="col">결제수단</th>
	<th scope="col">주문번호</th>
	<th scope="col">결제현황</th>
	<th scope="col">구매상세</th>
</tr>
</thead>
<tbody>
<?php
	for($i = 0; $row = sql_fetch_array($sql); $i++){
		$rst = sql_fetch("SELECT it_name FROM han_shop_cart WHERE od_id = '".$row['od_id']."' ");
?>
	<tr>
		<td class="spl-subject-cell">
			<?php echo $rst['it_name']; ?>
		</td>
		<td class="spl-cell"><?php echo $row['od_time']; ?></td>
		<td class="spl-cell">
			<?php echo number_format($row['od_receipt_price']); ?>
		</td>
		<td class="td_numsmall">
			<?php echo $row['od_settle_case']; ?>
		</td>
		<td class="td_mng"><?php echo $row['od_id']; ?></td>
		<td class="td_mng"><?php echo $row['od_status']; ?></td>
		<td class="td_mng">
			<button type="button" class="od_detail" data-page="od_detail" data-id="<?php echo $row['od_id']; ?>">구매상세</button>
		</td>
	</tr>
<?php }
if($i == 0) echo"<tr><td colspan='7'>구매내역이 없습니다.</td></tr>";
?>
</tbody>
</table>
<?php echo get_paging2(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "order_list(#page)"); ?>