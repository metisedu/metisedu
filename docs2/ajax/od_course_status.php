<?php
include_once("./_common.php");

$sQuery = " SELECT *
			FROM han_shop_cart
			WHERE od_id = '".$od_id."'
			";
//echo"<p>".$sQuery;
$sql = sql_query($sQuery);
?>
<div>
	<h2 id="modal1Title">수강현황</h2>
	<form name="schlist" id="schlist">
	<input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
	<input type="hidden" name="od_status" value="<?php echo $od['od_status']; ?>">
	<div class="tbl_frm01 tbl_wrap table-responsive">
		<table>
		<?php
		for($i = 0; $row = sql_fetch_array($sql); $i++){
			$sQuery = sql_query("SELECT * FROM han_shop_item_list WHERE it_id = '".$row['it_id']."' ");

			for($j = 0; $rst = sql_fetch_array($sQuery); $j++){
				$sql_a = sql_query("SELECT * FROM han_shop_chapter a, han_shop_movie b WHERE (a.cp_id = b.cp_id) AND a.lt_id = '".$rst['it_id2']."' ");
				for($k = 0; $rows = sql_fetch_array($sql_a); $k++){
					if($k == 0){
						echo'<tr>';
						echo'	<td colspan="2" style="text-align:left;"><b>'.$rows['cp_name'].'</b></td>';
						echo'</tr>';
					}
		?>
			<tr>
				<th><?php echo $rows['mv_name']; ?></th>
				<td style="text-align:right;">0%</td>
			</tr>
		<?php
				}
			}
		}
		?>
		</table>
	</div>
	</form>
</div>
<br>
<button data-remodal-action="cancel" class="remodal-confirm">확인</button>
<button data-remodal-action="cancel" class="remodal-cancel">취소</button>
<script>

</script>