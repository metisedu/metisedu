<?php
function getVimeoData($a) {
    $b = file_get_contents("http://vimeo.com/api/v2/video/".$a.".json");
    $c = json_decode($b);
    return $c[0];
}
$a = $_REQUEST["wr_link1"];
$b = explode("/", $a);
$c = $b[3];
$d = getVimeoData($c);
$wr_4 = $d->thumbnail_large;

// tutor 게시판 관리자가아닌 게시판 수정에서 들어오는 게시 여부 값은 기존 db 값 가져와서 그대로 넣어주기.
$wr_3_sql = "SELECT wr_3 FROM han_write_tutor WHERE wr_id = '".$wr_id."'";
$wr_3_row = sql_fetch($wr_3_sql);
$wr_3 = ($wr_3_row["wr_3"] == "")? "N":$wr_3_row["wr_3"];

?>