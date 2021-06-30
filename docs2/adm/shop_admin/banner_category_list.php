<?php
$sub_menu = '100500';
include_once('./_common.php');

auth_check($auth[$sub_menu], "w");

$bn_id = preg_replace('/[^0-9]/', '', $bn_id);

$html_title = '강좌 리스트 배너';
$g5['title'] = $html_title.'관리';

include_once (G5_ADMIN_PATH.'/admin.head.php');
?>
<style>
.border-bottom-bold {border-bottom:5px solid #000;}
</style>
<form name="fbanner" action="./banner_category_list_update.php" method="post" enctype="multipart/form-data">

<div class="card card-inverse card-flat">

	<div class="card-block">
		<div class="row">
			<div class="col-md-12">

				<div id="bn_tab">
					<a href="/adm/shop_admin/banner_main.php"><button type="button">사이트 메인</button></a>
					<a href="/adm/shop_admin/banner_category_list.php"><button type="button" class="on">강좌 리스트</button></a>
					<a href="/adm/newwinlist.php"><button type="button">팝업 배너</button></a>
				</div>

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
		url:"/ajax/add_bn_cate_lsit.php",
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
			func: "add_bn_cate_lsit"
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