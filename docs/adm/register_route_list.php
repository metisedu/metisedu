<?php
$sub_menu = '500120';
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

function get_date_total_register($date, $mb_route){
	global $g5;

	$sql = " select count(*) AS cnt
			   from {$g5['member_table']}
			  where SUBSTRING(mb_datetime,1,4) = '".substr($date,0,4)."'
			  and   mb_route = '".$mb_route."'
			  ";
	//echo"<p>".$sql;
	$result = sql_fetch($sql);

	return $result;
}

// 검색
if(!$fr_date) $fr_date = date("Y-m-01");
if(!$to_date) $to_date = date("Y-m-d");

$start = new DateTime($fr_date);
$end = new DateTime($to_date);
$between = date_diff($start, $end);
$bt_day = $between->days;

$g5['title'] = '회원가입 통계';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

// 가입경로
$sQuery = " SELECT *
			FROM {$g5['member_table']}
			WHERE mb_route != ''
			GROUP BY mb_route
			ORDER BY mb_route ASC ";
$sql = sql_query($sQuery);
$sql2 = sql_query($sQuery);
$sql3 = sql_query($sQuery);
$sql4 = sql_query($sQuery);
$sql5 = sql_query($sQuery);
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
					<button type="button" class="chart_tab <?php if($mode == '') echo"on"; ?>" onclick="location.href='./register_list.php';">기간별</button>
					<button type="button" class="chart_tab <?php if($mode == 'route') echo"on"; ?>" onclick="location.href='./register_route_list.php?mode=route';">가입경로별</button>
				</div>

				<form class="local_sch03 local_sch">
				<input type="hidden" name="mode" value="<?php echo $mode; ?>" />
				<?php
				if($mode == 'route'){
				?>
				<div class="sch_last">
					<strong>가입경로별</strong>
					<select name="mb_route" id="mb_route" class="frm_input">
						<option value="">선택</option>
						<?php
						for($i = 0; $row = sql_fetch_array($sql); $i++){
							if($mb_route == $row['mb_route'])
								$slt = "selected";
							else
								$slt = "";

							echo"<option value='".$row['mb_route']."' ".$slt.">".$row['mb_route']."</option>";
						}
						?>
					</select>
					<script>
						$("#mb_route").change(function(){
							if($("#mb_route").val() == "")
								return false;

							location.href="/adm/register_route_list.php?mode=route&mb_route="+$("#mb_route").val()+"&fr_date=<?php echo $fr_date; ?>&to_date=<?php echo $to_date; ?>";
						});
					</script>
				</div>
				<?php } ?>
				<div class="sch_last">
					<strong>가입일자</strong>
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
							for($i = 0; $row = sql_fetch_array($sql2); $i++){
								$ct_date[] = "'".$row['mb_route']."'";
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
									for($i = 0; $row = sql_fetch_array($sql3); $i++){
										$to_day = date("m/d", strtotime($to_date));
										$a_to_day = date("Y")."-".str_replace("/","-",$to_day);
										$amount = get_date_total_register($a_to_day, $row['mb_route']);
										$ct_amt[] = "'".$amount['cnt']."'";
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
									for($i = 0; $row = sql_fetch_array($sql4); $i++){
										$one_year_ago = date(date("Y", strtotime("-1 year"))."/m/d", strtotime($to_date));
										$a_to_day2 = str_replace("/","-",$one_year_ago);
										$amount2 = get_date_total_register($a_to_day2, $row['mb_route']);
										$ct_amt2[] = "'".$amount2['cnt']."'";
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
						<th scope="col">인지경로</th>
						<th scope="col"><?php echo date("Y"); ?>년</th>
						<th scope="col"><?php echo date("Y", strtotime("-1 year")); ?>년</th>
						<th scope="col">증감</th>
					</tr>
					<tr>
						<th scope="col">합계</th>
						<th scope="col"><span id="total_amount1">0</span></th>
						<th scope="col"><span id="total_amount2">0</span></th>
						<th scope="col">&nbsp;</th>
					</tr>
					</thead>
					<tbody>
					<?php

					for($i = 0; $row = sql_fetch_array($sql5); $i++){
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
						$amount = get_date_total_register($a_to_day, $row['mb_route']);
						$amount2 = get_date_total_register($a_to_day2, $row['mb_route']); // 1년전

						if($amount['cnt'] > 0){
							$per = (($amount['cnt'] - $amount2['cnt']) / $amount['cnt']) * 100;
						}else{
							$per = "0";
						}
						if(($amount['cnt'] - $amount2['cnt']) > 0){
							$per_txt = "<b style='color:red;'>▲ ".round($per)."</b>";
						}else if(($amount['cnt'] - $amount2['cnt']) == 0){
							$per_txt = "<b>- ".round($per)."</b>";
						}else{
							$per_txt = "<b style='color:blue;'>▼ ".round($per)."</b>";
						}
					?>
					<tr>
						<td><?php echo $row['mb_route']; ?></td>
						<td><?php echo number_format($amount['cnt']); ?></td>
						<td><?php echo number_format($amount2['cnt']); ?></td>
						<td><?php echo $per_txt; ?>%</td>
					</tr>
					<?php
					}
					if($i == 0){
						echo"<tr><td colspan='4'>등록된 가입경로가 없습니다.</td></tr>";
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