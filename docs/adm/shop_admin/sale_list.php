<?php
$sub_menu = '500110';
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

function get_date_total_amount($date){
	global $g5, $mode, $ca_id2, $it_id;

	if($mode == 'cate'){
		$sql = " select SUM(c.it_price) AS od_receipt_price
			   from {$g5['g5_shop_order_table']} a, {$g5['g5_shop_cart_table']} b, {$g5['g5_shop_item_table']} c
			  where (a.od_id = b.od_id) AND (b.it_id = c.it_id)
			  AND   SUBSTRING(a.od_time,1,10) = '$date'
			  AND   a.od_status IN ('입금','완료','배송')
			  AND   (c.ca_id = '".$ca_id2."' OR c.ca_id2 = '".$ca_id2."' OR c.ca_id3 = '".$ca_id2."')
			  ";
	}else if($mode == 'item'){
		$sql = " select SUM(c.it_price) AS od_receipt_price
			   from {$g5['g5_shop_order_table']} a, {$g5['g5_shop_cart_table']} b, {$g5['g5_shop_item_table']} c
			  where (a.od_id = b.od_id) AND (b.it_id = c.it_id)
			  AND   SUBSTRING(a.od_time,1,10) = '$date'
			  AND   a.od_status IN ('입금','완료','배송')
			  AND   (c.ca_id = '".$ca_id2."' OR c.ca_id2 = '".$ca_id2."' OR c.ca_id3 = '".$ca_id2."')
			  AND   c.it_id = '".$it_id."'
			  ";
	}else{
		$sql = " select SUM(od_receipt_price) AS od_receipt_price
			   from {$g5['g5_shop_order_table']}
			  where SUBSTRING(od_time,1,10) = '$date'
			  ";
	}

	$result = sql_fetch($sql);

	return $result;
}

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

// 검색
if(!$fr_date) $fr_date = date("Y-m-01");
if(!$to_date) $to_date = date("Y-m-d");

$start = new DateTime($fr_date);
$end = new DateTime($to_date);
$between = date_diff($start, $end);
$bt_day = $between->days;

$g5['title'] = '매출통계';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>
<script src="/js/Chart.min.js"></script>
<script src="/js/utils.js"></script>
<style type="text/css">/* Chart.js */
@keyframes chartjs-render-animation{from{opacity:.99}to{opacity:1}}.chartjs-render-monitor{animation:chartjs-render-animation 1ms}.chartjs-size-monitor,.chartjs-size-monitor-expand,.chartjs-size-monitor-shrink{position:absolute;direction:ltr;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1}.chartjs-size-monitor-expand>div{position:absolute;width:1000000px;height:1000000px;left:0;top:0}.chartjs-size-monitor-shrink>div{position:absolute;width:200%;height:200%;left:0;top:0}

.chartjs-render-monitor {
    animation: chartjs-render-animation 1ms;
}
</style>

<div class="card card-inverse card-flat">

	<div class="card-block">
		<div class="row">
			<div class="col-md-12">

				<div class="local_ov01 local_ov">
					<button type="button" class="chart_tab <?php if($mode == '') echo"on"; ?>" onclick="location.href='./sale_list.php';">기간별</button>
					<button type="button" class="chart_tab <?php if($mode == 'cate') echo"on"; ?>" onclick="location.href='./sale_list.php?mode=cate';">카테고리별</button>
					<button type="button" class="chart_tab <?php if($mode == 'item') echo"on"; ?>" onclick="location.href='./sale_list.php?mode=item';">상품별</button>
				</div>

				<form class="local_sch03 local_sch">
				<input type="hidden" name="mode" value="<?php echo $mode; ?>" />
				<?php
				if($mode == 'cate'){
				?>
				<div class="sch_last">
					<strong>카테고리별</strong>
					<select name="ca_id2" id="ca_id2" class="frm_input">
						<?php echo conv_selected_option($ca_list, $ca_id2); ?>
					</select>
					<script>
						$("#ca_id2").change(function(){
							if($("#ca_id2").val() == "")
								return false;

							location.href="/adm/shop_admin/sale_list.php?mode=cate&ca_id2="+$("#ca_id2").val()+"&fr_date=<?php echo $fr_date; ?>&to_date=<?php echo $to_date; ?>";
						});
					</script>
				</div>
				<?php }else if($mode == 'item'){ ?>
				<div class="sch_last">
					<strong>카테고리</strong>
					<select name="ca_id" id="ca_id" class="frm_input">
						<?php echo conv_selected_option($ca_list, $ca_id2); ?>
					</select>

					<strong>상품</strong>
					<select name="it_id" id="it_id" class="frm_input">
						<option value="">선택</option>
					</select>
					<script>
						var ca_opt = '<?php echo $ca_id2; ?>';
						var it_id = '<?php echo $it_id; ?>';

						function get_it_opt(){
							$.ajax({
								type:"post",
								url:"/ajax/admin_func.php",
								data: {
									func: "it_id_op",
									ca_id: ca_opt
								},
								success:function(data){
									$("#it_id").html('<option value="">선택</option>');
									$("#it_id").append(data);
									if(it_id != ""){
										$("#it_id").val(it_id);
									}
								}
							});
						}

						$("#ca_id").change(function(){
							ca_opt = $("#ca_id").val();
							get_it_opt();
						});

						$("#it_id").change(function(){
							if($("#it_id").val() == "")
								return false;

							location.href="/adm/shop_admin/sale_list.php?mode=item&ca_id2="+$("#ca_id").val()+"&it_id="+$("#it_id").val()+"&fr_date=<?php echo $fr_date; ?>&to_date=<?php echo $to_date; ?>";
						});

						get_it_opt();
					</script>
				</div>
				<?php } ?>
				<div class="sch_last">
					<strong>주문일자</strong>
					<input type="text" id="fr_date"  name="fr_date" value="<?php echo $fr_date; ?>" class="frm_input" size="10" maxlength="10" autocomplete="off"> ~
					<input type="text" id="to_date"  name="to_date" value="<?php echo $to_date; ?>" class="frm_input" size="10" maxlength="10" autocomplete="off">
					<button type="button" onclick="javascript:set_date('이번달');">이번달</button>
					<button type="button" onclick="javascript:set_date('최근7일');">최근7일</button>
					<button type="button" onclick="javascript:set_date('최근15일');">최근15일</button>
					<button type="button" onclick="javascript:set_date('연월선택');">연/월선택</button>
					<input type="submit" value="검색" class="btn_submit">
				</div>
				</form>

				<div class="tbl_head01 tbl_wrap">
					<canvas id="myChart" style="width:100%;height:400px;"></canvas>
					<script>
					var ctx = document.getElementById('myChart').getContext('2d');
					var color = Chart.helpers.color;
					var myChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: [
							<?php
							$ct_date = array();
							for($date = $bt_day; $date >= 0; $date--){
								$to_day = date("m/d", strtotime("-".$date." day", strtotime($to_date)));
								$ct_date[] = "'".$to_day."'";
							}
							echo implode(",",$ct_date);
							?>
							],
							datasets: [{
								label: '<?php echo date("Y"); ?>',
								backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
								borderColor: window.chartColors.red,
								borderWidth: 1,
								data: [
									<?php
									$ct_amt = array();
									for($date = $bt_day; $date >= 0; $date--){
										$to_day = date("m/d", strtotime("-".$date." day", strtotime($to_date)));
										$a_to_day = date("Y")."-".str_replace("/","-",$to_day);
										$amount = get_date_total_amount($a_to_day);
										$ct_amt[] = "'".$amount['od_receipt_price']."'";
									}
									echo implode(",",$ct_amt);
									?>
								]
							}, {
								label: '<?php echo date("Y", strtotime("-1 year")); ?>',
								backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
								borderColor: window.chartColors.blue,
								borderWidth: 1,
								data: [
									<?php
									$ct_amt2 = array();
									for($date = $bt_day; $date >= 0; $date--){
										$one_year_ago = date(date("Y", strtotime("-1 year"))."/m/d", strtotime("-".$date." day", strtotime($to_date)));
										$a_to_day2 = str_replace("/","-",$one_year_ago);
										$amount2 = get_date_total_amount($a_to_day2);
										$ct_amt2[] = "'".$amount2['od_receipt_price']."'";
									}
									echo implode(",",$ct_amt2);
									?>
								]
							}]
						},
						options: {
							scales: {
								y: {
									beginAtZero: true
								}
							}
						}
					});
					</script>

				</div>

				<div class="tbl_head01 tbl_wrap">
					<table id="sodr_list">
					<caption>목록</caption>
					<thead>
					<tr>
						<th scope="col">날짜</th>
						<th scope="col">요일</th>
						<th scope="col"><?php echo date("Y"); ?>년</th>
						<th scope="col">요일</th>
						<th scope="col"><?php echo date("Y", strtotime("-1 year")); ?>년</th>
						<th scope="col">증감</th>
					</tr>
					<tr>
						<th scope="col">합계</th>
						<th scope="col">&nbsp;</th>
						<th scope="col"><span id="total_amount1">0</span></th>
						<th scope="col">&nbsp;</th>
						<th scope="col"><span id="total_amount2">0</span></th>
						<th scope="col">&nbsp;</th>
					</tr>
					</thead>
					<tbody>
					<?php
					$yoil = array("일","월","화","수","목","금","토");
					for($date = 0; $date <= $bt_day; $date++){
						$to_day = date("m/d", strtotime("-".$date." day", strtotime($to_date)));
						$one_year_ago = date(date("Y", strtotime("-1 year"))."/m/d", strtotime("-".$date." day", strtotime($to_date)));

						$w = date("w", strtotime($to_day));
						$w2 = date("w", strtotime($one_year_ago));
						// 올해
						if($w == 0){
							$day = "<b style='color:red;'>".$yoil[$w]."</b>";
						}else if($w == 6){
							$day = "<b style='color:blue;'>".$yoil[$w]."</b>";
						}else{
							$day = "<b>".$yoil[$w]."</b>";
						}
						// 1년 전
						if($w2 == 0){
							$day2 = "<b style='color:red;'>".$yoil[$w2]."</b>";
						}else if($w2 == 6){
							$day2 = "<b style='color:blue;'>".$yoil[$w2]."</b>";
						}else{
							$day2 = "<b>".$yoil[$w2]."</b>";
						}

						$a_to_day = date("Y")."-".str_replace("/","-",$to_day);
						$a_to_day2 = str_replace("/","-",$one_year_ago);
						$amount = get_date_total_amount($a_to_day);
						$amount2 = get_date_total_amount($a_to_day2); // 1년전

						if($amount['od_receipt_price'] > 0){
							$per = (($amount['od_receipt_price'] - $amount2['od_receipt_price']) / $amount['od_receipt_price']) * 100;
						}else{
							$per = "0";
						}
						if(($amount['od_receipt_price'] - $amount2['od_receipt_price']) > 0){
							$per_txt = "<b style='color:red;'>▲ ".round($per)."</b>";
						}else if(($amount['od_receipt_price'] - $amount2['od_receipt_price']) == 0){
							$per_txt = "<b>- ".round($per)."</b>";
						}else{
							$per_txt = "<b style='color:blue;'>▼ ".round($per)."</b>";
						}
					?>
					<tr>
						<td><?php echo $to_day; ?></td>
						<td><?php echo $day; ?></td>
						<td><?php echo number_format($amount['od_receipt_price']); ?></td>
						<td><?php echo $day2; ?></td>
						<td><?php echo number_format($amount2['od_receipt_price']); ?></td>
						<td><?php echo $per_txt; ?>%</td>
					</tr>
					<?php
					}
					?>
					</tbody>
					</table>
				</div>

			</div>
		</div>
	</div>
</div>

<script>
$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});

function set_date(today)
{
    <?php
    $date_term = date('w', G5_SERVER_TIME);
    $week_term = $date_term + 7;
    $last_term = strtotime(date('Y-m-01', G5_SERVER_TIME));
    ?>
	if (today == "이번달") {
        document.getElementById("fr_date").value = "<?php echo date('Y-m-01', G5_SERVER_TIME); ?>";
        document.getElementById("to_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
    } else if (today == "최근7일") {
        document.getElementById("fr_date").value = "<?php echo date('Y-m-d', strtotime('-7 days')); ?>";
        document.getElementById("to_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
    } else if (today == "최근15일") {
        document.getElementById("fr_date").value = "<?php echo date('Y-m-d', strtotime('-15 days')); ?>";
        document.getElementById("to_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
    } else if (today == "연월선택") {
        document.getElementById("fr_date").value = "";
        document.getElementById("to_date").value = "";
    }
}
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>