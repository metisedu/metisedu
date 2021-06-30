<?php
$sub_menu = '100500';
include_once('./_common.php');

auth_check($auth[$sub_menu], "w");

$bn_id = preg_replace('/[^0-9]/', '', $bn_id);

$html_title = '메인카테고리';
$g5['title'] = $html_title.'관리';

// 접속기기 필드 추가
if(!sql_query(" select bn_device from {$g5['g5_shop_banner_table']} limit 0, 1 ")) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_banner_table']}`
                    ADD `bn_device` varchar(10) not null default '' AFTER `bn_url` ", true);
    sql_query(" update {$g5['g5_shop_banner_table']} set bn_device = 'pc' ", true);
}

include_once (G5_ADMIN_PATH.'/admin.head.php');
?>
<style>
.border-bottom-bold {border-bottom:5px solid #000;}
</style>
<form name="fbanner" action="./banner_cate_formupdate.php" method="post" enctype="multipart/form-data">

<div class="card card-inverse card-flat">

	<div class="card-block">
		<div class="row">
			<div class="col-md-12">

				<?php include_once('./banner_tab.php'); ?>

				<div class="tbl_frm01 tbl_wrap table-responsive">
					<table class="table">
					<caption><?php echo $g5['title']; ?></caption>
					<colgroup>
						<col class="grid_4">
						<col>
					</colgroup>
					<tbody id="bn_main">
					</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="btn_fixed_top">
    <!--<a href="./bannerlist.php" class="btn_02 btn">목록</a>-->
    <input type="submit" value="저장" class="btn_submit btn" accesskey="s">
	<input type="button" value="카테고리 추가" class="btn_03 btn" id="add_bn_main_cate" accesskey="s">
</div>

</form>

<script>
function get_bn_main_list(){
	$.ajax({
		type:"post",
		url:"/ajax/add_bn_main_cate.php",
		data: {
			bn_position: "메인"
		},
		success:function(data){
			$("#bn_main").html(data);
		}
	});
}

$("#add_bn_main_cate").click(function(){
	$.ajax({
		type:"post",
		url:"/ajax/admin_func.php",
		data: {
			func: "add_bn_main_cate"
		},
		success:function(data){
			$("#bn_main").append(data);
		}
	});
});

$(window).load(function(){
	get_bn_main_list();
});
</script>
<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>