<?php
$sub_menu = '100500';
include_once('./_common.php');

auth_check($auth[$sub_menu], "w");

$bn_id = preg_replace('/[^0-9]/', '', $bn_id);

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
<form name="freview" action="./banner_review_formupdate.php" method="post" enctype="multipart/form-data">

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
								<input type="text" name="bn_title" value="<?php echo get_text($row['bn_title']); ?>" class="frm_input" placeholder="수강후기 타이틀" size="80" />
							</td>
						</tr>
						<tr>
							<th>배너 이미지</th>
							<th>URL</th>
							<th>작성자</th>
							<th>직업</th>
							<th>제목</th>
							<th>내용</th>
						</tr>
						<tr>
							<td>
								<input type="file" name="bn_bimg1">
								<?php
								$bimg_str = "";
								$bimg = G5_DATA_PATH."/review/1";
								if (file_exists($bimg)) {
									$size = @getimagesize($bimg);
									if($size[0] && $size[0] > 150)
										$width = 150;
									else
										$width = $size[0];

									echo '<br><input type="checkbox" name="bn_bimg_del1" value="1" id="bn_bimg_del"> <label for="bn_bimg_del">삭제</label>';
									$bimg_str = '<img src="'.G5_DATA_URL.'/review/1" width="'.$width.'">';
								}
								if ($bimg_str) {
									echo '<div class="banner_or_img">';
									echo $bimg_str;
									echo '</div>';
								}
								?>
								<span style="font-size: 10px;color: #3c3c3c;">기준 사이즈 : 127px X 100px</span>
							</td>
							<td><input type="text" name="bn_url1" value="<?php echo $row['bn_url1']; ?>" class="frm_input" size="40" /></td>
							<td><input type="text" name="bn_alt1" value="<?php echo get_text($row['bn_alt1']); ?>" class="frm_input" size="10" /></td>
							<td><input type="text" name="bn_job1" value="<?php echo get_text($row['bn_job1']); ?>" class="frm_input" /></td>
							<td><input type="text" name="bn_subject1" value="<?php echo get_text($row['bn_subject1']); ?>" class="frm_input" size="40" /></td>
							<td><textarea name="bn_content1" class="frm_input" style="width: 325px;" /><?php echo get_text($row['bn_content1']); ?></textarea>
						</tr>
						<tr>
							<td>
								<input type="file" name="bn_bimg2">
								<?php
								$bimg_str = "";
								$bimg = G5_DATA_PATH."/review/2";
								if (file_exists($bimg)) {
									$size = @getimagesize($bimg);
									if($size[0] && $size[0] > 150)
										$width = 150;
									else
										$width = $size[0];

									echo '<br><input type="checkbox" name="bn_bimg_del2" value="1" id="bn_bimg_del"> <label for="bn_bimg_del">삭제</label>';
									$bimg_str = '<img src="'.G5_DATA_URL.'/review/2" width="'.$width.'">';
								}
								if ($bimg_str) {
									echo '<div class="review_or_img">';
									echo $bimg_str;
									echo '</div>';
								}
								?>
								<span style="font-size: 10px;color: #3c3c3c;">기준 사이즈 : 127px X 100px</span>
							</td>
							<td><input type="text" name="bn_url2" value="<?php echo $row['bn_url2']; ?>" class="frm_input" size="40" /></td>
							<td><input type="text" name="bn_alt2" value="<?php echo get_text($row['bn_alt2']); ?>" class="frm_input" size="10" /></td>
							<td><input type="text" name="bn_job2" value="<?php echo get_text($row['bn_job2']); ?>" class="frm_input" /></td>
							<td><input type="text" name="bn_subject2" value="<?php echo get_text($row['bn_subject2']); ?>" class="frm_input" size="40" /></td>
							<td><textarea name="bn_content2" class="frm_input" style="width: 325px;" /><?php echo get_text($row['bn_content2']); ?></textarea>
						</tr>
						<tr>
							<td>
								<input type="file" name="bn_bimg3">
								<?php
								$bimg_str = "";
								$bimg = G5_DATA_PATH."/review/3";
								if (file_exists($bimg)) {
									$size = @getimagesize($bimg);
									if($size[0] && $size[0] > 150)
										$width = 150;
									else
										$width = $size[0];

									echo '<br><input type="checkbox" name="bn_bimg_del3" value="1" id="bn_bimg_del"> <label for="bn_bimg_del">삭제</label>';
									$bimg_str = '<img src="'.G5_DATA_URL.'/review/3" width="'.$width.'">';
								}
								if ($bimg_str) {
									echo '<div class="review_or_img">';
									echo $bimg_str;
									echo '</div>';
								}
								?>
								<span style="font-size: 10px;color: #3c3c3c;">기준 사이즈 : 127px X 100px</span>
							</td>
							<td><input type="text" name="bn_url3" value="<?php echo $row['bn_url3']; ?>" class="frm_input" size="40" /></td>
							<td><input type="text" name="bn_alt3" value="<?php echo get_text($row['bn_alt3']); ?>" class="frm_input" size="10" /></td>
							<td><input type="text" name="bn_job3" value="<?php echo get_text($row['bn_job3']); ?>" class="frm_input" /></td>
							<td><input type="text" name="bn_subject3" value="<?php echo get_text($row['bn_subject3']); ?>" class="frm_input" size="40" /></td>
							<td><textarea name="bn_content3" class="frm_input" style="width: 325px;" /><?php echo get_text($row['bn_content3']); ?></textarea>
						</tr>
						<tr>
							<td>
								<input type="file" name="bn_bimg4">
								<?php
								$bimg_str = "";
								$bimg = G5_DATA_PATH."/review/4";
								if (file_exists($bimg)) {
									$size = @getimagesize($bimg);
									if($size[0] && $size[0] > 150)
										$width = 150;
									else
										$width = $size[0];

									echo '<br><input type="checkbox" name="bn_bimg_del4" value="1" id="bn_bimg_del"> <label for="bn_bimg_del">삭제</label>';
									$bimg_str = '<img src="'.G5_DATA_URL.'/review/4" width="'.$width.'">';
								}
								if ($bimg_str) {
									echo '<div class="review_or_img">';
									echo $bimg_str;
									echo '</div>';
								}
								?>
								<span style="font-size: 10px;color: #3c3c3c;">기준 사이즈 : 127px X 100px</span>
							</td>
							<td><input type="text" name="bn_url4" value="<?php echo $row['bn_url4']; ?>" class="frm_input" size="40" /></td>
							<td><input type="text" name="bn_alt4" value="<?php echo get_text($row['bn_alt4']); ?>" class="frm_input" size="10" /></td>
							<td><input type="text" name="bn_job4" value="<?php echo get_text($row['bn_job4']); ?>" class="frm_input" /></td>
							<td><input type="text" name="bn_subject4" value="<?php echo get_text($row['bn_subject4']); ?>" class="frm_input" size="40" /></td>
							<td><textarea name="bn_content4" class="frm_input" style="width: 325px;" /><?php echo get_text($row['bn_content4']); ?></textarea>
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