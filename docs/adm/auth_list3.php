<?php
$sub_menu = "100210";
include_once('./_common.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

$sql_common = " from {$g5['member_table']} ";

$sql_search = " where mb_level = 3 ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "mb_id";
    $sod = "";
}
$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search}
            {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *
            {$sql_common}
            {$sql_search}
            {$sql_order}
            limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall btn_ov02">전체목록</a>';

$g5['title'] = "강사 등록";
include_once('./admin.head.php');

$colspan = 5;
?>
<div class="card card-inverse card-flat">
	<div class="card-header">
		<div class="card-title"><?php echo $g5['title']?></div>
	</div>

	<div class="card-block">

		<div class="row">
			<div class="col-md-12">

				<div class="local_ov01 local_ov">
					<?php echo $listall ?>
					<span class="btn_ov01"><span class="ov_txt">강사</span><span class="ov_num"><?php echo number_format($total_count) ?>명</span></span>
				</div>

				<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
				<input type="hidden" name="sfl" value="mb_id" id="sfl">

				<label for="stx" class="sound_only">강사아이디<strong class="sound_only"> 필수</strong></label>
				<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
				<input type="submit" value="검색" id="fsearch_submit" class="btn_submit">

				</form>

				<form name="fauthlist" id="fauthlist" method="post" action="./auth_list_delete2.php" onsubmit="return fauthlist_submit(this);">
				<input type="hidden" name="sst" value="<?php echo $sst ?>">
				<input type="hidden" name="sod" value="<?php echo $sod ?>">
				<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
				<input type="hidden" name="stx" value="<?php echo $stx ?>">
				<input type="hidden" name="page" value="<?php echo $page ?>">
				<input type="hidden" name="token" value="">

				<div class="tbl_head01 tbl_wrap" class="table-responsive">
					<table class="">
					<caption><?php echo $g5['title']; ?> 목록</caption>
					<thead>
					<tr>
						<th scope="col">
							<label for="chkall" class="sound_only">현재 페이지 강사 전체</label>
							<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
						</th>
						<th scope="col"><?php echo subject_sort_link('a.mb_id') ?>강사아이디</a></th>
						<th scope="col"><?php echo subject_sort_link('mb_nick') ?>이름</a></th>
						<th scope="col">연락처</th>
						<th scope="col">권한</th>
					</tr>
					</thead>
					<tbody>
					<?php
					$count = 0;
					for ($i=0; $row=sql_fetch_array($result); $i++)
					{
						$mb_nick = get_sideview($row['mb_id'], $row['mb_name'], $row['mb_email'], $row['mb_homepage']);

						$bg = 'bg'.($i%2);
					?>
					<tr class="<?php echo $bg; ?>">
						<td class="td_chk">
							<input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>">
							<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['mb_nick'] ?>님 권한</label>
							<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
						</td>
						<td class="td_mbid"><a href="?sfl=a.mb_id&amp;stx=<?php echo $row['mb_id'] ?>"><?php echo $row['mb_id'] ?></a></td>
						<td class="td_auth_mbnick"><?php echo $mb_nick ?></td>
						<td class="td_auth"><?php echo $row['mb_hp']; ?></td>
						<td class="td_auth">
							<a href="/adm/auth_form3.php?w=u&mb_id=<?php echo $row['mb_id']; ?>"><button type="button" class="btn btn_03">수정</button></a>
							<?php
							/*
							if($row['mb_auth'] == "g"){
								echo "강사";
							}else{
								echo "슈퍼관리자";
							}
							*/
							?>
						</td>
					</tr>
					<?php
						$count++;
					}

					if ($count == 0)
						echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
					?>
					</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="btn_fixed_top">
	<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02">
	<?php if ($is_admin == 'super') { ?>
	<a href="./auth_form3.php" id="auth_add" class="btn btn_01">강사추가</a>
	<?php } ?>
</div>

<?php
//if (isset($stx))
//    echo '<script>document.fsearch.sfl.value = "'.$sfl.'";</script>'."\n";

if (strstr($sfl, 'mb_id'))
    $mb_id = $stx;
else
    $mb_id = '';
?>
</form>

<?php
$pagelist = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page=');
echo $pagelist;
?>

<script>
function fauthlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>
