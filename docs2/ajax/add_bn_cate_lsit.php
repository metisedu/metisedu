<?php
include_once("./_common.php");

// 분류
$ca_list  = '<option value="">선택</option>'.PHP_EOL;
$sql = " select * from {$g5['g5_shop_category_table']} ";
if ($is_admin != 'super')
    $sql .= " where ca_mb_id = '{$member['mb_id']}' ";
$sql .= " order by ca_order, ca_id ";
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++)
{
    $len = strlen($row['ca_id']) / 2 - 1;
    $nbsp = '';
    for ($i=0; $i<$len; $i++) {
        $nbsp .= '&nbsp;&nbsp;&nbsp;';
    }
    $ca_list .= '<option value="'.$row['ca_id'].'">'.$nbsp.$row['ca_name'].'</option>'.PHP_EOL;
}

$ca_position = '서브';

$where = ' where ';
$sql_search = '';

if ( $ca_position ){
    $sql_search .= " $where ca_position = '$ca_position' ";
    $where = ' and ';
    $qstr .= "&amp;ca_position=$ca_position";
}

$sql_common = " from han_shop_banner_cate ";
$sql_common .= $sql_search;

$sql = " select * from han_shop_banner_cate $sql_search
	  order by ca_item_order, ca_no asc
	  ";
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++) {
?>
	<tr>
		<td colspan="4">
			<select name="ca_id[<?php echo $i; ?>]" id="ca_id_<?php echo $i; ?>">
				<?php echo conv_selected_option($ca_list, $row['ca_id']); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<input type="hidden" name="ca_no[]" value="<?php echo $row['ca_no']; ?>" />
			<input type="hidden" name="ca_position[]" value="서브" />
			<input type="text" name="ca_title[]" value="<?php echo get_text($row['ca_title']); ?>" class="frm_input" placeholder="카테고리 타이틀" size="80" />
			<input type="text" name="ca_color[]" value="<?php echo $row['ca_color']; ?>" class="frm_input" placeholder="색상코드 예)#ff6400" size="20" />
		</td>
	</tr>
	<tr>
		<td colspan="4"><input type="text" name="ca_s_title[]" value="<?php echo get_text($row['ca_s_title']); ?>" class="frm_input" placeholder="카테고리 서브 타이틀" size="80" /></td>
	</tr>
	<tr>
		<th>노출1 : 판매용 강의 코드</th>
		<th>노출2 : 판매용 강의 코드</th>
		<th>노출3 : 판매용 강의 코드</th>
		<th>노출4 : 판매용 강의 코드</th>
	</tr>
	<tr>
		<td><input type="text" name="ca_item_code1[]" value="<?php echo $row['ca_item_code1']; ?>" class="frm_input" /></td>
		<td><input type="text" name="ca_item_code2[]" value="<?php echo $row['ca_item_code2']; ?>" class="frm_input" /></td>
		<td><input type="text" name="ca_item_code3[]" value="<?php echo $row['ca_item_code3']; ?>" class="frm_input" /></td>
		<td><input type="text" name="ca_item_code4[]" value="<?php echo $row['ca_item_code4']; ?>" class="frm_input" /></td>
	</tr>
	<tr>
		<th>노출5 : 판매용 강의 코드</th>
		<th>노출6 : 판매용 강의 코드</th>
		<th>노출7 : 판매용 강의 코드</th>
		<th>노출8 : 판매용 강의 코드</th>
	</tr>
	<tr class="border-bottom-bold">
		<td><input type="text" name="ca_item_code5[]" value="<?php echo $row['ca_item_code5']; ?>" class="frm_input" /></td>
		<td><input type="text" name="ca_item_code6[]" value="<?php echo $row['ca_item_code6']; ?>" class="frm_input" /></td>
		<td><input type="text" name="ca_item_code7[]" value="<?php echo $row['ca_item_code7']; ?>" class="frm_input" /></td>
		<td><input type="text" name="ca_item_code8[]" value="<?php echo $row['ca_item_code8']; ?>" class="frm_input" /></td>
	</tr>
<?php
}
if ($i == 0) {
echo '<tr><td colspan="5" class="empty_table">자료가 없습니다.</td></tr>';
}
?>