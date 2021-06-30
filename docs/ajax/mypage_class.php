<?php
include_once('./_common.php');

$t_day = date("Y-m-d");

$sQuery = " SELECT *
			FROM {$g5['g5_shop_order_table']} a, {$g5['g5_shop_cart_table']} b
			WHERE (a.od_id = b.od_id)
			AND   a.mb_id = '".$member['mb_id']."'
			AND   a.od_status IN ('입금','배송','완료')
			AND   a.od_start_date <= '".$t_day."'
			AND   a.od_end_date >= '".$t_day."'
			AND   b.ct_select = '1'
			GROUP BY b.ct_id
			ORDER BY a.od_id DESC, b.ct_id ASC
			";
$sql = sql_query($sQuery);
?>
<style>
@media screen and (max-width: 768px){
	.list-item:after{content:'';background:url('') -300px -16px;}
}
</style>
<table class="table">
	<thead>
		<tr>
			<th><!-- 강의명 -->Lessons</th>
			<th  class="text-center"><!-- 이용기간 -->Period of use</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php
			for($i = 0; $row = sql_fetch_array($sql); $i++){
				$it = get_item($row['it_id']);
				if(!$it)
					continue;

				$ca = get_cate($it['ca_id']);
				$image = get_it_image($row['it_id'], 68, 68);

				$uid = md5($row['od_id'].$row['od_time'].$row['od_ip']);
		?>
		<tr class="item-list">
			<td class="w60">
			<div class="media">
				<div class="pull-left thumb">
					<?php echo $image; ?>
				</div>
				<div class="item-list-name">
					<a href="#"><?php echo $it['it_name']; ?></a>
					<span class="author"><?php echo $it['it_t_name']; ?> <!-- | <?//php echo $ca_id_slt[$it['ca_id']]; ?> --></span>
					<span class="date"><!-- 구매일 --> <?php echo date("Y.m.d", strtotime($row['ct_time'])); ?></span>
				</div>
			</div>
			</td>
			<td class="w25 text-center period"><?php echo get_lec_date($row['it_id'], $row['ct_end_date'], 'mypage'); ?></td>
			<td class="w15 text-center btn_wrap"><a href="/shop/mypage_view.php?od_id=<?php echo $row['od_id']; ?>&amp;ct_id=<?php echo $row['ct_id']; ?>&amp;uid=<?php echo $uid; ?>" class="btn_course">classes in progress</a></td>
		</tr>
		<?php
		}
		if($i == 0){
			echo"<tr><td colspan='3' style='text-align:center;width:100%;padding:100px;0px'>There are no classes in progress.</td></tr>";
		}
		?>
	</tbody>
</table>