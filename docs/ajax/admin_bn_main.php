<?php
include_once("./_common.php");

$bn_position = (isset($_POST['bn_position']) && in_array($_POST['bn_position'], array('메인', '왼쪽'))) ? $_POST['bn_position'] : '';

$where = ' where ';
$sql_search = '';

if ( $bn_position ){
    $sql_search .= " $where bn_position = '$bn_position' ";
    $where = ' and ';
    $qstr .= "&amp;bn_position=$bn_position";
}

$sql_common = " from {$g5['g5_shop_banner_table']} ";
$sql_common .= $sql_search;

$sql = " select * from {$g5['g5_shop_banner_table']} $sql_search
	  order by bn_order, bn_id desc
	  ";
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++) {
	// 테두리 있는지
	$bn_border  = $row['bn_border'];
	// 새창 띄우기인지
	$bn_new_win = ($row['bn_new_win']) ? 'target="_blank"' : '';

	$bimg = G5_DATA_PATH.'/banner/'.$row['bn_id'];
	if(file_exists($bimg)) {
		$size = @getimagesize($bimg);
		if($size[0] && $size[0] > 800)
			$width = 800;
		else
			$width = $size[0];

		$bn_img = "";

		$bn_img .= '<img src="'.G5_DATA_URL.'/banner/'.$row['bn_id'].'" width="'.$width.'" alt="'.get_text($row['bn_alt']).'">';
	}

	switch($row['bn_device']) {
		case 'pc':
			$bn_device = 'PC';
			break;
		case 'mobile':
			$bn_device = '모바일';
			break;
		default:
			$bn_device = 'PC와 모바일';
			break;
	}

	$bn_begin_time = substr($row['bn_begin_time'], 2, 14);
	$bn_end_time   = substr($row['bn_end_time'], 2, 14);
?>

<tr>
	<td>
		<input type="hidden" name="bn_id[]" value="<?php echo $row['bn_id']; ?>" />
		<input type="hidden" name="bn_position[]" value="메인" />
		<input type="text" name="bn_alt[]" value="<?php echo get_text($row['bn_alt']); ?>" id="bn_alt" class="frm_input" size="50">
	</td>
	<td>
		<input type="file" name="bn_bimg[<?php echo $i; ?>]">
		<?php
		$bimg_str = "";
		$bimg = G5_DATA_PATH."/banner/{$row['bn_id']}";
		if (file_exists($bimg) && $row['bn_id']) {
			$size = @getimagesize($bimg);
			if($size[0] && $size[0] > 250)
				$width = 250;
			else
				$width = $size[0];

			echo '<input type="checkbox" name="bn_bimg_del['.$i.']" value="1" id="bn_bimg_del"> <label for="bn_bimg_del">'.get_text($row['bn_alt']).' 삭제</label>';
			$bimg_str = '<img src="'.G5_DATA_URL.'/banner/'.$row['bn_id'].'" width="'.$width.'">';
		}
		if ($bimg_str) {
			echo '<div class="banner_or_img">';
			echo $bimg_str;
			echo '</div>';
		}
		?>
		<span style="font-size: 10px;color: #3c3c3c;">PC 기준 사이즈 : 1920px X 563px<br>Mo 기준 사이즈 : 640px X 563px</span>
	</td>
	<td>
		<input type="text" name="bn_url[]" size="50" value="<?php echo $row['bn_url']; ?>" id="bn_url" class="frm_input">
	</td>
	<td>
		<select name="bn_device[]">
			<option value="both"<?php echo get_selected($row['bn_device'], 'both', true); ?>>PC와 모바일</option>
			<option value="pc"<?php echo get_selected($row['bn_device'], 'pc'); ?>>PC</option>
			<option value="mobile"<?php echo get_selected($row['bn_device'], 'mobile'); ?>>모바일</option>
		</select>
	</td>
	<td>
		<select name="bn_use[]">
			<option value="1" <?php echo get_selected($row['bn_use'], 1); ?>>노출</option>
			<option value="0" <?php echo get_selected($row['bn_use'], 0); ?>>비노출</option>
		</select>
	</td>
	<td>
		<input type="text" name="bn_order[]" value="<?php echo $row['bn_order']; ?>" class="frm_input" size="10" />
	</td>
	<td>
		<select name="bn_new_win[]">
			<option value="0" <?php echo get_selected($row['bn_new_win'], 0); ?>>사용안함</option>
			<option value="1" <?php echo get_selected($row['bn_new_win'], 1); ?>>사용</option>
		</select>
	</td>
	<td><a href="./bannerformupdate.php?w=d&amp;bn_id=<?php echo $row['bn_id']; ?>" onclick="return delete_confirm(this);" class="btn btn_02">삭제</a></td>
</tr>

<?php
}
if ($i == 0) {
echo '<tr><td colspan="7" class="empty_table">자료가 없습니다.</td></tr>';
}
?>