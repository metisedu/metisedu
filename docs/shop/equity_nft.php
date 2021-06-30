<?php
include_once('./_common.php');
define('_NOHEADER_',true);

if (!$is_member)
    goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_SHOP_URL."/mypage.php"));


$g5['title'] = 'Equity NFT';
include_once('./_head.php');

$latest_good_sql = "SELECT d.mb_id, d.wr_id, d.bg_flag, t.wr_subject, t.wr_good FROM han_board_good d".
    " LEFT JOIN han_write_tutor t ON t.wr_id = d.wr_id".
    " WHERE d.bo_table='tutor' and d.mb_id='".$member["mb_id"]."'".
    " and d.bg_datetime IN (SELECT max(d2.bg_datetime) FROM han_board_good d2 WHERE d2.bo_table=d.bo_table and d2.mb_id = d.mb_id and d2.wr_id=d.wr_id)".
    " GROUP BY d.wr_id".
    " ORDER BY d.bg_datetime DESC;";
$latest_good_res = sql_query($latest_good_sql);
while($good_row = sql_fetch_array($latest_good_res)){
    $good_data[] = $good_row;
}

// han_board_good 에서 나의 내역 중 마지막거를 가져오고
// 그 강사의 정보를 가져오고
// 제목과 함께 5% / 굿 갯수에 따른 리워드 표시

//$po_tot_sql = "SELECT COUNT(*) AS cnt FROM han_point WHERE mb_id = '".$member["mb_id"]."'";
//$po_tot_row = sql_fetch($po_tot_sql);
//$total_page = $po_tot_row["cnt"];
//
//
//$rows = 5;
//$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
//if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
//$from_record = ($page - 1) * $rows; // 시작 열을 구함
//
//
//$po_sql = "SELECT * FROM han_point WHERE mb_id = '".$member["mb_id"]."' ORDER BY po_id DESC limit {$from_record}, {$rows} ";
//$po_res = sql_query($po_sql);
//while($po_row = sql_fetch_array($po_res)){
//    $po_data[] = $po_row;
//}


?>
    <style>
        .div_tot div {height:60px;width:50%;float:left;}
    </style>
    <!-- 마이페이지 시작 { -->
    <section id="sub_mypage">
        <div class="container">
            <div class="row">
                <?php
                include_once(G5_THEME_PATH.'/mypage.left.php');
                ?>
                <div class="col-md-9 col-sm-12 col-xs-12 my_con" style="padding-bottom: 80px;">
                    <div class="mypage-class">
                        <div class="mypage-class-menu">
                            <ul>
                                <li class="my_tab_btn" data-id="class"><a href="/shop/reward_log.php" class="active">Equity NFT</a></li>
                            </ul>
                        </div>

                        <table class="table">
                            <thead>
                            <tr>
                                <th class="text-center"><!-- 목록 -->Lecture</th>
                                <th class="text-center"><!-- 목록 -->Equity NFT</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php for($i=0; $i<count($good_data); $i++){ ?>
                                <tr class="item-list">
                                    <td class="w60">
                                        <div class="media">
                                            <div class="pull-left thumb" style="text-align:center;" >
                                                <?php echo $good_data[$i]["wr_subject"]; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="w60">
                                        <div class="media">
                                            <div class="pull-left thumb" style="text-align:center;" >
                                                <?php echo round(5 / $good_data[$i]["wr_good"], 6); ?>%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
        @media screen and (max-width: 768px){
            .list-item:after{content:'';background:url('') -300px -16px;}
        }
    </style>

<?php
include_once("./_tail.php");
?>