<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_SKIN_URL.'/style.css">', 0);
?>
<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<!-- 상품 사용후기 시작 { -->
    <?php
    $thumbnail_width = 500;

    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $is_num     = $total_count - ($page - 1) * $rows - $i;
        $is_star    = get_star($row['is_score']);
        $is_name    = get_text($row['is_name']);
        $is_subject = conv_subject($row['is_subject'],50,"…");
        $is_content = get_view_thumbnail(conv_content($row['is_content'], 1), $thumbnail_width);
        $is_reply_name = !empty($row['is_reply_name']) ? get_text($row['is_reply_name']) : '';
        $is_reply_subject = !empty($row['is_reply_subject']) ? conv_subject($row['is_reply_subject'],50,"…") : '';
        $is_reply_content = !empty($row['is_reply_content']) ? get_view_thumbnail(conv_content($row['is_reply_content'], 1), $thumbnail_width) : '';
        $is_time    = substr($row['is_time'], 2, 8);
        $is_href    = './itemuselist.php?bo_table=itemuse&amp;wr_id='.$row['wr_id'];

		$it = get_item($row['it_id']);

        $hash = md5($row['is_id'].$row['is_time'].$row['is_ip']);

		// get_itemuselist_thumbnail($row['it_id'], $row['is_content'], 50, 50);
		$item_link_href = shop_item_url($row['it_id']);

		$profile = get_profile($row['mb_id'], '50');
		if(!$profile)
			$profile = "<img src=\"/img/avatar.png\" alt=\"\">";

        if ($i == 0) echo '<ol>'; // id="sit_use_ol"
    ?>
		<li>
		<div class="avatar"><a href="javascript:void(0);"><?php echo $profile; ?></a></div>
		<div class="comment_right clearfix">
			<div class="comment_rating">
				<img src="<?php echo G5_SHOP_URL; ?>/img/s_star<?php echo $is_star; ?>.png" alt="별<?php echo $is_star; ?>개" width="85">
				<!--<i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class=" icon-star-empty"></i>-->
				<!--<span class="score"><?php echo $is_star; ?></span>-->
			</div>
			<div class="comment_info">
				<!-- 강의명 -->Lecture : <a href="<?php echo $item_link_href; ?>"><?php echo $it['it_name']; ?></a><span>ㆍ</span> <!-- 좋아요 -->Like :<span class="like" data-id="<?php echo $row['is_id']; ?>"><i class="icon-heart"></i><?php echo number_format($row['is_like']); ?></span> <span>ㆍ</span><span class="writer"><!-- 글쓴이 -->Writer : <?php echo $is_name; ?></span><span>ㆍ</span><?php echo $is_time; ?>
			</div>
			<p>
				 <?php echo $is_content; // 사용후기 내용 ?>
			</p>

			<?php if ($is_admin || $row['mb_id'] == $member['mb_id']) { ?>
			<div class="sit_use_cmd">
				<a href="<?php echo $itemuse_form."&amp;is_id={$row['is_id']}&amp;w=u"; ?>" class="itemuse_form btn01" onclick="return false;"><!-- 수정 -->Edit</a>
				<a href="<?php echo $itemuse_formupdate."&amp;is_id={$row['is_id']}&amp;w=d&amp;hash={$hash}"; ?>" class="itemuse_delete btn01"><!-- 삭제 -->Delete</a>
			</div>
			<?php } ?>
		</div>
		</li>
    <?php }

    if ($i > 0) echo '</ol>';

    if (!$i) echo '<p class="sit_empty">No Review</p>';
    ?>

<?php
//echo itemuse_page($config['cf_write_pages'], $page, $total_page, G5_SHOP_URL."/itemuse.php?it_id=$it_id&amp;page=", "");
?>

<script>
$(function(){
    $(".itemuse_form").click(function(){
        window.open(this.href, "itemuse_form", "width=810,height=680,scrollbars=1");
        return false;
    });

    $(".itemuse_delete").click(function(){
        if (confirm("정말 삭제 하시겠습니까?\n\n삭제후에는 되돌릴수 없습니다.")) {
            return true;
        } else {
            return false;
        }
    });

    $(".sit_use_li_title").click(function(){
        var $con = $(this).siblings(".sit_use_con");
        if($con.is(":visible")) {
            $con.slideUp();
        } else {
            $(".sit_use_con:visible").hide();
            $con.slideDown(
                function() {
                    // 이미지 리사이즈
                    $con.viewimageresize2();
                }
            );
        }
    });

    $(".pg_page").click(function(){
        $("#itemuse").load($(this).attr("href"));
        return false;
    });

	/* 좋아요 */
	$(".like").click(function(){
		if($(this).attr("data-id") == ''){
			return false;
		}

		$.ajax({
			type:"post",
			url:"/ajax/lec_use_like.php",
			data: {
				is_id: $(this).attr("data-id")
			},
			success:function(data){
				if(data){
					alert(data);
					return false;
				}else{
					location.reload();
				}
			}
		});
	});
});
</script>
<!-- } 상품 사용후기 끝 -->