<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/mailer.lib.php');

if ($w == "a") {
    sql_query("UPDATE han_staking SET hs_startdate = '" . date("Y-m-d") . "', hs_enddate = '" . date("Y-m-d", strtotime("+" . ($month*30) . " day")) . "' WHERE hs_id = " . $hs_id);
} else if($w == "d") {
    sql_query("UPDATE han_staking SET hs_startdate = null, hs_enddate = null WHERE hs_id = '" . $hs_id . "' ");
}

alert('적용되었습니다!');