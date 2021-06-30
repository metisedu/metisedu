<?php
include_once("./_common.php");

$where = " and ";
$sql_search = "";
if ($stx != "") {
    if ($sfl != "") {
        $sql_search .= " $where $sfl like '%$stx%' ";
        $where = " and ";
    }
    if ($save_stx != $stx)
        $page = 1;
}

if ($sca != "") {
    $sql_search .= " $where (a.ca_id like '$sca%' or a.ca_id2 like '$sca%' or a.ca_id3 like '$sca%') ";
}

if ($sfl == "")  $sfl = "it_name";

$sql_common = " from {$g5['g5_shop_item_table']} a
               where 1";
$sql_common .= $sql_search;

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 5;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

if (!$sst) {
    $sst  = "it_id";
    $sod = "desc";
}
$sql_order = "order by $sst $sod";


$sql  = " select *
           $sql_common
           $sql_order
           limit $from_record, $rows ";
//echo"<p>".$sql;
$result = sql_query($sql);
?>
<div>
	<h2 id="modal1Title">강의 추가하기</h2>
	<p id="modal1Desc">
	<form name="flist" id="flist" class="local_sch01 local_sch">
	<input type="hidden" name="mb_id" value="<?php echo $mb_id; ?>">
	<input type="hidden" name="save_stx" value="<?php echo $stx; ?>">
		<label for="sfl" class="sound_only">검색대상</label>
		<select name="sfl" id="sfl">
			<option value="it_id" <?php echo get_selected($sfl, 'it_id'); ?>>판매상품코드</option>
			<option value="mb_id" <?php echo get_selected($sfl, 'mb_id'); ?>>강사명</option>
			<option value="it_name" <?php echo get_selected($sfl, 'it_name'); ?>>상품명</option>
		</select>

		<label for="stx" class="sound_only">검색어</label>
		<input type="text" name="stx" value="<?php echo $stx; ?>" id="stx" class="frm_input">
		<input type="button" value="검색" class="btn_submit pop_srh" id="pop_srh" style="cursor:pointer;">
	</form>
	</p>
	<form name="schlist" id="schlist">
	<input type="hidden" name="mb_id" value="<?php echo $mb_id; ?>">
	<input type="hidden" name="between_day" class="between_day" value="">
	<div class="tbl_frm01 tbl_wrap table-responsive">
		<table>
			<tr>
				<th>No.</th>
				<th>상품명</th>
				<th>강사명</th>
				<th>지급</th>
			</tr>
			<?php
			for($i = 0; $row = sql_fetch_array($result); $i++){
				$num = ($total_count - $from_record) - $i;
			?>
			<tr>
				<td><?php echo number_format($num); ?></td>
				<td><?php echo $row['it_name']; ?></td>
				<td><?php echo $row['it_id']; ?></td>
				<td><input type="radio" name="it_id" class="chk_it_id" value="<?php echo $row['it_id']; ?>" /></td>
			</tr>
			<?php
			}
			if($i == 0) echo"<tr><td colspan='4'>강의 내역이 없습니다.</td></tr>";
			?>
		</table>

	</div>
	<div>
		지급기한 <input type="text" name="fr_date" id="fr_date" class="frm_input" readonly autocomplete="off"> ~ <input type="text" name="to_date" id="to_date" class="frm_input" readonly autocomplete="off">
		총 <span class="between_day">0</span>일
	</div>
	</form>
</div>
<br>
<button class="remodal-confirm" id="add_lec_btn">확인</button>
<button data-remodal-action="cancel" class="remodal-cancel">취소</button>
<script>
function call(fr_date, to_date){
	var sdd = fr_date;
	var edd = to_date;
	var ar1 = sdd.split('-');
	var ar2 = edd.split('-');
	var da1 = new Date(ar1[0], ar1[1], ar1[2]);
	var da2 = new Date(ar2[0], ar2[1], ar2[2]);
	var dif = da2 - da1;
	var cDay = 24 * 60 * 60 * 1000;// 시 * 분 * 초 * 밀리세컨
	var cMonth = cDay * 30;// 월 만듬
	var cYear = cMonth * 12; // 년 만듬
	if(sdd && edd){
		return parseInt(dif/cDay);
	}
}

$(function() {
    $("#fr_date, #to_date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        showButtonPanel: true,
        yearRange: "c-1:c+99",
		onSelect: function (dateText, inst) {
			if($("#fr_date").val() != "" && $("#to_date").val() != ""){
				var bt_day = call($("#fr_date").val(), $("#to_date").val());
				$(".between_day").text(bt_day);
				$(".between_day").val(bt_day);
			}
		}
    });

	$(document).keyup(function(e){
		if(e.keyCode == 13 && $("#stx").is(":focus") ){
			$("#pop_srh").trigger('click');
			event.preventDefault();
		}
	});

	document.addEventListener('keydown', function(event) {
		if (event.keyCode === 13) {
			event.preventDefault();
		};
	}, true);
});
</script>