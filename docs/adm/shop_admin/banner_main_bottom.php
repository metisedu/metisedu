<?php
$sub_menu = '100500';
include_once('./_common.php');

auth_check($auth[$sub_menu], "w");

$html_title = '메인카테고리';
$g5['title'] = $html_title.'관리';

$sQuery = " SELECT *
			FROM han_shop_banner_etc
			";
$row = sql_fetch($sQuery);

include_once (G5_ADMIN_PATH.'/admin.head.php');
?>
<style>
.border-bottom-bold {border-bottom:5px solid #000;}
</style>
<form name="fbottom" action="./banner_bottom_formupdate.php" method="post" enctype="multipart/form-data">

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
					<tbody>
						<tr>
							<td colspan="6">
								<input type="text" name="bt_title" value="<?php echo get_text($row['bt_title']); ?>" class="frm_input" placeholder="수강후기 타이틀" size="80" />
							</td>
						</tr>
						<tr>
							<th>배너명</th>
							<th>배너 이미지</th>
							<th>URL</th>
						</tr>
						<tr>
							<td><input type="text" name="bt_alt1" value="<?php echo get_text($row['bt_alt1']); ?>" class="frm_input" /></td>
							<td>
								<input type="file" name="bt_bimg1">
								<?php
								$bimg_str = "";
								$bimg = G5_DATA_PATH."/bottom/1";
								if (file_exists($bimg)) {
									$size = @getimagesize($bimg);
									if($size[0] && $size[0] > 150)
										$width = 150;
									else
										$width = $size[0];

									echo '<input type="checkbox" name="bt_bimg_del1" value="1" id="bt_bimg_del"> <label for="bt_bimg_del">삭제</label>';
									$bimg_str = '<img src="'.G5_DATA_URL.'/bottom/1" width="'.$width.'">';
								}
								if ($bimg_str) {
									echo '<div class="banner_or_img">';
									echo $bimg_str;
									echo '</div>';
								}
								?>
								<span style="font-size: 10px;color: #3c3c3c;">기준 사이즈 : 386px X 223px</span>
							</td>
							<td><input type="text" name="bt_url1" value="<?php echo $row['bt_url1']; ?>" class="frm_input" size="40" /></td>
						</tr>
						<tr>
							<td><input type="text" name="bt_alt2" value="<?php echo get_text($row['bt_alt2']); ?>" class="frm_input" /></td>
							<td>
								<input type="file" name="bt_bimg2">
								<?php
								$bimg_str = "";
								$bimg = G5_DATA_PATH."/bottom/2";
								if (file_exists($bimg)) {
									$size = @getimagesize($bimg);
									if($size[0] && $size[0] > 150)
										$width = 150;
									else
										$width = $size[0];

									echo '<input type="checkbox" name="bt_bimg_del2" value="1" id="bt_bimg_del"> <label for="bt_bimg_del">삭제</label>';
									$bimg_str = '<img src="'.G5_DATA_URL.'/bottom/2" width="'.$width.'">';
								}
								if ($bimg_str) {
									echo '<div class="review_or_img">';
									echo $bimg_str;
									echo '</div>';
								}
								?>
								<span style="font-size: 10px;color: #3c3c3c;">기준 사이즈 : 386px X 223px</span>
							</td>
							<td><input type="text" name="bt_url2" value="<?php echo $row['bt_url2']; ?>" class="frm_input" size="40" /></td>
						</tr>
						<tr>
							<td><input type="text" name="bt_alt3" value="<?php echo get_text($row['bt_alt3']); ?>" class="frm_input" /></td>
							<td>
								<input type="file" name="bt_bimg3">
								<?php
								$bimg_str = "";
								$bimg = G5_DATA_PATH."/bottom/3";
								if (file_exists($bimg)) {
									$size = @getimagesize($bimg);
									if($size[0] && $size[0] > 150)
										$width = 150;
									else
										$width = $size[0];

									echo '<input type="checkbox" name="bt_bimg_del3" value="1" id="bt_bimg_del"> <label for="bt_bimg_del">삭제</label>';
									$bimg_str = '<img src="'.G5_DATA_URL.'/bottom/3" width="'.$width.'">';
								}
								if ($bimg_str) {
									echo '<div class="review_or_img">';
									echo $bimg_str;
									echo '</div>';
								}
								?>
								<span style="font-size: 10px;color: #3c3c3c;">기준 사이즈 : 386px X 223px</span>
							</td>
							<td><input type="text" name="bt_url3" value="<?php echo $row['bt_url3']; ?>" class="frm_input" size="40" /></td>
						</tr>
					</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="btn_fixed_top">
    <input type="submit" value="저장" class="btn_submit btn" accesskey="s">
</div>

</form>

<script>

</script>
<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>