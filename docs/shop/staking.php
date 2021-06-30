<?php
include_once('./_common.php');
define('_NOHEADER_', true);

if (!$is_member)
    goto_url(G5_BBS_URL . "/login.php?url=" . urlencode(G5_SHOP_URL . "/mypage.php"));


$g5['title'] = 'Equity NFT';
include_once('./_head.php');

$latest_good_sql = "SELECT d.mb_id, d.wr_id, d.bg_flag, t.wr_subject, t.wr_good FROM han_board_good d" .
    " LEFT JOIN han_write_tutor t ON t.wr_id = d.wr_id" .
    " WHERE d.bo_table='tutor' and d.mb_id='" . $member["mb_id"] . "'" .
    " and d.bg_datetime IN (SELECT max(d2.bg_datetime) FROM han_board_good d2 WHERE d2.bo_table=d.bo_table and d2.mb_id = d.mb_id and d2.wr_id=d.wr_id)" .
    " GROUP BY d.wr_id" .
    " ORDER BY d.bg_datetime DESC;";
$latest_good_res = sql_query($latest_good_sql);
while ($good_row = sql_fetch_array($latest_good_res)) {
    $good_data[] = $good_row;
}


?>
    <style>
        .div_tot div {
            height: 60px;
            width: 50%;
            float: left;
        }

        input[type="radio"] {
            width: 15px;
            height: 15px;
            border: 1px solid #444444;
        }

        input[type="radio"]:checked {
            width: 15px;
            height: 15px;
            border: 1px solid #444444;
            background: #444;
        }
    </style>
    <!-- 마이페이지 시작 { -->
    <section id="sub_mypage">
        <div class="container">
            <div class="row">
                <div class="col-md-9 col-sm-12 col-xs-12 my_con"
                     style="padding-bottom: 80px;float: unset;margin: 0 auto;padding-top: 52px;">
                    <form class="mypage-class" action="/shop/stakingupdate.php" method="post">
                        <div class="mypage-class-menu">
                            <ul>
                                <li class="my_tab_btn" data-id="class"><a href="/shop/reward_log.php" class="active">Staking</a>
                                </li>
                            </ul>
                        </div>

                        <table class="table">
                            <thead>
                            <tr>
                                <th class="text-center"><!-- 목록 -->Months</th>
                                <th class="text-center"><!-- 목록 -->Discount</th>
                                <th class="text-center"><!-- 목록 -->Select</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php for ($i = 3; $i <= 12; $i += 3) { ?>
                                <tr class="item-list">
                                    <td class="w60">
                                        <div class="media">
                                            <div class="pull-left thumb" style="text-align:center;">
                                                <?php echo $i; ?> Months Lock
                                            </div>
                                        </div>
                                    </td>
                                    <td class="w60">
                                        <div class="media">
                                            <div class="pull-left thumb" style="text-align:center;">
                                                <?php echo $i / 3 * 5; ?>%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="w60">
                                        <div class="media">
                                            <div class="pull-left thumb" style="text-align:center;">
                                                <input type="radio" name="months"
                                                       value="<?php echo $i; ?>" <?php echo $i == 3 ? "checked" : ""; ?>/>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <div>MTS Wallet: 0x446e4A75BDA8F3248E603aF2d1033352c4a813f0
                            <button onclick="return copyAddress()" style="padding: 1px 2px;border: none;border-radius: 5px;background: #ff541e;color: white;">Address Copy</button>
                        </div>
                        <div>When you pay for online lectures, you can get a discount.</div>
                        <div style="margin-top: 12px; text-align: center;">
                            <input type="submit" value="Confirm" style="padding: 5px 10px;border: none;border-radius: 5px;background: #ff541e;color: white;"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <style>
        @media screen and (max-width: 768px) {
            .list-item:after {
                content: '';
                background: url('') -300px -16px;
            }
        }
    </style>
    <script>
        function copyAddress() {
            var tempElem = document.createElement('textarea');
            tempElem.value = '0x446e4A75BDA8F3248E603aF2d1033352c4a813f0';
            document.body.appendChild(tempElem);

            tempElem.select();
            document.execCommand("copy");
            document.body.removeChild(tempElem);

            alert("Address is copied to clipboard!");

            return false;
        }
    </script>

<?php
include_once("./_tail.php");
?>