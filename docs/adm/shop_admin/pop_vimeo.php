<?php
$sub_menu = "200451";
include_once('./_common.php');
auth_check($auth[$sub_menu], 'r');
include_once(G5_PATH.'/head.sub.php');


$colspan = 9;

if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = '';
if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = '';


$qstr = "token=".$token."&amp;fr_date=".$fr_date."&amp;to_date=".$to_date."&amp;stx=".$stx."&amp;sfl=".$sfl;
$query_string = $qstr ? '?'.$qstr : '';



$sql_common = " from han_write_tutor ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'pr_pay_name' : 
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        case 'cl_car_number' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
      
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}


if ($fr_date && $to_date) {
//    $sql_search .= "AND reward_date between '{$fr_date} 00:00:00' and '{$to_date} 23:59:59'";
}



if (!$sst) {
    $sst  = "wr_id";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";





$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 20;
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
<body>

<form name="searchForm" action="?" class="local_sch03 local_sch" method="get">

	<div class="sch_last">	
		<strong>검색</strong>
		<select name="sfl" id="sfl">
			<option value="wr_subject"<?php echo get_selected($_GET['sfl'], "wr_subject"); ?>>제목</option>
		</select>
		<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
		<input type="submit" value="검색" id="search" class="btn_submit">
	</div>
</form>



<form name="frm" id="frm" action="./pay_request_update.php" onsubmit="return frm_submit(this);" method="post">
	<input type="hidden" name="sst" value="<?php echo $sst ?>">
	<input type="hidden" name="sod" value="<?php echo $sod ?>">
	<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
	<input type="hidden" name="stx" value="<?php echo $stx ?>">
	<input type="hidden" name="page" value="<?php echo $page ?>">
	<input type="hidden" name="qstr" value="<?php echo $qstr ?>">
	<input type="hidden" name="token" value="<?php echo $token ?>">
	<input type="hidden" name="it_id" value="<?php echo $it_id ?>">

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
			<th scope="col">제목</th>
			<th scope="col">연동된 강좌 링크</th>
			<th scope="col">연동 상품 링크</th>
			<th scope="col">-</th>
		</tr>
		</thead>
		<tbody>
		<?php
		for ($i=0; $row=sql_fetch_array($result); $i++) {
			$bg = 'bg'.($i%2);
			$it_sql = "SELECT it_name FROM han_shop_item WHERE it_id = '".$row["wr_10"]."'";
			$it_row = sql_fetch($it_sql);
			
		?>
		<tr class="<?php echo $bg; ?>">
			<td class="td_chk">
				<input type="hidden" name="wr_id[<?php echo $i ?>]" value="<?php echo $row['wr_id'] ?>">
				<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['wr_id']) ?></label>
				<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
			</td>
			<td class="td_numsmall"><?php echo $total_count - ($i + ( ( $page-1 ) * $rows ) )?></td>
			<td class=""><?php echo $row['wr_subject']?></td>
			<td class=""><?php echo $row["wr_link1"]?></td>
			<td class=""><?php echo $row["wr_10"]?> - <?php echo $it_row["it_name"];?> </td>
			<td>
				<a href="./pop_vimeo_update.php?<?php echo $qstr;?>&amp;w=u&amp;wr_id=<?php echo $row['wr_id'];?>&it_id=<?php echo $it_id;?>" class="btn btn_03">등록</a>
			</td>
		</tr>
		<script>
			function account_delete(qstr, wr_id){
				if(confirm("정말 삭제 하시겠습니까?")){
					location.href="./account_delete.php?"+qstr+"&w=u&wr_id="+wr_id;
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


?>
</body>