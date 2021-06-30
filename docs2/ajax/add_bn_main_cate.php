<?php
include_once("./_common.php");

$ca_position = '메인';

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
	  order by ca_item_order, ca_no desc
	  ";
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++) {
?>
	<tr>
		<td colspan="5">
			<input type="hidden" name="ca_no[]" value="<?php echo $row['ca_no']; ?>" />
			<input type="hidden" name="ca_position[]" value="메인" />
			<input type="text" name="ca_title[]" value="<?php echo get_text($row['ca_title']); ?>" class="frm_input" placeholder="카테고리 타이틀" size="80" />
		</td>
	</tr>
	<tr>
		<td colspan="5"><input type="text" name="ca_s_title[]" value="<?php echo get_text($row['ca_s_title']); ?>" class="frm_input" placeholder="카테고리 서브 타이틀" size="80" /></td>
	</tr>
	<tr>
		<td colspan="5"><input type="text" name="ca_url[]" value="<?php echo $row['ca_url']; ?>" class="frm_input" placeholder="더보기 링크" size="80" /></td>
	</tr>
	<tr>
		<th>노출1 : 판매용 강의 코드</th>
		<th>노출2 : 판매용 강의 코드</th>
		<th>노출3 : 판매용 강의 코드</th>
		<th>노출4 : 판매용 강의 코드</th>
		<th>카테고리 순서</th>
	</tr>
	<tr class="border-bottom-bold">
		<td><input type="text" name="ca_item_code1[]" value="<?php echo $row['ca_item_code1']; ?>" class="frm_input" /></td>
		<td><input type="text" name="ca_item_code2[]" value="<?php echo $row['ca_item_code2']; ?>" class="frm_input" /></td>
		<td><input type="text" name="ca_item_code3[]" value="<?php echo $row['ca_item_code3']; ?>" class="frm_input" /></td>
		<td><input type="text" name="ca_item_code4[]" value="<?php echo $row['ca_item_code4']; ?>" class="frm_input" /></td>
		<td><input type="text" name="ca_item_order[]" value="<?php echo $row['ca_item_order']; ?>" class="frm_input" /></td>
	</tr>
<?php
}
if ($i == 0) {
echo '<tr><td colspan="5" class="empty_table">자료가 없습니다.</td></tr>';
}
?>