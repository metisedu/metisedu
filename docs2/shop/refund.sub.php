<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if (!defined("_ORDERINQUIRY_")) exit; // 개별 페이지 접근 불가

// 테마에 orderinquiry.sub.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
    $theme_inquiry_file = G5_THEME_SHOP_PATH.'/orderinquiry.sub.php';
    if(is_file($theme_inquiry_file)) {
        include_once($theme_inquiry_file);
        return;
        unset($theme_inquiry_file);
    }
}
?>
<style>
.class-filter .period_date button.btn_date {
    color: #ffffff;
    background-color: #0065af;
    padding: 6px 20px;
    margin-top: 0px;
    display: inline-block;
    border-bottom: 1px solid #0065af;
    margin-left: 5px;
	border:0px;
}
</style>

<!-- 주문 내역 목록 시작 { -->
<?php if (!$limit) { ?>총 <?php echo $cnt; ?> 건<?php } ?>

<div class="col-md-9">
	<div class="mypage-class">
		<div class="mypage-class-menu">
			<ul>
				<li><a href="/shop/orderinquiry/" class="">결제내역</a></li>
				<li><a href="/shop/refund/" class="active">환불내역</a></li>
			</ul>
		</div>

		<div class="mypage-payment-list no-after">
			<div class="class-filter">

				<div class="col-md-6">
					<div class="period">
						<ul>
							<li><a href="javascript:set_date(3);" class="<?php if($bt_mon == 3) echo"active"; ?>">3개월</a></li>
							<li><a href="javascript:set_date(6);" class="<?php if($bt_mon == 6) echo"active"; ?>">6개월</a></li>
							<li><a href="javascript:set_date(12);" class="<?php if($bt_mon == 0) echo"active"; ?>">12개월</a></li>
						</ul>
					</div>
				</div>
				<div class="col-md-6">
					<div class="period_date">
					<form name="nForm" id="nForm" method="post">
						<input type="text" name="s_date" id="s_date" class="form_control input_period" value="<?php echo $s_date; ?>"> - <input type="text" name="e_date" id="e_date" class="form_control input_period"  value="<?php echo $e_date; ?>">
						<button type="submit" class="btn_date">조회</button>
					</form>
					</div>
				</div>

				<!--<span class="payment-month">2019.12</span>-->
				<?php
				$sql = " select *
						  from {$g5['g5_shop_order_table']}
						  where mb_id = '{$member['mb_id']}'
						  and   od_status IN ('취소','환불','반품')
						  and   LEFT(od_time,10) >= '".date("Y-m-d", strtotime($s_date))."' AND LEFT(od_time,10) <= '".date("Y-m-d", strtotime($e_date))."'
						  order by od_id desc
						  $limit ";
				$result = sql_query($sql);
				for ($i=0; $row=sql_fetch_array($result); $i++)
				{
					$uid = md5($row['od_id'].$row['od_time'].$row['od_ip']);

					switch($row['od_status']) {
						case '주문':
							$od_status = '<span class="status_01">입금확인중</span>';
							break;
						case '입금':
							$od_status = '<span class="status_02">입금완료</span>';
							break;
						case '준비':
							$od_status = '<span class="status_03">상품준비중</span>';
							break;
						case '배송':
							$od_status = '<span class="status_04">상품배송</span>';
							break;
						case '완료':
							$od_status = '<span class="status_05">배송완료</span>';
							break;
						default:
							$od_status = '<span class="status_06">주문취소</span>';
							break;
					}

					$sQuery = " SELECT *
								FROM han_shop_cart
								WHERE od_id = '".$row['od_id']."'
								AND   ct_end_date != ''
								ORDER BY ct_id DESC
								";
					//echo"<p>".$sQuery;
					$rst = sql_fetch($sQuery);

					$it = get_item($rst['it_id']);
				?>
				<div class="col-md-12 payment-list-item refund">
					<div class="row">
						<div class="col-md-4">
							<div class="payment-list-status">
							환불완료
							</div>
							<div class="item-list-name">
								<a href="<?php echo G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $row['od_id']; ?>&amp;uid=<?php echo $uid; ?>" class="ord_name"><?php echo $rst['it_name']; ?></a>
								<span class="author"><?php echo $row['od_b_name']; ?> | <?php echo $ca_id_slt[$it['ca_id']]; ?></span>
								<span class="date">구매일 <?php echo substr($row['od_time'],2,14); ?></span>
							</div>
						</div>
						<div class="col-md-3">

							<div class="payment_price">
								<dl>
									<dt>결제금액</dt>
									<dd><?php echo display_price($row['od_cart_price']); ?></dd>
									<dt>공제금액</dt>
									<dd>-<?php echo number_format(0)?>원</dd>
								</dl>
								<dl class="last">
									<dt>최종 환불금액</dt>
									<dd><?php echo display_price($row['od_refund_price']); ?></dd>
								</dl>
							</div>
						</div>
						<div class="col-md-5">
							<div class="payment_price refund">
								<dl>
									<dt>환불형태</dt>
									<dd>신용카드</dd>
									<dt>환불완료</dt>
									<dd><?php echo date("Y.m.d H:i")?></dd>
								</dl>
								<div class="caution">
									<ul>
										<li>* 공제 금액은 환불 규정에 따라 제외된 금액입니다</li>
										<li>* 무통장 입금 환불은 본인 명의 계좌만 가능합니다</lio>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
				}
				if ($i == 0)
					echo '<li class="empty_table">환불 내역이 없습니다.</li>';
				?>
			</div>

		</div>
	</div>
</div>

<script>
function set_date(today)
{
	$(".period > ul > li > a").removeClass('active');
    if (today == "3") {
        document.getElementById("s_date").value = "<?php echo date('Y-m-d', strtotime('-3 months', G5_SERVER_TIME)); ?>";
        document.getElementById("e_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
		$(".period > ul > li:nth-child(1) > a").addClass('active');
    } else if (today == "6") {
        document.getElementById("s_date").value = "<?php echo date('Y-m-d', strtotime('-6 months', G5_SERVER_TIME)); ?>";
        document.getElementById("e_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
		$(".period > ul > li:nth-child(2) > a").addClass('active');
    } else if (today == "12") {
        document.getElementById("s_date").value = "<?php echo date('Y-m-d', strtotime('-12 months', G5_SERVER_TIME)); ?>";
        document.getElementById("e_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
		$(".period > ul > li:nth-child(3) > a").addClass('active');
    }
}
</script>
<? /*
<p class="tooltip_txt"><i class="fa fa-info-circle" aria-hidden="true"></i> 주문서번호 링크를 누르시면 주문상세내역을 조회하실 수 있습니다.</p>
<ul class="smb_my_od">
	<?php
	    $sql = " select *
	              from {$g5['g5_shop_order_table']}
	              where mb_id = '{$member['mb_id']}'
	              order by od_id desc
	              $limit ";
	    $result = sql_query($sql);
	    for ($i=0; $row=sql_fetch_array($result); $i++)
	    {
	        $uid = md5($row['od_id'].$row['od_time'].$row['od_ip']);

	        switch($row['od_status']) {
	            case '주문':
	                $od_status = '<span class="status_01">입금확인중</span>';
	                break;
	            case '입금':
	                $od_status = '<span class="status_02">입금완료</span>';
	                break;
	            case '준비':
	                $od_status = '<span class="status_03">상품준비중</span>';
	                break;
	            case '배송':
	                $od_status = '<span class="status_04">상품배송</span>';
	                break;
	            case '완료':
	                $od_status = '<span class="status_05">배송완료</span>';
	                break;
	            default:
	                $od_status = '<span class="status_06">주문취소</span>';
	                break;
	        }
	    ?>
	<li>
		<div class="smb_my_od_li smb_my_od_li1">
			<span class="sound_only">주문서번호</span>
        	<input type="hidden" name="ct_id[<?php echo $i; ?>]" value="<?php echo $row['ct_id']; ?>">
        	<a href="<?php echo G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $row['od_id']; ?>&amp;uid=<?php echo $uid; ?>" class="ord_num"><?php echo $row['od_id']; ?></a>
			<br>
			<span class="sound_only">주문일시</span>
			<span class="date"><?php echo substr($row['od_time'],2,14); ?> (<?php echo get_yoil($row['od_time']); ?>)</span>
		</div>
		<div class="smb_my_od_li smb_my_od_li2">
			<a href="<?php echo G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $row['od_id']; ?>&amp;uid=<?php echo $uid; ?>" class="ord_name"><?php echo $row['it_name']; ?>상품명입니다</a>
			<br>
			<span class="sound_only">주문금액</span>
			<span class="cost"><?php echo display_price($row['od_cart_price'] + $row['od_send_cost'] + $row['od_send_cost2']); ?></span>
    		<!-- <span clsass="sound_only">입금액</span>
    		<?php echo display_price($row['od_receipt_price']); ?> -->
    		<span class="misu">(미입금액 : <?php echo display_price($row['od_misu']); ?>)</span>
		</div>
		<div class="smb_my_od_li smb_my_od_li3">
			<span class="sound_only">상태</span>
			<?php echo $od_status; ?>
		</div>
	</li>
	<?php
}

if ($i == 0)
    echo '<li class="empty_table">주문 내역이 없습니다.</li>';
?>
</ul>
*/
?>
<!-- } 주문 내역 목록 끝 -->