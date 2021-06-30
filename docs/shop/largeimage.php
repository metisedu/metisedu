<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/largeimage.php');
    return;
}

$it_id = get_search_string(trim($_GET['it_id']));
$no = (isset($_GET['no']) && $_GET['no']) ? (int) $_GET['no'] : 1;

$row = get_shop_item($it_id, true);

if(!$row['it_id'])
    alert_close('상품정보가 존재하지 않습니다.');

$imagefile = G5_DATA_PATH.'/item/'.$row['it_img'.$no];
$imagefileurl = run_replace('get_item_image_url', G5_DATA_URL.'/item/'.$row['it_img'.$no], $row, $no);
$size = file_exists($imagefile) ? @getimagesize($imagefile) : array();

$g5['title'] = "{$row['it_name']} ($it_id)";
include_once(G5_PATH.'/head.sub.php');

$skin = G5_SHOP_SKIN_PATH.'/largeimage.skin.php';

if(is_file($skin))
    include_once($skin);
else
    echo '<p>'.str_replace(G5_PATH.'/', '', $skin).'파일이 존재하지 않습니다.</p>';

include_once(G5_PATH.'/tail.sub.php');
?>