<?php
$sub_menu = "300161";
include_once('./_common.php');
$g5['title'] = '리워드로그';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
auth_check($auth[$sub_menu], 'r');



$colspan = 7;

if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = '';
if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = '';


$qstr = "token=".$token."&amp;fr_date=".$fr_date."&amp;to_date=".$to_date."&amp;stx=".$stx."&amp;sfl=".$sfl;
$query_string = $qstr ? '?'.$qstr : '';



$sql_common = " from han_point ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_id' : 
            $sql_search .= " ({$sfl} = '%{$stx}%') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}


if ($fr_date && $to_date) {
    $sql_search .= "AND po_datetime between '{$fr_date} 00:00:00' and '{$to_date} 23:59:59'";
}



if (!$sst) {
    $sst  = "po_datetime";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";





$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 10;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *
            {$sql_common}
            {$sql_search}
            {$sql_order}
            limit {$from_record}, {$rows} ";
$result = sql_query($sql);
?>		

<form name="searchForm" action="?" class="local_sch03 local_sch" method="get">
	
	<div class="sch_last">
		<strong>일자</strong>
		<input type="text" id="fr_date"  name="fr_date" value="<?php echo $fr_date; ?>" class="frm_input" size="10" maxlength="10" autocomplete="off"> ~
		<input type="text" id="to_date"  name="to_date" value="<?php echo $to_date; ?>" class="frm_input" size="10" maxlength="10" autocomplete="off">
		<button type="button" onclick="javascript:set_date('오늘');">오늘</button>
		<button type="button" onclick="javascript:set_date('어제');">어제</button>
		<button type="button" onclick="javascript:set_date('이번주');">이번주</button>
		<button type="button" onclick="javascript:set_date('이번달');">이번달</button>
		<button type="button" onclick="javascript:set_date('지난주');">지난주</button>
		<button type="button" onclick="javascript:set_date('지난달');">지난달</button>
		<button type="button" onclick="javascript:set_date('전체');">전체</button>
		<input type="submit" value="검색" class="btn_submit">
	</div>
	<div class="sch_last">	
		<select name="sfl" id="sfl">
			<option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>아이디</option>
		</select>
		<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
		<input type="submit" value="검색" id="search" class="btn_submit">
	</div>
</form>
<!--
<form name="forderprint" action="./pay_request_excel.php" autocomplete="off" class="local_sch03">
	<input type="hidden" name="page" value="<?php echo $page; ?>">
	<input type="hidden" name="sel_field" value="<?php echo $sel_field; ?>">
	<input type="hidden" name="search" value="<?php echo $search; ?>">
	<input type="hidden" name="stx" value="<?php echo $stx; ?>">
	<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">

	<input type="hidden" name="fr_date" value="<?php echo $fr_date; ?>">
	<input type="hidden" name="to_date" value="<?php echo $to_date; ?>">
	<input type="hidden" name="print_type" value="">
	<input type="hidden" name="od_id_chk" value="">
</form>
-->


<form name="frm" id="frm" action="./pay_request_update.php" onsubmit="return frm_submit(this);" method="post">
	<input type="hidden" name="sst" value="<?php echo $sst ?>">
	<input type="hidden" name="sod" value="<?php echo $sod ?>">
	<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
	<input type="hidden" name="stx" value="<?php echo $stx ?>">
	<input type="hidden" name="page" value="<?php echo $page ?>">
	<input type="hidden" name="qstr" value="<?php echo $qstr ?>">
	<input type="hidden" name="token" value="<?php echo $token ?>">

	<div class="tbl_head01 tbl_wrap">
		<table>
		<caption><?php echo $g5['title']; ?> 목록</caption>
		<thead>
		<tr>
			<th scope="col">
				<label for="chkall" class="sound_only">게시판 전체</label>
				<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
			</th>
			<th scope="col" id="mb_list_id">번호</th>
			<th scope="col"><?php echo subject_sort_link('po_datetime') ?>일시</th>
			<th scope="col"><?php echo subject_sort_link('mb_id') ?>아이디</th>

			<th scope="col">리워드내용</th>
			<th scope="col"><?php echo subject_sort_link('po_point') ?>리워드(포인트)</th>
			<!-- <th scope="col">보기</th> -->
		</tr>
		</thead>
		<tbody>
		<?php
		for ($i=0; $row=sql_fetch_array($result); $i++) {
		
			$bg = 'bg'.($i%2);

			//$member_sql = "select * from g5_member where mb_1 = '{$row['partner_account']}'";
			//$member_row = sql_fetch($member_sql);
		?>
		<tr class="<?php echo $bg; ?>">
			<td class="td_chk">
				<input type="hidden" name="po_id[<?php echo $i ?>]" value="<?php echo $row['po_id'] ?>">
				<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['po_id']) ?></label>
				<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
			</td>
			<td class="td_numsmall"><?php echo $total_count - ($i + ( ( $page-1 ) * $rows ) )?></td>
			<td class=""><?php echo $row['po_datetime']?></td>
			<td class=""><a href="/adm/reward_mem_list.php?sfl=mb_id&stx=<?php echo $row['mb_id']?>" style="text-decoration:underline;"><?php echo $row['mb_id']?></a></td>

			<td class=""><?php echo $row['po_content']?></td>
			<td class=""><?php echo number_format($row['po_point'])?></td>

			<!--
			<td>
				<button type="button" onclick="reward_delete('<?php echo $qstr;?>', '<?php echo $row["po_id"];?>')" class="btn btn_03">
					-
				</button>
			</td>
			-->
			<!-- <td class=""><button onclick="javascript:location.href='./member_request_delete.php?rq_no=<?php echo $row['rq_no'];?>'">삭제</button></td> -->
		</tr>
		<script>
			function reward_delete(qstr, po_id){
				if(confirm("정말 삭제 하시겠습니까?")){
					location.href="./reward_delete.php?"+qstr+"&w=u&po_id="+po_id;
				}else{
					//alert("취소하셨습니다.");
				} 
			}
		</script>
		<?php
		}
		if ($i == 0)
			echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없거나 관리자에 의해 삭제되었습니다.</td></tr>';
		?>
		</tbody>
		</table>
	</div>


	<div class="btn_fixed_top">
	    <?php if ($is_admin == 'super') { ?>
	    
		<!-- <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn_02 btn">	     -->
	    <?php } ?>
	</div>
</form>
<script>

function frm_submit(f)
{

	if(document.pressed == "선택삭제") {
		if(!is_checked("chk[]")) {
			alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
			return false;
		}
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

	if(document.pressed == "전체삭제") {
        if(!confirm("모든 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}

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
        document.getElementById("fr_date").value = "<?php echo date('Y-m-d', strtotime('-'.($date_term - 1).' days', G5_SERVER_TIME)); ?>";
        document.getElementById("to_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
    } else if (today == "이번달") {
        document.getElementById("fr_date").value = "<?php echo date('Y-m-01', G5_SERVER_TIME); ?>";
        document.getElementById("to_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
    } else if (today == "지난주") {
        document.getElementById("fr_date").value = "<?php echo date('Y-m-d', strtotime('-'.($week_term - 1).' days', G5_SERVER_TIME)); ?>";
        document.getElementById("to_date").value = "<?php echo date('Y-m-d', strtotime('-'.($week_term - 7).' days', G5_SERVER_TIME)); ?>";
    } else if (today == "지난달") {
        document.getElementById("fr_date").value = "<?php echo date('Y-m-01', strtotime('-1 Month', $last_term)); ?>";
        document.getElementById("to_date").value = "<?php echo date('Y-m-t', strtotime('-1 Month', $last_term)); ?>";
    } else if (today == "전체") {
        document.getElementById("fr_date").value = "";
        document.getElementById("to_date").value = "";
    }
}

$(function(){


    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });

	$.datepicker.setDefaults({
        dateFormat: 'yy-mm-dd',
        prevText: '이전 달',
        nextText: '다음 달',
        monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        dayNames: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
        showMonthAfterYear: true,
        yearSuffix: '년'
    });
	$(function() {
		$( ".schDate" ).datepicker({
			dateFormat: 'yy-mm-dd'
		});
	});
});



function forderprintcheck(type)
{
	$("input[name='print_type']").val(type);
	if(type=='chk'){
		if (!is_checked("chk[]")) {
			alert("다운로드하실 항목을 하나 이상 선택하세요.");
		}
		else{
			var od_id_array = new Array();
			$.each($("input[name='chk[]']:checked"),function(key,inputelement){
				od_id_array[key] = $("#od_id_"+inputelement.value).val();
			});
			var od_id_value = od_id_array.join();
			$("input[name='od_id_chk']").val(od_id_value);

			document.forderprint.submit();
		}
	}
	else if(type=='all'){
		var total_count = '<?=$total_count?>';
		if(total_count >10000){
			alert("10,000건 이하만 다운로드 가능합니다.");
		}
		else{
			document.forderprint.submit();
		}
	}
	else{
		if (!is_checked("chk[]")) {
			alert("다운로드하실 항목을 하나 이상 선택하세요.");
		}
		else{
			var win = window.open("", "winprint", "left=10,top=10,width=670,height=800,menubar=yes,toolbar=yes,scrollbars=yes");
			document.forderprint.target = "winprint";

			var od_id_array = new Array();
			$.each($("input[name='chk[]']:checked"),function(key,inputelement){
				od_id_array[key] = $("#od_id_"+inputelement.value).val();
			});
			var od_id_value = od_id_array.join();
			$("input[name='od_id_chk']").val(od_id_value);

			document.forderprint.submit();
		}
	}

	/*
	
	if (f.csv[0].checked || f.csv[1].checked)
    {
        f.target = "_top";
    }
    else
    {
        var win = window.open("", "winprint", "left=10,top=10,width=670,height=800,menubar=yes,toolbar=yes,scrollbars=yes");
        f.target = "winprint";
    }
	*/

    //f.submit();
}
</script>

<?php
$qstr .= "&amp;page=";

$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr");
echo $pagelist;

include_once('./admin.tail.php');
?>
