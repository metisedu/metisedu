<?php
include_once('./_common.php');
define('_NOHEADER_',true);

if (!$is_member)
    goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_SHOP_URL."/coupon.php"));

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/coupon.php');
    return;
}

// 테마에 coupon.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
    $theme_coupon_file = G5_THEME_SHOP_PATH.'/coupon.php';
    if(is_file($theme_coupon_file)) {
        include_once($theme_coupon_file);
        return;
        unset($theme_coupon_file);
    }
}

if ($is_guest)
    alert_close('회원만 조회하실 수 있습니다.');

$g5['title'] = $member['mb_nick'].' 님의 쿠폰 내역';
include_once('./_head.php');

// 쿠폰
$cp_count = 0;
$sql = " select *
            from {$g5['g5_shop_coupon_table']}
            where mb_id IN ( '{$member['mb_id']}', '전체회원' )
              and cp_start <= '".G5_TIME_YMD."'
              and cp_end >= '".G5_TIME_YMD."' ";
$result = sql_query($sql);
?>

<!-- 쿠폰 내역 시작 { -->
<section id="sub_mypage">
	<div class="container">
		<div class="row">
			<?php
			include_once(G5_THEME_PATH.'/mypage.left.php');
			?>

			<div class="col-md-9 col-sm-12 col-xs-12 my_con">
				<div class="mypage-class">
					<div class="mypage-small-tit">
						쿠폰내역

					</div>

					<div class="mypage-coupon-list responsive">
						<div class="mypage-coupon-input">
							쿠폰번호 입력 <input type="text" name="cp_id" id="cp_id" class="frm_input"> <button type="button" onclick="get_coupon_check();">쿠폰등록</button>
						</div>

						<table class="table" style="display:;">
							<thead>
								<tr>
									<th>쿠폰 명</th>
									<th style="width:17%"  class="text-center">종류</th>
									<th style="width:33%" class="text-center">기간</th>
									<th style="width:25%"></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$cp_count = 0;
								for($i=0; $row=sql_fetch_array($result); $i++) {
									if(is_used_coupon($member['mb_id'], $row['cp_id'])){
										$use = "사용함";
									}else{
										$use = "사용가능";
									}

									if($row['cp_method'] == 1) {
										$sql = " select ca_name from {$g5['g5_shop_category_table']} where ca_id = '{$row['cp_target']}' ";
										$ca = sql_fetch($sql);
										$cp_target = $ca['ca_name'].'의 상품할인';
									} else if($row['cp_method'] == 2) {
										$cp_target = '결제금액 할인';
									} else if($row['cp_method'] == 3) {
										$cp_target = '배송비 할인';
									} else {
										$it = get_shop_item($row['cp_target'], true);
										$cp_target = $it['it_name'].' 상품할인';
									}

									if($row['cp_type'])
										$cp_price = $row['cp_price'].'%';
									else
										$cp_price = number_format($row['cp_price']).'원';

									$cp_count++;

									$nDate = $row['cp_end'];
									$valDate = date("Y-m-d");

									$leftDate = intval((strtotime($nDate)-strtotime($valDate)) / 86400);
								?>
								<tr class="item-list">
									<td>
									<div class="media">
										<div class="item-list-name">
											<span class="coupon-name"><?php echo $row['cp_subject']; ?></span>
											<span class="date">지급일 <?php echo date("Y.m.d", strtotime($row['cp_datetime'])); ?></span>
										</div>
									</div>
									</td>
									<td class="text-center type"><?php echo $cp_target; ?></td>
									<td class="text-center period">남은기간 : <?php echo date("Y.m.d", strtotime($row['cp_end'])); ?>까지 / <?php echo $leftDate; ?>일남음</td>
									<td class="text-center btn_wrap"><a href="#" class="btn_coupon"><?php echo $use; ?></a></td>
								</tr>
								<?php
								}
								$sql = " SELECT *
										 FROM g5_shop_coupon_log AS a
										 INNER JOIN g5_shop_coupon AS b ON a.cp_id = b.cp_id
										 WHERE a.mb_id IN ( '{$member['mb_id']}' )
										 AND   b.cp_start <= '".G5_TIME_YMD."'
										 AND   b.cp_end >= '".G5_TIME_YMD."'
										 AND   b.cp_method2 = '2'
										 AND   a.rand_id != ''
										 ORDER BY a.cl_id
										 ";
								$rand = sql_query($sql);
								for($i = 0; $row = sql_fetch_array($rand); $i++){

									if($row['cl_datetime'] > '0000-00-00 00:00:00'){
										$use = '사용';
									}else{
										$use = '미사용';
										$cp_av += 1;
									}

									if($row['cp_method'] == 1) {
										$sql = " select ca_name from {$g5['g5_shop_category_table']} where ca_id = '{$row['cp_target']}' ";
										$ca = sql_fetch($sql);
										$cp_target = $ca['ca_name'].'의 상품할인';
									} else if($row['cp_method'] == 2) {
										$cp_target = '결제금액 할인';
									} else if($row['cp_method'] == 3) {
										$cp_target = '배송비 할인';
									} else {
										$sql = " select it_name from {$g5['g5_shop_item_table']} where it_id = '{$row['cp_target']}' ";
										$it = sql_fetch($sql);
										$cp_target = $it['it_name'].' 상품할인';
									}

									if($row['cp_type'])
										$cp_price = $row['cp_price'].'%';
									else
										$cp_price = number_format($row['cp_price']).'원';

									$cp_count++;

									$nDate = $row['cp_end'];
									$valDate = date("Y-m-d");

									$leftDate = intval((strtotime($nDate)-strtotime($valDate)) / 86400);
								?>
								<tr class="item-list">
									<td>
									<div class="media">

										<div class="item-list-name">
											<span class="coupon-name"><?php echo $row['cp_subject']; ?></span>
											<span class="date">지급일 <?php echo date("Y.m.d", strtotime($row['cl_datetime'])); ?></span>
										</div>
									</div>
									</td>
									<td class="text-center type"><?php echo $cp_target; ?></td>
									<td class="text-center period">남은기간 : <?php echo date("Y.m.d", strtotime($row['cp_end'])); ?>까지 / <?php echo $leftDate; ?>일남음</td>
									<td class="text-center btn_wrap"><a href="#" class="btn_coupon"><?php echo $use; ?></a></td>
								</tr>
								<?php
								}
								if ($cp_count == 0){
									echo '
									<tr><td colspan="4"><div class="mypage-class-null text-center">
										<i class="fa fa-exclamation-circle" aria-hidden="true"></i>
										<br />
										사용할 수 있는 쿠폰이 없습니다.
									</div></td></tr>
									';
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

</section>
<script>
	function get_coupon_check(){
		if( $("#cp_id").val() == '' ){
			alert('쿠폰번호를 입력해 주세요.');
			return false;
		}

		$.ajax({
			type: "post",
			url: g5_shop_url + "/coupon_check_number.php",
			data: {
				cp_id: $("#cp_id").val()
			},
			success: function(data){
				if(data == "10"){
					alert('등록되었습니다.');
					location.reload();
				}else if(data == "20"){
					alert('이미 등록된 쿠폰번호 입니다.');
				}else{
					alert('쿠폰번호가 유효하지 않습니다.');
				}
			}
		});
	}
</script>
<?php
include_once("./_tail.php");
?>