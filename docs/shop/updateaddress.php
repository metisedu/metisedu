<?php
include_once('./_common.php');
define('_NOHEADER_',true);

$sQuery = " UPDATE han_member
			SET mb_address = '".$_POST["address"]."'
			WHERE mb_id = '".$member['mb_id']."'
			";
$rst = sql_fetch($sQuery);
echo json_encode($_POST);
?>
