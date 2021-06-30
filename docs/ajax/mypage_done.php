<?php
include_once('./_common.php');

$t_day = date("Y-m-d");

$sQuery = " SELECT *
			FROM {$g5['g5_shop_cart_table']}
			WHERE mb_id = '".$member['mb_id']."'
			AND   ct_status IN ('입금','배송','완료')
			AND   ct_end_date < '".$t_day."'
			ORDER BY od_id DESC
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

				$item_link_href = shop_item_url($it['it_id']);     // 상품링크
		?>
		<tr class="item-list">
			<td class="w60">
			<div class="media">
				<div class="pull-left thumb">
					<?php echo $image; ?>
				</div>
				<div class="item-list-name">
					<a href="<?php echo $item_link_href; ?>"><?php echo $it['it_name']; ?></a>
					<span class="author"><?php echo $it['it_t_name']; ?> | <?php echo $ca_id_slt[$it['ca_id']]; ?></span>
					<span class="date"><!-- 구매일 --> <?php echo date("Y.m.d", strtotime($row['ct_time'])); ?></span>
				</div>
			</div>
			</td>
			<td class="w25 text-center period"><!-- 기간만료 -->Expiration of period</td>
			<td class="w15 text-center btn_wrap"><a href="<?php echo $item_link_href; ?>" class="btn_class_complete"><!-- 다시수강하기 -->Re course</a></td>
		</tr>
		<?php
		}
		if($i == 0){
			echo"<tr><td colspan='3' style='text-align:center;width:100%;padding:100px;0px'>The lecture was not completed a course.</td></tr>";
		}
		?>
	</tbody>
</table>