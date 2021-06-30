<?php
$sub_menu = '400300';
include_once('./_common.php');

auth_check($auth[$sub_menu], "w");

$html_title = '강의검색';

$g5['title'] = $html_title;
include_once(G5_PATH.'/head.sub.php');

$sql_common = " from han_shop_lec ";
$sql_where = " where 1 ";

if($it_name){
    $it_name = preg_replace('/\!\?\*$#<>()\[\]\{\}/i', '', strip_tags($it_name));
    $sql_where .= " and it_name like '%".sql_real_escape_string($it_name)."%' ";
}

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common . $sql_where;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select lt_id, it_name, ca_id3, it_price
            $sql_common
            $sql_where
            order by lt_id
            limit $from_record, $rows ";
//echo"<p>".$sql;
$result = sql_query($sql);

$qstr1 = 'mb_name='.urlencode($mb_name);
?>

<div id="sch_member_frm" class="new_win scp_new_win">
    <h1>강의선택</h1>

    <form name="fmember" method="get">
    <div id="scp_list_find">
        <label for="mb_name">강의명</label>
        <input type="text" name="it_name" id="it_name" value="<?php echo get_text($it_name); ?>" class="frm_input required" required size="20">
        <input type="submit" value="검색" class="btn_frmline">
    </div>
    <div class="tbl_head01 tbl_wrap new_win_con">
        <table>
        <caption>검색결과</caption>
        <thead>
        <tr>
            <th>강의명</th>
            <th>강의코드</th>
            <th>선택</th>
        </tr>
        </thead>
        <tbody>
        <?php
        for($i=0; $row=sql_fetch_array($result); $i++) {
        ?>
        <tr>
            <td class="td_mbname"><?php echo get_text($row['it_name']); ?></td>
            <td class="td_left"><?php echo $row['lt_id']; ?></td>
            <td class="scp_find_select td_mng td_mng_s"><button type="button" class="btn btn_03" onclick="sel_lt_id('<?php echo $row['lt_id']; ?>','<?php echo $row['it_name']; ?>','<?php echo $row['it_t_name']; ?>','<?php echo $row['it_price']; ?>');">선택</button></td>
        </tr>
        <?php
        }

        if($i ==0)
            echo '<tr><td colspan="3" class="empty_table">검색된 자료가 없습니다.</td></tr>';
        ?>
        </tbody>
        </table>
    </div>
    </form>

    <?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr1.'&amp;page='); ?>

    <div class="btn_confirm01 btn_confirm win_btn">
        <button type="button" onclick="window.close();" class="btn_close btn">닫기</button>
    </div>
</div>

<script>
function sel_lt_id(itemCode, lectureName, teacherName, lecturePrice)
{
	window.opener.fnItemActionOne(itemCode, lectureName, teacherName, lecturePrice);
    window.close();
}
</script>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>