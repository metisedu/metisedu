<?php
$sub_menu = "300160";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');


$g5['title'] .= '리워드지급 수정';
include_once('./admin.head.php');



add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

$sql = "SELECT * FROM han_member WHERE mb_no = '".$mb_no."' ";
$row = sql_fetch($sql);

$point_sql = "SELECT * FROM han_point WHERE mb_id = '".$row["mb_id"]."' ORDER BY po_id DESC";
$point_res = sql_query($point_sql);
while($point_row = sql_fetch_array($point_res)){
	$point_data[] = $point_row;
}
?>

<form name="fmember" id="fmember" action="./reward_mem_form_update.php" onsubmit="return fmember_submit(this);" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="mb_no" value="<?php echo $row["mb_no"];?>">
<input type="hidden" name="mb_id" value="<?php echo $row["mb_id"];?>">
<input type="hidden" name="token" value="">


<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
		<tr>
			<th scope="row"><label for="mb_id">아이디</label></th>
			<td colspan="3">
			   <?php echo $row["mb_id"];?>
				
				
			</td>
			
		</tr>
		<tr>
			<th scope="row"><label for="mb_point">미지급합계</label></th>
			<td>
				<span id="view_mb_point">
					<?php echo number_format($row["mb_point"]);?>
				</span>
			</td>
			<th scope="row"><label for="mb_point_metis">지급합계</label></th>
			<td>
				<span id="view_mb_point_metis">
					<?php echo number_format($row["mb_point_metis"]);?>
				</span>
				
			</td>
		</tr>
		<script type="text/javascript">
		$(document).ready(function(){
			var mb_id = "<?php echo $row['mb_id']; ?>";
			
			$("#btn_add_point_metis").on("click", function(){
				var add_point = $("#add_point_metis").val(); 
				var regExp = /^[0-9]*$/;
				if(!regExp.test(add_point)) {
					alert('숫자를 입력해주세요.');
					return false;
				}
				
				if(confirm(add_point+" 코인을 지급 하시겠습니까?")){
				
					$.ajax({
						type: "POST",
						dataType: "JSON",
						url	 : "./reward_mem_ajax.php",
						cache : false,
						async : false,
						data:{
						"mb_id"	:	mb_id,
						"mode"	:	"add",
						"point"	:	add_point
						},
						error : function(e){
							console.log(e);
						},
						success : function(data){
							console.log(data);
							$("#view_mb_point").text(number_format(data.point));
							$("#view_mb_point_metis").text(number_format(data.point_metis));
							$("#his_log").prepend("<li>"+data.his_log+"</li>");

						}
					});
				}
			});

			// 전체 클릭시
			$("#btn_add_all_point_metis").on("click", function(){
				$.ajax({
					type: "POST",
					dataType: "JSON",
					url	 : "./reward_mem_ajax.php",
					cache : false,
					async : false,
					data:{
					"mb_id"	:	mb_id,
					"mode"	:	"all"
					},
					error : function(e){
						console.log(e);
					},
					success : function(data){
						//console.log(data.mem_point);
						$("#add_point_metis").val(data.mem_point);
					}
				});
			});
		});

		</script>
		<tr>
			<th scope="row"><label for="mb_id">지급하기</label></th>
			<td colspan="3">
				<input type="text" name="add_point_metis" id="add_point_metis" class="frm_input" value="">
				<button type="button" id="btn_add_all_point_metis" class="btn">전체</button>
				<button type="button" id="btn_add_point_metis" class="btn">지급하기</button>
			</td>
			
		</tr>

		<tr>
			<th scope="row"><label for="history">히스토리</label></th>
			<td colspan="3">
				<ul id="his_log" style="max-height:200px; overflow:auto;">
				<?php 
				for($i=0; $i<count($point_data); $i++){
					$point_history = "";
					$point_history .= $point_data[$i]["po_datetime"];
					$point_history .= " : ";
					$point_history .= $point_data[$i]["po_content"];

					echo "<li>";
					echo $point_history;
					echo "</li>";
				}
				?>
				</ul>
			</td>
		</tr>
		
		<tr>
			<th scope="row"><label for="mb_memo_point">메모</label></th>
			<td colspan="3">
				<textarea name="mb_memo_point" id="mb_memo_point"><?php echo $row["mb_memo_point"]; ?></textarea>
			</td>
		</tr>

    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <a href="./account_list.php?<?php echo $qstr ?>" class="btn btn_02">목록</a>
    <input type="submit" value="확인" class="btn_submit btn" accesskey='s'>
</div>
</form>

<script>
function fmember_submit(f)
{
    if (!f.mb_icon.value.match(/\.(gif|jpe?g|png)$/i) && f.mb_icon.value) {
        alert('아이콘은 이미지 파일만 가능합니다.');
        return false;
    }

    if (!f.mb_img.value.match(/\.(gif|jpe?g|png)$/i) && f.mb_img.value) {
        alert('회원이미지는 이미지 파일만 가능합니다.');
        return false;
    }

    return true;
}
</script>
<?php

include_once('./admin.tail.php');
?>
