<?php
include_once('./_common.php');

$sQuery = " SELECT MAX(cp_order) + 1 AS cp_order
			FROM han_shop_chapter
			WHERE lt_id = '".$lt_id."'
			";
$rst = sql_fetch($sQuery);
$cp_order = $rst['cp_order'];

$sQuery = " INSERT INTO han_shop_chapter
				SET lt_id = '".$lt_id."'
				,	cp_name = '".$cp_name."'
				,	cp_order = '".$cp_order."'
				,	cp_time = '".date("Y-m-d H:i:s")."'
				";
$rst = sql_query($sQuery);

if($rst){
	$cp_id = sql_insert_id();

	$sQuery = " SELECT *
				FROM han_shop_chapter
				WHERE cp_id = '".$cp_id."'
				";
	$rst = sql_fetch($sQuery);
?>
<div class="mv_option_addfrm" id="mv_option_addfrm">
	<div class="sit_option_frm_wrapper">
		<div style="padding:5px;line-height:30px;background:#8197ff !important;color:#fff;">
			<input type="hidden" name="cp_id[]" value="<?php echo $rst['cp_id']; ?>">
			<input type="hidden" name="cp_file_name[]" value="<?php echo $rst['cp_file']; ?>">
			<button type="button" class="suc_btn" onclick="del_chapter('<?php echo $rst['cp_id']; ?>');">챕터 삭제</button>
			<b>강의 Section 입력</b> : <input type="text" name="cp_name[]" value="<?php echo $rst['cp_name']; ?>" class="frm_input" size="80" placeholder="강의 센션명을 적어주세요." />&nbsp;&nbsp;&nbsp;
			<b>Section 순서</b> : <input type="text" name="cp_order[]" value="<?php echo $rst['cp_order']; ?>" class="frm_input" />&nbsp;&nbsp;&nbsp;
			<b>Section 이미지</b> : <input type="file" name="cp_file[]" class="frm_input" />
			크기 : 167 x 167
		</div>
		<table>
		<caption>추가옵션 목록</caption>
		<thead>
		<tr>
			<th scope="col">
				<label for="mv_chk_all" class="sound_only">전체 추가옵션</label>
				<input type="checkbox" name="mv_chk_all" value="1">
			</th>
			<th scope="col">제목</th>
			<th scope="col">영상/미디어 컨텐츠 키</th>
			<th scope="col">미리보기 URL</th>
			<th scope="col">진열순서</th>
			<th scope="col">강의 관련자료</th>
			<th scope="col">노출여부</th>
			<th scope="col" style="display:none;">완료 / 수정</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td class="td_chk">
				<input type="hidden" name="mv_cp_id[]" value="<?php echo $rst['cp_id']; ?>">
				<input type="hidden" name="mv_file_name[]" value="">
				<input type="checkbox" name="mv_chk[]" value="1">
			</td>
			<td class="spl-subject-cell" style="text-align:center;">
				<input type="text" name="mv_name[]" value="" class="frm_input" size="50" style="margin-bottom: 5px;" /><br>
			</td>
			<td class="spl-cell" style="text-align:center;"><input type="text" name="mv_url[]" value="" class="frm_input" size="40" /></td>
			<td class="spl-cell" style="text-align:center;"><input type="text" name="mv_preview[]" value="" class="frm_input" size="40" /></td>
			<td class="spl-cell" style="text-align:center;"><input type="text" name="mv_order[]" value="" class="frm_input" size="7" /></td>
			<td class="td_numsmall" style="text-align:center;">
				<input type="file" name="mv_file[]" value="" id="mv_file_0" class="frm_input" style="border:0px;" size="9">
			</td>
			<td class="td_mng">
				<select name="mv_use[]" id="mv_use_">
					<option value="1">사용함</option>
					<option value="0">사용안함</option>
				</select>
			</td>
			<td class="td_mng" style="display:none;">
				<button type="button">완료</button>
				<button type="button">수정</button>
			</td>
		</tr>
		</tbody>
		</table>
	</div>

	<div class="btn_list01 btn_list">
		<button type="button" class="btn btn_02 sel_move_delete">선택삭제</button>
		<button type="button" class="btn btn_01 spl_move_apply" data-id="<?php echo $rst['cp_id']; ?>">영상추가</button>
	</div>
</div>
<?php
}else{
	echo"01";
}
?>
