<?php
$sub_menu = "200200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from han_staking ";

$sql_search = " left join han_member on han_member.mb_id = han_staking.mb_id ";

if (!$sst) {
    $sst = "hs_id";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$g5['title'] = '회원 스테이킹 관리';
include_once('./admin.head.php');

$sql = " select han_member.mb_id, mb_hp, mb_email, hs_id, hs_months, hs_discount, hs_startdate, hs_enddate, mb_name, mb_tel, mb_datetime, hs_createdate {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$colspan = 16;
?>
<div class="card card-inverse card-flat">
    <div class="card-header">
        <div class="card-title"></div>
    </div>

    <div class="card-block">

        <div class="row">
            <div class="col-md-12">
                <form name="fmemberlist" id="fmemberlist" action="./member_list_update.php" onsubmit="return fmemberlist_submit(this);" method="post">
                    <input type="hidden" name="sst" value="<?php echo $sst ?>">
                    <input type="hidden" name="sod" value="<?php echo $sod ?>">
                    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
                    <input type="hidden" name="stx" value="<?php echo $stx ?>">
                    <input type="hidden" name="page" value="<?php echo $page ?>">
                    <input type="hidden" name="token" value="">

                    <div class="tbl_head01 tbl_wrap table-responsive">
                        <table>
                            <caption><?php echo $g5['title']; ?> 목록</caption>
                            <thead>
                            <tr>
                                <th scope="col" id="mb_list_id">아이디</th>
                                <th scope="col" id="mb_list_join">가입일</th>
                                <th scope="col" id="mb_list_join">스테이킹 신청일</th>
                                <th scope="col" id="mb_list_join">스테이킹 개월수</th>
                                <th scope="col" id="mb_list_join">할인율</th>
                                <th scope="col" id="mb_list_join">시작일</th>
                                <th scope="col" id="mb_list_join">종료일</th>
                                <th scope="col" id="mb_list_mng">관리</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            for ($i=0; $row=sql_fetch_array($result); $i++) {
                                if ($row['hs_startdate']) {
                                    $s_mod = '<a href="/adm/member_staking_update.php?' . $qstr . '&amp;month='.$row['hs_months'].'&amp;w=d&amp;hs_id=' . $row['hs_id'] . '" class="btn btn_01">승인취소</a>';
                                } else {
                                    $s_mod = '<a href="/adm/member_staking_update.php?' . $qstr . '&amp;month='.$row['hs_months'].'&amp;w=a&amp;hs_id=' . $row['hs_id'] . '" class="btn btn_03">승인</a>';
                                }

                                $mb_id = $row['mb_id'];

                                $bg = 'bg'.($i%2);
                                ?>

                                <tr class="<?php echo $bg; ?>">
                                    <td headers="mb_list_id" class="td_name sv_use">
                                        <?php echo $mb_id ?>
                                    </td>
                                    <td headers="mb_list_join" class="td_date"><?php echo substr($row['mb_datetime'],2,8); ?></td>
                                    <td headers="mb_list_join" class="td_date"><?php echo substr($row['hs_createdate'],2,8); ?></td>
                                    <td headers="mb_list_join" class="td_date"><?php echo $row['hs_months']; ?>개월</td>
                                    <td headers="mb_list_join" class="td_date"><?php echo $row['hs_discount']; ?>%</td>
                                    <td headers="mb_list_join" class="td_date"><?php echo substr($row['hs_startdate'],2,8); ?></td>
                                    <td headers="mb_list_join" class="td_date"><?php echo substr($row['hs_enddate'],2,8); ?></td>
                                    <td headers="mb_list_mng" class="td_mng td_mng_s"><?php echo $s_mod ?></td>
                                </tr>

                                <?php
                            }
                            if ($i == 0)
                                echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
                            ?>
                            </tbody>
                        </table>
                    </div>
                </form>

                <?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

            </div>
        </div>
    </div>
</div>


<script>
    function fmemberlist_submit(f)
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
