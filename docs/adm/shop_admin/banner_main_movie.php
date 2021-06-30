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
<form name="fbottom" action="./banner_movie_formupdate.php" method="post" enctype="multipart/form-data">

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
							<td colspan="2">
								<input type="text" name="mv_movie_url" value="<?php echo $row['mv_movie_url']; ?>" class="frm_input" placeholder="영상주소" size="80" />
							</td>
						</tr>
						<tr>
							<th>좌측 이미지</th>
							<th>연결 URL</th>
							<th>제목</th>
							<th>내용</th>
						</tr>
						<tr>
							<td>
								<input type="file" name="mv_bimg1">
								<?php
								$bimg_str = "";
								$bimg = G5_DATA_PATH."/movie/1";
								if (file_exists($bimg)) {
									$size = @getimagesize($bimg);
									if($size[0] && $size[0] > 150)
										$width = 150;
									else
										$width = $size[0];

									echo '<input type="checkbox" name="mv_bimg_del1" value="1" id="mv_bimg_del"> <label for="mv_bimg_del">삭제</label>';
									$bimg_str = '<img src="'.G5_DATA_URL.'/movie/1" width="'.$width.'">';
								}
								if ($bimg_str) {
									echo '<div class="banner_or_img">';
									echo $bimg_str;
									echo '</div>';
								}
								?>
								<span style="font-size: 10px;color: #3c3c3c;">기준 사이즈 : 819px X 461px</span>
							</td>
							<td><input type="text" name="mv_url1" value="<?php echo $row['mv_url1']; ?>" class="frm_input" size="40" /></td>
							<td><input type="text" name="mv_subject1" value="<?php echo get_text($row['mv_subject1']); ?>" class="frm_input" size="40" /></td>
							<td><textarea name="mv_content1" class="frm_input" style="width: 325px;" /><?php echo get_text($row['mv_content1']); ?></textarea>
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