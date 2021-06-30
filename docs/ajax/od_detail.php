<?php
include_once("./_common.php");

$sQuery = " SELECT *
			FROM han_shop_order
			WHERE od_id = '".$od_id."'
			";
//echo"<p>".$sql;
$od = sql_fetch($sQuery);

$rst = sql_fetch("SELECT it_name FROM han_shop_cart WHERE od_id = '".$od['od_id']."' ");
?>
<div>
	<h2 id="modal1Title">구매 상세 내역</h2>
	<form name="schlist" id="schlist">
	<input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
	<input type="hidden" name="od_status" value="<?php echo $od['od_status']; ?>">
	<div class="tbl_frm01 tbl_wrap table-responsive">
		<table>
			<tr>
				<th>결제수단</th>
				<td><?php echo $od['od_status']; ?></td>
				<th>주문자명</th>
				<td><?php echo $od['od_name']; ?></td>
			</tr>
			<tr>
				<th>구매금액</th>
				<td><?php echo number_format($od['od_cart_price']); ?> 원</td>
				<th>주문번호</th>
				<td><?php echo $od['od_id']; ?></td>
			</tr>
			<tr>
				<th>쿠폰사용금액</th>
				<td><?php echo number_format($od['od_cart_coupon']); ?> 원</td>
				<th>주문일시</th>
				<td><?php echo $od['od_time']; ?></td>
			</tr>
			<tr>
				<th>실결제금액</th>
				<td><?php echo number_format($od['od_receipt_price']); ?> 원</td>
				<th>상품명</th>
				<td><?php echo $rst['it_name']; ?></td>
			</tr>

			<tr>
				<td colspan="4"><b>결제 취소 및 환불</b></td>
			</tr>
			<tr>
				<th>환불사유</th>
				<td colspan="3" style="text-align:left;"><input type="text" name="od_refund_memo" id="od_refund_memo" class="frm_input" value="" style="width:100%;" /></td>
			</tr>
			<tr>
				<th>환불금액</th>
				<td colspan="3" style="text-align:left;"><input type="text" name="od_refund_sub_price" id="od_refund_sub_price" class="frm_input" value="" /> 원</td>
			</tr>
			<tr>
				<th rowspan="3">환불계좌정보</th>
				<td colspan="3" style="text-align:left;"><input type="text" name="od_refund_name" id="od_refund_name" class="frm_input" value="" placeholder="예금주" /></td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:left;"><input type="text" name="od_refund_bank" id="od_refund_bank" class="frm_input" value="" placeholder="은행명" /></td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:left;"><input type="text" name="od_refund_account" id="od_refund_account" class="frm_input" value="" placeholder="계좌번호" style="width:100%;" /></td>
			</tr>
		</table>
	</div>
	</form>
</div>
<br>
<button class="remodal-confirm" id="refund_btn">확인</button>
<button data-remodal-action="cancel" class="remodal-cancel">취소</button>
<script>

</script>