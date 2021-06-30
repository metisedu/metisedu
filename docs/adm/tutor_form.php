<?php
$sub_menu = "300150";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');


$g5['title'] .= '정산자료수정';
include_once('./admin.head.php');



add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$sql = "SELECT * FROM han_write_tutor WHERE wr_id = '".$wr_id."'";
$row = sql_fetch($sql);
?>

<form name="fmember" id="fmember" action="./tutor_form_update.php" onsubmit="return fmember_submit(this);" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="wr_id" value="<?php echo $wr_id;?>">
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
			<th scope="row"><label for="wr_datetime">등록자</label></th>
			<td>
			   <?php echo $row["mb_id"];?>
				
				
			</td>
			<th scope="row"><label for="wr_datetime">등록일</label></th>
			<td>
			   <?php echo $row["wr_datetime"];?>
				
				
			</td>
			
		</tr>
		<tr>
			<th scope="row"><label for="wr_subject">제목</label></th>
			<td colspan="3">
				<input type="text" name="wr_subject" id="wr_subject" class="frm_input frm_input_full" value="<?php echo $row["wr_subject"];?>">
			</td>
		</tr>	
		<tr>
			<th scope="row"><label for="wr_content">교육서비스 정보</label></th>
			<td colspan="3">
				<textarea name="wr_content" id="wr_content"><?php echo $row["wr_content"];?></textarea>
			</td>
		</tr>	
		<tr>
			<th scope="row"><label for="wr_1">이력정보</label></th>
			<td colspan="3">
				<textarea name="wr_1" id="wr_1"><?php echo $row["wr_1"];?></textarea>
			</td>
		</tr>	

		<tr>
			<th scope="row"><label for="wr_good">좋아요</label></th>
			<td>
				<input type="text" name="wr_good" id="wr_good" class="frm_input" value="<?php echo $row["wr_good"];?>">
				
				
			</td>
			<th scope="row"><label for="wr_nogood">싫어요</label></th>
			<td>
				<input type="text" name="wr_nogood" id="wr_nogood" class="frm_input" value="<?php echo $row["wr_nogood"];?>">
				
				
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="wr_2">투표 마감일</label></th>
			<td colspan="3">
				<input type="text" name="wr_2" id="wr_2" class="frm_input" value="<?php echo $row["wr_2"];?>">
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="wr_link1">영상링크</label></th>
			<td colspan="3">
				<input type="text" name="wr_link1" id="wr_link1" class="frm_input frm_input_full" value="<?php echo $row["wr_link1"];?>">
			</td>
		</tr>	

		<tr>
			<th scope="row">공개 여부</th>
			<td colspan="3">
				<label for="wr_3_on">공개
					<input type="radio" name="wr_3" id="wr_3_on" value="Y" <?php echo ($row["wr_3"]=="Y")? "checked":"";?>>
				</label>
				<label for="wr_3_off">비공개
				<input type="radio" name="wr_3" id="wr_3_off" value="N" <?php echo ($row["wr_3"]=="N")? "checked":"";?>>
				</label>
			</td>
		</tr>	



    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <a href="./tutor_list.php?<?php echo $qstr ?>" class="btn btn_02">목록</a>
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
$(document).ready(function(){

	$("#wr_2").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "yy-mm-dd",
		showButtonPanel: true,
		yearRange: "c-99:c+99",
		timeFormat:'HH:mm:ss'
	});
});
</script>
<?php

include_once('./admin.tail.php');
?>
