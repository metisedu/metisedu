<?php
include_once('./_common.php');

$sQuery = " SELECT *
			FROM han_shop_item_list
			WHERE it_id = '".$it_id."'
			ORDER BY ir_order ASC, ir_no ASC
			";
//echo"<p>".$sQuery;
$sql_a = sql_query($sQuery);

for($k = 0; $rst = sql_fetch_array($sql_a); $k++){
	$sQuery = " SELECT *
				FROM han_shop_chapter
				WHERE lt_id = '".$rst['it_id2']."'
				ORDER BY (cp_order + 0) ASC
				";
	//echo"<p>".$sQuery;
	$sql = sql_query($sQuery);

	if($k == 0)
		echo"<ul>";
	else if($k > 0)
		echo"</ul><ul>";

	for($i = 0; $row = sql_fetch_array($sql); $i++){
		$sQuery = " SELECT *
					FROM han_shop_movie
					WHERE lt_id = '".$rst['it_id2']."'
					AND   cp_id = '".$row['cp_id']."'
					AND   mv_use = '1'
					ORDER BY (mv_order + 0) ASC
					";
		//echo"<p>".$sQuery;
		$sql_b = sql_query($sQuery);

		$lec = sql_fetch("SELECT * FROM han_shop_lec WHERE lt_id = '".$rst['it_id2']."' ");
		if($i == 0){
			echo"<li class='course_list_title' id='".$rst['it_id2']."'>".sprintf("%02d", ($i+1)).". ".stripslashes($lec['it_name'])."</li>";
		}
?>
	<li class="chapter_list" id="<?php echo $row['cp_id']; ?>">
		<a href="javascript:void(0);" class="dep1 <?php echo $ac?>">
		<span>CLASS <?php echo sprintf("%02d", ($i+1)); ?></span>
		<?php echo $row['cp_name']; ?>
		<i class="fa fa-angle-<?php echo $aa?>"></i>
		</a>
		<ul class="dep2 <?php echo $ac?> mv_list">
			<?php
				for($j = 0; $mv = sql_fetch_array($sql_b); $j++){
					$mv_status = get_mv_status($mv['mv_id'], $member['mb_id']);
					if($mv_status == "수강중") $icon = "ing";
					else if($mv_status == "수강완료") $icon = "done";
					else $icon = "";

					if(is_mobile())
						$k_mv = "#kollus_mv";
					else
						$k_mv = "javascript:void(0);";
			?>
			<li class="mv_url" data-it="<?php echo $it_id; ?>" data-cp="<?php echo $row['cp_id']; ?>" data-mv="<?php echo $mv['mv_id']; ?>" data-url="<?php echo $mv['mv_url']; ?>">
				<a href="<?php echo $k_mv; ?>" class="<?php echo $ac?>"><?php echo $mv['mv_name']; ?>
					<span class="<?php echo $cl?> <?php echo $icon; ?>"><?php echo $mv['mv_time']; ?> <?php echo $mv_status; ?></span>
				</a>
			</li>
			<?php
				}
				if($j == 0){
					echo"<li class='mv_url' data-url=''><b style='padding: 10px;display: block;'>강의가 곧 오픈됩니다</b></li>";
				}
			?>
		</ul>
	</li>
<?php
	}
	if($i == 0){
		echo"<li style='padding:10px;'>등록된 챕터가 없습니다.</li></ul>";
	}
}
?>
</ul>
<script>
	/*
	$(".chapter_list").click(function(){
		if($(this).hasClass("on")){
			return false;
		}

		var i = $(".chapter_list").index($(this));

		if( $(".mv_list").eq(i).css("display") == "none"){
			$(".mv_list").hide();
			$(".mv_list").eq(i).show();
			$(this).addClass("on");
		}else{
			$(".mv_list").hide();
			$(this).removeClass("on");
		}
	});
	*/
	$(".dep1").click(function(){
		if( $(this).next("ul").css("display") == "none"){
			$(".mv_list").hide();
			$(this).next("ul").show();
			$(this).parent("li.chapter_list").addClass("on");
		}else{
			$(".mv_list").hide();
			$(this).parent("li.chapter_list").removeClass("on");
		}
	});
</script>