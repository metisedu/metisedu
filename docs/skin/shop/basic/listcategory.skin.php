<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$str = '';
$exists = false;

$ca_id_slt = $ca_id;

//$ca_id = substr($ca_id,0,4);
$ca_id_len = strlen($ca_id);
$len2 = $ca_id_len + 2;
$len4 = $ca_id_len + 4;

$lt_menu = sql_fetch("SELECT ca_name FROM {$g5['g5_shop_category_table']} WHERE ca_id = '".$ca_id."' ");

$sql = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where ca_id like '$ca_id%' and length(ca_id) = $ca_id_len and ca_use = '1' order by ca_order, ca_id ";
$result = sql_query($sql);
while ($row=sql_fetch_array($result)) {

    $row2 = sql_fetch(" select count(*) as cnt from {$g5['g5_shop_item_table']} where (ca_id like '{$row['ca_id']}%' or ca_id2 like '{$row['ca_id']}%' or ca_id3 like '{$row['ca_id']}%') and it_use = '1'  ");
	$a_class = "";
	if($row['ca_id'] == $ca_id_slt)
		$a_class = "active";

    $str .= '<li><a href="'.shop_category_url($row['ca_id']).'" class="'.$a_class.'">'.$row['ca_name'].'</a></li>'; // ('.$row2['cnt'].')
    $exists = true;
}

if ($exists) {
	$active = "active";
    // add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
    add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_SKIN_URL.'/style.css">', 0);
?>

<!-- 상품분류 1 시작 { -->
<div class="box_style_2 .col-sm-12">
	<h4><a href="#" class="box_cate_tit"><?php echo $lt_menu['ca_name']; ?></a></h4>
	<div class="box_category">
		<ul class="submenu-col">
			<?php echo $str; ?>
		</ul>
	</div>
</div>

<!--<aside id="sct_ct_1" class="sct_ct">
    <h2>현재 상품 분류와 관련된 분류</h2>
    <ul>
        <?php echo $str; ?>
    </ul>
</aside>-->
<!-- } 상품분류 1 끝 -->

<?php } ?>