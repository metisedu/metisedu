<?php
include_once("./_common.php");

$sQuery = " SELECT *
			FROM han_shop_chapter
			WHERE lt_id = '".$lt_id."'
			ORDER BY (cp_order + 0) ASC
			";
$sqla = sql_query($sQuery);

for($k = 0; $rst = sql_fetch_array($sqla); $k++){
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
			<?php
				$thumb = get_mv_thumbnail($rst['cp_file'], 35, 35);
				if($thumb){
					echo $thumb;
				}else{
					echo"크기 : 167 x 167";
				}
				//echo $rst['cp_file']
			?>
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
		<?php
			$sQuery = " SELECT *
						FROM han_shop_movie
						WHERE lt_id = '".$lt_id."'
						AND   cp_id = '".$rst['cp_id']."'
						ORDER BY (mv_order + 0) ASC ";
			$sql = sql_query($sQuery);

			$mv = array();
			for($i = 0; $row = sql_fetch_array($sql); $i++){
				$mv[] = $row;
			}

			if(count($mv) > 0){
				$t_mv_list = count($mv);
			}else{
				$t_mv_list = 1;
			}

			for($i = 0; $i < $t_mv_list; $i++){
		?>
			<tr>
				<td class="td_chk">
					<input type="hidden" name="mv_cp_id[]" value="<?php echo $rst['cp_id']; ?>">
					<input type="hidden" name="mv_file_name[]" value="<?php echo $mv[$i]['mv_file']; ?>">
					<input type="checkbox" name="mv_chk[]" value="1">
				</td>
				<td class="spl-subject-cell" style="text-align:center;">
					<input type="text" name="mv_name[]" value="<?php echo $mv[$i]['mv_name']; ?>" class="frm_input" size="50" style="margin-bottom: 5px;" /><br>
				</td>
				<td class="spl-cell" style="text-align:center;"><input type="text" name="mv_url[]" value="<?php echo $mv[$i]['mv_url']; ?>" class="frm_input" size="40" /></td>
				<td class="spl-cell" style="text-align:center;"><input type="text" name="mv_preview[]" value="<?php echo $mv[$i]['mv_preview']; ?>" class="frm_input" size="40" /></td>
				<td class="spl-cell" style="text-align:center;"><input type="text" name="mv_order[]" value="<?php echo $mv[$i]['mv_order']; ?>" class="frm_input" size="7" /></td>
				<td class="td_numsmall" style="text-align:center;">
					<input type="file" name="mv_file[]" value="" id="mv_file_0" class="frm_input" style="border:0px;" size="9">
					<?php
						$thumb = get_it_thumbnail($mv[$i]['mv_file'], 35, 35);
						echo $thumb;
						echo "파일명: ". $mv[$i]['mv_file_name']
					?>
				</td>
				<td class="td_mng">
					<select name="mv_use[]" id="mv_use_<?php echo $i; ?>">
						<option value="1" <?php if($mv[$i]['mv_use'] == 1) echo"selected"; ?>>사용함</option>
						<option value="0" <?php if($mv[$i]['mv_use'] == 0) echo"selected"; ?>>사용안함</option>
					</select>
				</td>
				<td class="td_mng" style="display:none;">
					<button type="button">완료</button>
					<button type="button">수정</button>
				</td>
			</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>

	<div class="btn_list01 btn_list">
		<button type="button" class="btn btn_02 sel_move_delete">선택삭제</button>
		<button type="button" class="btn btn_01 spl_move_apply" data-id="<?php echo $rst['cp_id']; ?>">영상추가</button>
	</div>
</div>
<?php }
if($k == 0){
	echo"<div class='empty_sec'>등록된 섹션이 없습니다.</div>";
}
?>
