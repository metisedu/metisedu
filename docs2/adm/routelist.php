<?php
$sub_menu = "300300";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

// 체크된 자료 삭제
if (isset($_POST['chk']) && is_array($_POST['chk'])) {
    for ($i=0; $i<count($_POST['chk']); $i++) {
        $ro_id = (int) $_POST['chk'][$i];

        sql_query(" delete from han_route where ro_id = '$ro_id' ", true);
    }
}

// 인지경로 추가
if(isset($_POST['ro_name'])){
	$rst = sql_fetch("SELECT * FROM han_route WHERE ro_name = '".$_POST['ro_name']."' ");
	if(!$rst){
		sql_query("INSERT INTO han_route SET ro_name = '".$_POST['ro_name']."', ro_time = '".date("Y-m-d H:i:s")."' ");
	}
}

$sql_common = " from han_route a ";
$sql_search = " where (1) ";

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "ro_name" :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
        case "ro_time" :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "ro_id";
    $sod = "desc";
}
$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search}
            {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *
            {$sql_common}
            {$sql_search}
            {$sql_order}
            limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '인지경로 관리';
include_once('./admin.head.php');

$colspan = 4;
?>

<script>
var list_update_php = '';
var list_delete_php = 'routelist.php';
</script>
<div class="card card-inverse card-flat">
	<div class="card-header">
		<div class="card-title"></div>
	</div>

	<div class="card-block">

		<div class="row">
			<div class="col-md-12">


				<div class="local_ov01 local_ov">
						<?php echo $listall ?>
						<span class="btn_ov01"><span class="ov_txt">건수</span><span class="ov_num">  <?php echo number_format($total_count) ?>개</span></span>
				</div>

				<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
				<div class="sch_last">
					<label for="sfl" class="sound_only">검색대상</label>
					<select name="sfl" id="sfl">
						<option value="ro_name"<?php echo get_selected($_GET['sfl'], "ro_name"); ?>>인지경로</option>
						<option value="ro_time"<?php echo get_selected($_GET['sfl'], "ro_time"); ?>>등록일</option>
					</select>
					<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
					<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
					<input type="submit" value="검색" class="btn_submit">
				</div>
				</form>

				<form name="nFrom" id="nFrom" class="local_sch01 local_sch" method="post">
				<div class="sch_last">
					<input type="text" name="ro_name" value="" id="stx" title="인지경로" required class="required frm_input">
					<button type="submit" class="btn btn_01">인지경로 추가</button>
				</div>
				</form>

				<form name="fpopularlist" id="fpopularlist" method="post">
				<input type="hidden" name="sst" value="<?php echo $sst ?>">
				<input type="hidden" name="sod" value="<?php echo $sod ?>">
				<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
				<input type="hidden" name="stx" value="<?php echo $stx ?>">
				<input type="hidden" name="page" value="<?php echo $page ?>">
				<input type="hidden" name="token" value="<?php echo $token ?>">

				<div class="tbl_head01 tbl_wrap table-responsive">
					<table class="">
					<caption><?php echo $g5['title']; ?> 목록</caption>
					<thead>
					<tr>
						<th scope="col">
							<label for="chkall" class="sound_only">현재 페이지 인지경로 전체</label>
							<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
						</th>
						<th scope="col"><?php echo subject_sort_link('ro_name') ?>인지경로</a></th>
						<th scope="col">등록일</th>
					</tr>
					</thead>
					<tbody>
					<?php
					for ($i=0; $row=sql_fetch_array($result); $i++) {

						$word = get_text($row['ro_name']);
						$bg = 'bg'.($i%2);
					?>

					<tr class="<?php echo $bg; ?>">
						<td class="td_chk">
							<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $word ?></label>
							<input type="checkbox" name="chk[]" value="<?php echo $row['ro_id'] ?>" id="chk_<?php echo $i ?>">
						</td>
						<td class="td_left"><a href="<?php echo $_SERVER['SCRIPT_NAME'] ?>?sfl=pp_word&amp;stx=<?php echo $word ?>"><?php echo $word ?></a></td>
						<td><?php echo $row['ro_time'] ?></td>
					</tr>

					<?php
					}

					if ($i == 0)
						echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
					?>
					</tbody>
					</table>

				</div>

				<?php if ($is_admin == 'super'){ ?>
				<div class=" btn_fixed_top">
					<button type="submit" class="btn btn_02">선택삭제</button>
				</div>
				<?php } ?>

				</form>

				<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
			</div>
		</div>
	</div>
</div>

<script>
$(function() {
    $('#fpopularlist').submit(function() {
        if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
            if (!is_checked("chk[]")) {
                alert("선택삭제 하실 항목을 하나 이상 선택하세요.");
                return false;
            }

            return true;
        } else {
            return false;
        }
    });
});
</script>

<?php
include_once('./admin.tail.php');
?>
