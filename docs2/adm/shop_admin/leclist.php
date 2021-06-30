<?php
$sub_menu = '400200';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '강의관리';
include_once (G5_ADMIN_PATH.'/admin.head.php');

// 분류
$ca_list  = '<option value="">선택</option>'.PHP_EOL;
$sql = " select * from {$g5['g5_shop_category_table']} ";
if ($is_admin != 'super')
    $sql .= " where ca_mb_id = '{$member['mb_id']}' ";
$sql .= " order by ca_order, ca_id ";
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++)
{
    $len = strlen($row['ca_id']) / 2 - 1;
    $nbsp = '';
    for ($i=0; $i<$len; $i++) {
        $nbsp .= '&nbsp;&nbsp;&nbsp;';
    }
    $ca_list .= '<option value="'.$row['ca_id'].'">'.$nbsp.$row['ca_name'].'</option>'.PHP_EOL;
}

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

$sQuery = " SELECT *
			FROM g5_shop_chapter
			WHERE it_id = '".$it_id."'
			ORDER BY (cp_order + 0) ASC
			";
$sqla = sql_query($sQuery);

$sql_common = " from han_shop_lec
               where 1 ";
$sql_common .= $sql_search;

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

if (!$sst) {
    $sst  = "lt_id";
    $sod = "desc";
}
$sql_order = "order by $sst $sod";


$sql  = " select *
           $sql_common
           $sql_order
           limit $from_record, $rows ";
//echo"<p>".$sql;
$result = sql_query($sql);

//$qstr  = $qstr.'&amp;sca='.$sca.'&amp;page='.$page;
$qstr  = $qstr.'&amp;sca='.$sca.'&amp;page='.$page.'&amp;save_stx='.$stx;

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';
?>
<div class="card card-inverse card-flat">

	<div class="card-block">
		<div class="row">
			<div class="col-md-12">

				<div class="local_ov01 local_ov">
					<?php echo $listall; ?>
					<span class="btn_ov01"><span class="ov_txt">등록된 강의</span><span class="ov_num"> <?php echo $total_count; ?>건</span></span>
				</div>

				<form name="flist" class="local_sch01 local_sch">
				<input type="hidden" name="save_stx" value="<?php echo $stx; ?>">

				<label for="sca" class="sound_only">분류선택</label>
				<select name="sca" id="sca">
					<option value="">전체분류</option>
					<?php
					$sql1 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} order by ca_order, ca_id ";
					$result1 = sql_query($sql1);
					for ($i=0; $row1=sql_fetch_array($result1); $i++) {
						$len = strlen($row1['ca_id']) / 2 - 1;
						$nbsp = '';
						for ($i=0; $i<$len; $i++) $nbsp .= '&nbsp;&nbsp;&nbsp;';
						echo '<option value="'.$row1['ca_id'].'" '.get_selected($sca, $row1['ca_id']).'>'.$nbsp.$row1['ca_name'].'</option>'.PHP_EOL;
					}
					?>
				</select>

				<label for="sfl" class="sound_only">검색대상</label>
				<select name="sfl" id="sfl">
					<option value="it_name" <?php echo get_selected($sfl, 'it_name'); ?>>강의명</option>
					<option value="lt_id" <?php echo get_selected($sfl, 'lt_id'); ?>>강의코드</option>
					<option value="it_time" <?php echo get_selected($sfl, 'it_time'); ?>>등록일</option>
				</select>

				<label for="stx" class="sound_only">검색어</label>
				<input type="text" name="stx" value="<?php echo $stx; ?>" id="stx" class="frm_input">
				<input type="submit" value="검색" class="btn_submit">

				</form>

				<form name="fitemlistupdate" method="post" action="./leclistupdate.php" onsubmit="return fitemlist_submit(this);" autocomplete="off" id="fitemlistupdate">
				<input type="hidden" name="sca" value="<?php echo $sca; ?>">
				<input type="hidden" name="sst" value="<?php echo $sst; ?>">
				<input type="hidden" name="sod" value="<?php echo $sod; ?>">
				<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
				<input type="hidden" name="stx" value="<?php echo $stx; ?>">
				<input type="hidden" name="page" value="<?php echo $page; ?>">

				<div class="tbl_head01 tbl_wrap table-responsive">
					<table>
					<caption><?php echo $g5['title']; ?> 목록</caption>
					<thead>
					<tr>
						<th scope="col">
							<label for="chkall" class="sound_only">강의 전체</label>
							<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
						</th>
						<th scope="col"><?php echo subject_sort_link('it_id', 'sca='.$sca); ?>강의명</a></th>
						<th scope="col">등록일</th>
						<th scope="col">강사명/업체명</th>
						<th scope="col">강의코드</th>
						<th scope="col">관리</th>
					</tr>
					</thead>
					<tbody>
					<?php
					for ($i=0; $row=sql_fetch_array($result); $i++)
					{
						$href = shop_item_url($row['it_id']);
						$bg = 'bg'.($i%2);

						$it_point = $row['it_point'];
						if($row['it_point_type'])
							$it_point .= '%';
					?>
					<tr class="<?php echo $bg; ?>">
						<td class="td_chk">
							<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['it_name']); ?></label>
							<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i; ?>">
						</td>

						<td class="">
							<?php echo get_text($row['it_name']); ?>
						</td>
						<td class="">
							<?php echo get_text($row['it_time']); ?>
						</td>
						<td class="td_sort">
							<label for="ca_id_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['it_name']); ?> 기본분류</label>
							<select name="ca_id[<?php echo $i; ?>]" id="ca_id_<?php echo $i; ?>">
								<?php echo conv_selected_option($ca_list, $row['ca_id']); ?>
							</select>
							<label for="ca_id2_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['it_name']); ?> 2차분류</label>
							<select name="ca_id2[<?php echo $i; ?>]" id="ca_id2_<?php echo $i; ?>">
								<?php echo conv_selected_option($ca_list, $row['ca_id2']); ?>
							</select>
							<label for="ca_id3_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['it_name']); ?> 3차분류</label>
							<select name="ca_id3[<?php echo $i; ?>]" id="ca_id3_<?php echo $i; ?>">
								<?php echo conv_selected_option($ca_list, $row['ca_id3']); ?>
							</select>
						</td>
						<td class="">
							<input type="hidden" name="lt_id[<?php echo $i; ?>]" value="<?php echo $row['lt_id']; ?>">
							<?php echo $row['lt_id']; ?>
						</td>
						<td class="td_mng td_mng_s">
							<a href="./lecform.php?w=u&amp;lt_id=<?php echo $row['lt_id']; ?>&amp;ca_id=<?php echo $row['ca_id']; ?>&amp;<?php echo $qstr; ?>" class="btn btn_03"><span class="sound_only"><?php echo htmlspecialchars2(cut_str($row['it_name'],250, "")); ?> </span>수정</a>
						</td>
					</tr>
					<?php
					}
					if ($i == 0)
						echo '<tr><td colspan="12" class="empty_table">등록된 강의가 없습니다.</td></tr>';
					?>
					</tbody>
					</table>
				</div>

				<div class="btn_fixed_top">

					<a href="./lecform.php" class="btn btn_01">강의등록</a>
					<input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn btn_02">
					<?php if ($is_admin == 'super') { ?>
					<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02">
					<?php } ?>
				</div>
				<!-- <div class="btn_confirm01 btn_confirm">
					<input type="submit" value="일괄수정" class="btn_submit" accesskey="s">
				</div> -->
				</form>

				<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
			</div>
		</div>
	</div>
</div>
<script>
function fitemlist_submit(f)
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

$(function() {
    $(".itemcopy").click(function() {
        var href = $(this).attr("href");
        window.open(href, "copywin", "left=100, top=100, width=300, height=200, scrollbars=0");
        return false;
    });
});

function excelform(url)
{
    var opt = "width=600,height=450,left=10,top=10";
    window.open(url, "win_excel", opt);
    return false;
}
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
