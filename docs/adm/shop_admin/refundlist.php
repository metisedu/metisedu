<?php
$sub_menu = '300130';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '환불관리';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$where = array();

$doc = strip_tags($doc);
$sort1 = in_array($sort1, array('od_id', 'od_cart_price', 'od_receipt_price', 'od_cancel_price', 'od_misu', 'od_cash')) ? $sort1 : '';
$sort2 = in_array($sort2, array('desc', 'asc')) ? $sort2 : 'desc';
$sel_field = get_search_string($sel_field);
if( !in_array($sel_field, array('od_id', 'mb_id', 'od_name', 'od_tel', 'od_hp', 'od_b_name', 'od_b_tel', 'od_b_hp', 'od_deposit_name', 'od_invoice')) ){   //검색할 필드 대상이 아니면 값을 제거
    $sel_field = '';
}
$od_status = get_search_string($od_status);
$search = get_search_string($search);
if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = '';
if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = '';

$od_misu = preg_replace('/[^0-9a-z]/i', '', $od_misu);
$od_cancel_price = preg_replace('/[^0-9a-z]/i', '', $od_cancel_price);
$od_refund_price = preg_replace('/[^0-9a-z]/i', '', $od_refund_price);
$od_receipt_point = preg_replace('/[^0-9a-z]/i', '', $od_receipt_point);
$od_coupon = preg_replace('/[^0-9a-z]/i', '', $od_coupon);

$sql_search = "";
if ($search != "") {
    if ($sel_field != "") {
        $where[] = " $sel_field like '%$search%' ";
    }

    if ($save_search != $search) {
        $page = 1;
    }
}

$where[] = " od_status IN('환불신청','환불') and od_refund_sub_price > 0 ";

if ($od_settle_case) {
    $where[] = " od_settle_case = '$od_settle_case' ";
}

if ($od_misu) {
    $where[] = " od_misu != 0 ";
}

if ($od_cancel_price) {
    $where[] = " od_cancel_price != 0 ";
}

if ($od_refund_price) {
    $where[] = " od_refund_price != 0 ";
}

if ($od_receipt_point) {
    $where[] = " od_receipt_point != 0 ";
}

if ($od_coupon) {
    $where[] = " ( od_cart_coupon > 0 or od_coupon > 0 or od_send_coupon > 0 ) ";
}

if ($od_escrow) {
    $where[] = " od_escrow = 1 ";
}

if ($fr_date && $to_date) {
    $where[] = " od_time between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
}

if ($where) {
    $sql_search = ' where '.implode(' and ', $where);
}

if ($sel_field == "")  $sel_field = "od_id";
if ($sort1 == "") $sort1 = "od_id";
if ($sort2 == "") $sort2 = "desc";

$sql_common = " from {$g5['g5_shop_order_table']} $sql_search ";

$sql = " select count(od_id) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql  = " select *,
            (od_cart_coupon + od_coupon + od_send_coupon) as couponprice
           $sql_common
           order by $sort1 $sort2
           limit $from_record, $rows ";
//echo"<p>".$sql;
$result = sql_query($sql);

$qstr1 = "od_status=".urlencode($od_status)."&amp;od_settle_case=".urlencode($od_settle_case)."&amp;od_misu=$od_misu&amp;od_cancel_price=$od_cancel_price&amp;od_refund_price=$od_refund_price&amp;od_receipt_point=$od_receipt_point&amp;od_coupon=$od_coupon&amp;fr_date=$fr_date&amp;to_date=$to_date&amp;sel_field=$sel_field&amp;search=$search&amp;save_search=$search";
if($default['de_escrow_use'])
    $qstr1 .= "&amp;od_escrow=$od_escrow";
$qstr = "$qstr1&amp;sort1=$sort1&amp;sort2=$sort2&amp;page=$page";

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

// 주문삭제 히스토리 테이블 필드 추가
if(!sql_query(" select mb_id from {$g5['g5_shop_order_delete_table']} limit 1 ", false)) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_order_delete_table']}`
                    ADD `mb_id` varchar(20) NOT NULL DEFAULT '' AFTER `de_data`,
                    ADD `de_ip` varchar(255) NOT NULL DEFAULT '' AFTER `mb_id`,
                    ADD `de_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `de_ip` ", true);
}
?>
<div class="card card-inverse card-flat">

	<div class="card-block">
		<div class="row">
			<div class="col-md-12">

				<div class="local_ov01 local_ov">
					<?php echo $listall; ?>
					<span class="btn_ov01"><span class="ov_txt">전체 환불내역</span><span class="ov_num"> <?php echo number_format($total_count); ?>건</span></span>
					<?php if($od_status == '준비' && $total_count > 0) { ?>
					<a href="./orderdelivery.php" id="order_delivery" class="ov_a">엑셀배송처리</a>
					<?php } ?>
				</div>

				<form name="frmorderlist" class="local_sch01 local_sch">
				<input type="hidden" name="doc" value="<?php echo $doc; ?>">
				<input type="hidden" name="sort1" value="<?php echo $sort1; ?>">
				<input type="hidden" name="sort2" value="<?php echo $sort2; ?>">
				<input type="hidden" name="page" value="<?php echo $page; ?>">
				<input type="hidden" name="save_search" value="<?php echo $search; ?>">

				<label for="sel_field" class="sound_only">검색대상</label>
				<select name="sel_field" id="sel_field">
					<option value="od_id" <?php echo get_selected($sel_field, 'od_id'); ?>>주문번호</option>
					<option value="mb_id" <?php echo get_selected($sel_field, 'mb_id'); ?>>아이디</option>
					<option value="od_name" <?php echo get_selected($sel_field, 'od_name'); ?>>이름</option>
					<option value="od_hp" <?php echo get_selected($sel_field, 'od_hp'); ?>>핸드폰번호</option>
				</select>

				<label for="search" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
				<input type="text" name="search" value="<?php echo $search; ?>" id="search" required class="required frm_input" autocomplete="off">
				<input type="submit" value="검색" class="btn_submit">

				</form>

				<form class="local_sch03 local_sch">
				<!--
				<div>
					<strong>결제수단</strong>
					<input type="radio" name="od_settle_case" value="" id="od_settle_case01"        <?php echo get_checked($od_settle_case, '');          ?>>
					<label for="od_settle_case01">전체</label>
					<input type="radio" name="od_settle_case" value="무통장" id="od_settle_case02"   <?php echo get_checked($od_settle_case, '무통장');    ?>>
					<label for="od_settle_case02">무통장</label>
					<input type="radio" name="od_settle_case" value="가상계좌" id="od_settle_case03" <?php echo get_checked($od_settle_case, '가상계좌');  ?>>
					<label for="od_settle_case03">가상계좌</label>
					<input type="radio" name="od_settle_case" value="계좌이체" id="od_settle_case04" <?php echo get_checked($od_settle_case, '계좌이체');  ?>>
					<label for="od_settle_case04">계좌이체</label>
					<input type="radio" name="od_settle_case" value="신용카드" id="od_settle_case06" <?php echo get_checked($od_settle_case, '신용카드');  ?>>
					<label for="od_settle_case06">신용카드</label>
				</div>
				-->
				<div class="sch_last">
					<strong>주문일자</strong>
					<input type="text" id="fr_date"  name="fr_date" value="<?php echo $fr_date; ?>" class="frm_input" size="10" maxlength="10"> ~
					<input type="text" id="to_date"  name="to_date" value="<?php echo $to_date; ?>" class="frm_input" size="10" maxlength="10">
					<button type="button" onclick="javascript:set_date('오늘');">오늘</button>
					<button type="button" onclick="javascript:set_date('어제');">어제</button>
					<button type="button" onclick="javascript:set_date('이번주');">이번주</button>
					<button type="button" onclick="javascript:set_date('이번달');">이번달</button>
					<button type="button" onclick="javascript:set_date('지난주');">지난주</button>
					<button type="button" onclick="javascript:set_date('지난달');">지난달</button>
					<button type="button" onclick="javascript:set_date('전체');">전체</button>
					<input type="submit" value="검색" class="btn_submit">
				</div>
				</form>

				<form name="forderlist" id="forderlist" onsubmit="return forderlist_submit(this);" method="post" autocomplete="off">
				<input type="hidden" name="search_od_status" value="<?php echo $od_status; ?>">

				<div class="tbl_head01 tbl_wrap">
					<table id="sodr_list">
					<caption>주문 내역 목록</caption>
					<thead>
					<tr>
						<th scope="col">
							<label for="chkall" class="sound_only">주문 전체</label>
							<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
						</th>
						<th scope="col" ><a href="<?php echo title_sort("od_id", 1)."&amp;$qstr1"; ?>">주문번호</a></th>
						<th scope="col" >이름</th>
						<th scope="col" >아이디</th>
						<th scope="col" >금액</th>
						<th scope="col" >환불신청일</th>
						<th scope="col" >환불사유</th>
						<th scope="col" >관리</th>
					</tr>
					</thead>
					<tbody>
					<?php
					for ($i=0; $row=sql_fetch_array($result); $i++)
					{
						// 결제 수단
						$s_receipt_way = $s_br = "";
						if ($row['od_settle_case'])
						{
							$s_receipt_way = $row['od_settle_case'];
							$s_br = '<br />';

							// 간편결제
							if($row['od_settle_case'] == '간편결제') {
								switch($row['od_pg']) {
									case 'lg':
										$s_receipt_way = 'PAYNOW';
										break;
									case 'inicis':
										$s_receipt_way = 'KPAY';
										break;
									case 'kcp':
										$s_receipt_way = 'PAYCO';
										break;
									default:
										$s_receipt_way = $row['od_settle_case'];
										break;
								}
							}
						}
						else
						{
							$s_receipt_way = '결제수단없음';
							$s_br = '<br />';
						}

						if ($row['od_receipt_point'] > 0)
							$s_receipt_way .= $s_br."포인트";

						$mb_nick = get_sideview($row['mb_id'], get_text($row['od_name']), $row['od_email'], '');

						$od_cnt = 0;
						if ($row['mb_id'])
						{
							$sql2 = " select count(*) as cnt from {$g5['g5_shop_order_table']} where mb_id = '{$row['mb_id']}' ";
							$row2 = sql_fetch($sql2);
							$od_cnt = $row2['cnt'];
						}

						// 주문 번호에 device 표시
						$od_mobile = '';
						if($row['od_mobile'])
							$od_mobile = '(M)';

						// 주문번호에 - 추가
						switch(strlen($row['od_id'])) {
							case 16:
								$disp_od_id = substr($row['od_id'],0,8).'-'.substr($row['od_id'],8);
								break;
							default:
								$disp_od_id = substr($row['od_id'],0,6).'-'.substr($row['od_id'],6);
								break;
						}

						// 주문 번호에 에스크로 표시
						$od_paytype = '';
						if($row['od_test'])
							$od_paytype .= '<span class="list_test">테스트</span>';

						if($default['de_escrow_use'] && $row['od_escrow'])
							$od_paytype .= '<span class="list_escrow">에스크로</span>';

						$uid = md5($row['od_id'].$row['od_time'].$row['od_ip']);

						$invoice_time = is_null_time($row['od_invoice_time']) ? G5_TIME_YMDHIS : $row['od_invoice_time'];
						$delivery_company = $row['od_delivery_company'] ? $row['od_delivery_company'] : $default['de_delivery_company'];

						$bg = 'bg'.($i%2);
						$td_color = 0;
						if($row['od_cancel_price'] > 0) {
							$bg .= 'cancel';
							$td_color = 1;
						}
					?>
					<tr class="orderlist<?php echo ' '.$bg; ?>">
						<td class="td_chk">
							<input type="hidden" name="od_id[<?php echo $i ?>]" value="<?php echo $row['od_id'] ?>" id="od_id_<?php echo $i ?>">
							<label for="chk_<?php echo $i; ?>" class="sound_only">주문번호 <?php echo $row['od_id']; ?></label>
							<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
						</td>
						<td headers="th_ordnum" class="td_odrnum2">
							<a href="<?php echo G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $row['od_id']; ?>&amp;uid=<?php echo $uid; ?>" class="orderitem"><?php echo $disp_od_id; ?></a>
							<?php echo $od_mobile; ?>
							<?php echo $od_paytype; ?>
						</td>
						<td headers="th_odrer" class="td_name"><?php echo $mb_nick; ?></td>
						<td headers="th_odrertel" class="td_tel"><?php echo get_text($row['mb_id']); ?></td>
						<td headers="th_recvr" class="td_num"><?php echo number_format($row['od_refund_sub_price']); ?> 원</td>
						<td class="td_name"><?php echo $row['od_refund_time']; ?></td>
						<td class=""><?php echo get_text($row['od_refund_memo']); ?></td>
						<td class="td_mng td_mng_s">
							<a href="./orderform.php?od_id=<?php echo $row['od_id']; ?>&amp;<?php echo $qstr; ?>" class="mng_mod btn btn_02"><span class="sound_only"><?php echo $row['od_id']; ?> </span>보기</a>
						</td>
					</tr>
					<?php
					}

					if ($i == 0)
						echo '<tr><td colspan="12" class="empty_table">자료가 없습니다.</td></tr>';
					?>
					</tbody>
					</table>
				</div>

				<div class="local_desc02 local_desc">
				<p>
					<strong>주의!</strong> 주문번호를 클릭하여 나오는 주문상세내역의 주소를 외부에서 조회가 가능한곳에 올리지 마십시오.
				</p>
				</div>

				</form>

				<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
			</div>
		</div>
	</div>
</div>
<script>
$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });

    // 주문상품보기
    $(".orderitem").on("click", function() {
        var $this = $(this);
        var od_id = $this.text().replace(/[^0-9]/g, "");

        if($this.next("#orderitemlist").size())
            return false;

        $("#orderitemlist").remove();

        $.post(
            "./ajax.orderitem.php",
            { od_id: od_id },
            function(data) {
                $this.after("<div id=\"orderitemlist\"><div class=\"itemlist\"></div></div>");
                $("#orderitemlist .itemlist")
                    .html(data)
                    .append("<div id=\"orderitemlist_close\"><button type=\"button\" id=\"orderitemlist-x\" class=\"btn_frmline\">닫기</button></div>");
            }
        );

        return false;
    });

    // 상품리스트 닫기
    $(".orderitemlist-x").on("click", function() {
        $("#orderitemlist").remove();
    });

    $("body").on("click", function() {
        $("#orderitemlist").remove();
    });

    // 엑셀배송처리창
    $("#order_delivery").on("click", function() {
        var opt = "width=600,height=450,left=10,top=10";
        window.open(this.href, "win_excel", opt);
        return false;
    });
});

function set_date(today)
{
    <?php
    $date_term = date('w', G5_SERVER_TIME);
    $week_term = $date_term + 7;
    $last_term = strtotime(date('Y-m-01', G5_SERVER_TIME));
    ?>
    if (today == "오늘") {
        document.getElementById("fr_date").value = "<?php echo G5_TIME_YMD; ?>";
        document.getElementById("to_date").value = "<?php echo G5_TIME_YMD; ?>";
    } else if (today == "어제") {
        document.getElementById("fr_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME - 86400); ?>";
        document.getElementById("to_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME - 86400); ?>";
    } else if (today == "이번주") {
        document.getElementById("fr_date").value = "<?php echo date('Y-m-d', strtotime('-'.$date_term.' days', G5_SERVER_TIME)); ?>";
        document.getElementById("to_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
    } else if (today == "이번달") {
        document.getElementById("fr_date").value = "<?php echo date('Y-m-01', G5_SERVER_TIME); ?>";
        document.getElementById("to_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
    } else if (today == "지난주") {
        document.getElementById("fr_date").value = "<?php echo date('Y-m-d', strtotime('-'.$week_term.' days', G5_SERVER_TIME)); ?>";
        document.getElementById("to_date").value = "<?php echo date('Y-m-d', strtotime('-'.($week_term - 6).' days', G5_SERVER_TIME)); ?>";
    } else if (today == "지난달") {
        document.getElementById("fr_date").value = "<?php echo date('Y-m-01', strtotime('-1 Month', $last_term)); ?>";
        document.getElementById("to_date").value = "<?php echo date('Y-m-t', strtotime('-1 Month', $last_term)); ?>";
    } else if (today == "전체") {
        document.getElementById("fr_date").value = "";
        document.getElementById("to_date").value = "";
    }
}
</script>

<script>
function forderlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    /*
    switch (f.od_status.value) {
        case "" :
            alert("변경하실 주문상태를 선택하세요.");
            return false;
        case '주문' :

        default :

    }
    */

    if(document.pressed == "선택삭제") {
        if(confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            f.action = "./orderlistdelete.php";
            return true;
        }
        return false;
    }

    var change_status = f.od_status.value;

    if (f.od_status.checked == false) {
        alert("주문상태 변경에 체크하세요.");
        return false;
    }

    var chk = document.getElementsByName("chk[]");

    for (var i=0; i<chk.length; i++)
    {
        if (chk[i].checked)
        {
            var k = chk[i].value;
            var current_settle_case = f.elements['current_settle_case['+k+']'].value;
            var current_status = f.elements['current_status['+k+']'].value;

            switch (change_status)
            {
                case "입금" :
                    if (!(current_status == "주문" && current_settle_case == "무통장")) {
                        alert("'주문' 상태의 '무통장'(결제수단)인 경우에만 '입금' 처리 가능합니다.");
                        return false;
                    }
                    break;

                case "준비" :
                    if (current_status != "입금") {
                        alert("'입금' 상태의 주문만 '준비'로 변경이 가능합니다.");
                        return false;
                    }
                    break;

                case "배송" :
                    if (current_status != "준비") {
                        alert("'준비' 상태의 주문만 '배송'으로 변경이 가능합니다.");
                        return false;
                    }

                    var invoice      = f.elements['od_invoice['+k+']'];
                    var invoice_time = f.elements['od_invoice_time['+k+']'];
                    var delivery_company = f.elements['od_delivery_company['+k+']'];

                    if ($.trim(invoice_time.value) == '') {
                        alert("배송일시를 입력하시기 바랍니다.");
                        invoice_time.focus();
                        return false;
                    }

                    if ($.trim(delivery_company.value) == '') {
                        alert("배송업체를 입력하시기 바랍니다.");
                        delivery_company.focus();
                        return false;
                    }

                    if ($.trim(invoice.value) == '') {
                        alert("운송장번호를 입력하시기 바랍니다.");
                        invoice.focus();
                        return false;
                    }

                    break;
            }
        }
    }

    if (!confirm("선택하신 주문서의 주문상태를 '"+change_status+"'상태로 변경하시겠습니까?"))
        return false;

    f.action = "./orderlistupdate.php";
    return true;
}
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
