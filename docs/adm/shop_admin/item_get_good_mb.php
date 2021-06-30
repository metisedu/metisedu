<?php
$sub_menu = '400300';
include_once('./_common.php');

auth_check($auth[$sub_menu], "w");

$mb_id = trim($mb_id);

if(!$mb_id)
    die('<p>아이디를 입력해주세요.</p>');

$sql = "SELECT * FROM han_member WHERE mb_id LIKE '%".$mb_id."%'";
$result = sql_query($sql);

$list = '';

for($i=0;$row=sql_fetch_array($result);$i++) {
    $list .= '<li class="list_res">';
    $list .= '<input type="hidden" name="good_id_list[]" value="'.$row['mb_id'].'">';
    $list .= '<div class="list_item">'.$row["mb_id"].'</div>';
    $list .= '<div class="list_item_btn"><button type="button" class="add_item_2 btn_frmline">추가</button></div>';
    $list .= '</li>'.PHP_EOL;
}

if($list)
    $list = '<ul>'.$list.'</ul>';
else
    $list = '<p>등록된 상품이 없습니다.';

echo $list;
?>