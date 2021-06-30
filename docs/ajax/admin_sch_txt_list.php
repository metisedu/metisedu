<?php
include_once("./_common.php");

$sQuery = " SELECT *
			FROM han_shop_item
			WHERE it_id = '".$it_id."'
			";
$it = sql_fetch($sQuery);

$keyword = explode("||", $it['it_keyword']);
if(is_array($keyword) && $it['it_keyword']){
	for($i = 0; $i < count($keyword); $i++){
		if(!$keyword[$i])
			continue;

		echo"<li>#".$keyword[$i]." <span data-id='".$keyword[$i]."'><i class='fa fa-close'></i></span></li>";
	}
}else{
	echo "<li class='empty_sch'>등록된 검색어가 없습니다.</li>";
}
?>