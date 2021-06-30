<?php
include_once('./_common.php');
define('_NOHEADER_',true);

if (!$is_member)
    goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_SHOP_URL."/mypage.php"));


$g5['title'] = 'Reward';
include_once('./_head.php');


$po_tot_sql = "SELECT COUNT(*) AS cnt FROM han_point WHERE mb_id = '".$member["mb_id"]."'";
$po_tot_row = sql_fetch($po_tot_sql);
$total_page = $po_tot_row["cnt"];


$rows = 5;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$po_sql = "SELECT * FROM han_point WHERE mb_id = '".$member["mb_id"]."' ORDER BY po_id DESC limit {$from_record}, {$rows} ";
$po_res = sql_query($po_sql);
while($po_row = sql_fetch_array($po_res)){
	$po_data[] = $po_row;
}


?>
<style>
.div_tot div {height:60px;width:50%;float:left;}
</style>
<!-- 마이페이지 시작 { -->
<section id="sub_mypage">
	<div class="container">
		<div class="row">
			<?php
			include_once(G5_THEME_PATH.'/mypage.left.php');
			?>
			<div class="col-md-9 col-sm-12 col-xs-12 my_con">
				<div class="mypage-class">
					<div class="mypage-class-menu">
						<ul>
							<li class="my_tab_btn" data-id="class"><a href="/shop/reward_log.php" class="active">Reward</a></li>
						</ul>
					</div>
					<!-- 수강중인 강의 -->
					<div class="responsive div_tot" id="smb_my_class" >
						<div>
						<!-- 미지급합계 -->Total unpaid : <?php echo number_format($member["mb_point"]);?>
						</div>

						<div>
						<!-- 지급합계 -->Total payment : <?php echo number_format($member["mb_point_metis"]);?>
						</div>
					</div>
					<!-- 수강중인 강의 end -->
					
					
					<table class="table">
						<thead>
							<tr>
								<th class="text-center"><!-- 히스토리 -->History</th>
							</tr>
						</thead>
						<tbody>	
							<?php for($i=0; $i<count($po_data); $i++){ ?>
							<tr class="item-list">
								<td class="w60">
									<div class="media">
										<div class="pull-left thumb" style="text-align:center;" >
											<?php echo $po_data[$i]["po_content"]; ?>
										</div>
									</div>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>

				</div>
				<?php echo get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

			</div>
		</div>
	</div>
</section>
<style>
@media screen and (max-width: 768px){
	.list-item:after{content:'';background:url('') -300px -16px;}
}
</style>

<?php
include_once("./_tail.php");
?>