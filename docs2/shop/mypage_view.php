<?php
include_once('./_common.php');
define('_NOHEADER_',true);

$od_id = isset($od_id) ? preg_replace('/[^A-Za-z0-9\-_]/', '', strip_tags($od_id)) : 0;

if( isset($_GET['ini_noti']) && !isset($_GET['uid']) ){
    goto_url(G5_SHOP_URL.'/mypage.php');
}

// 불법접속을 할 수 없도록 세션에 아무값이나 저장하여 hidden 으로 넘겨서 다음 페이지에서 비교함
$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

if (!$is_member) {
    if (get_session('ss_orderview_uid') != $_GET['uid'])
        alert("직접 링크로는 수강이 불가합니다.\\n\\n내 강의보기 페이지를 통하여 수강하시기 바랍니다.", G5_SHOP_URL."/mypage.php");
}

$sql = "select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
if($is_member && !$is_admin)
    $sql .= " and mb_id = '{$member['mb_id']}' ";
$od = sql_fetch($sql);
if (!$od['od_id'] || (!$is_member && md5($od['od_id'].$od['od_time'].$od['od_ip']) != get_session('ss_orderview_uid'))) {
    alert("내 강의보기 페이지를 확인해 주세요.", G5_SHOP_URL);
}
/*
if (!$is_member)
    goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_SHOP_URL."/mypage.php"));
*/
if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/myclass_view.php');
    return;
}

// 테마에 mypage.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
    $theme_mypage_file = G5_THEME_SHOP_PATH.'/mypage.php';
    if(is_file($theme_mypage_file)) {
        include_once($theme_mypage_file);
        return;
        unset($theme_mypage_file);
    }
}

$g5['title'] = '내 클래스';
include_once('./_head.php');

$t_day = date("Y-m-d");

$sQuery = " SELECT *
			FROM {$g5['g5_shop_cart_table']}
			WHERE mb_id = '".$member['mb_id']."'
			AND   ct_status IN ('입금','배송','완료')
			AND   ct_id = '".$ct_id."'
			AND   ct_start_date <= '".$t_day."'
			AND   ct_end_date >= '".$t_day."'
			";
$rst = sql_fetch($sQuery);

if(!$rst){
	alert('잘못된 정보입니다.','./mypage.php');
}

$it = get_item($rst['it_id']);
$ca = get_cate($it['ca_id']);
$image = get_it_image($rst['it_id'], 520, 293);
$it_id = $rst['it_id'];

// 강의 첫번째 영상/진행된 영상 가져오기
$first_mv = get_use_mv_check($it_id, $member['mb_id']);
if($first_mv){
	insert_mv_log($first_mv['it_id'], $first_mv['cp_id'], $first_mv['mv_id'], $member['mb_id']);
}

// 리뷰작성 내역 확인
$sQuery = " SELECT *
			FROM g5_shop_item_use
			WHERE mb_id = '".$member['mb_id']."'
			AND   it_id = '".$it_id."'
			";
$review_check = sql_fetch($sQuery);
?>
<!-- 내 클래스 시작 { -->
<section id="sub_course">
	<div class="container">
		<div class="row">
			<div class="col-md-7">
				<div class="course_tit">
					<?php echo get_text($it['it_name']); ?>
					<span><?php echo get_text($it['it_basic']); ?></span>
				</div>
				<div class="course_movie">
					<?php
					if(is_mobile()){
						$mv_width = "100%";
						$mv_height = "auto";
					}else{
						$mv_width = "670";
						$mv_height = "405";
					}

					if($it['it_zoom'] == '1'){
						$it_img = G5_DATA_PATH.'/item/'.$it['it_zoom_img'];
						$it_img_exists = run_replace('shop_item_image_exists', (is_file($it_img) && file_exists($it_img)), $it, $i);

						if($it_img_exists) {
							$thumb = get_it_thumbnail($it['it_zoom_img'], 670, 405);
							//$img_tag = run_replace('shop_item_image_tag', '<img src="'.G5_DATA_URL.'/item/'.$it['it_zoom_img'].'" class="shop_item_preview_image" >', $it, $i);

							echo $thumb;
						}
					}else if($first_mv['mv_url']){ // 콜러스
					?>
						<iframe width="<?php echo $mv_width; ?>" height="<?php echo $mv_height; ?>" src="" id="kollus_mv" frameborder="0" webkitallowfullscreen mozallowfullscreen allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen=""></iframe>
					<?php }else{ ?>
						<b>강의가 곧 오픈됩니다</b>
					<?php } ?>
				</div>
				<?php
				if($it['it_zoom'] == '1'){
				?>
				<div class="course_review">
					<span class="course_small_tit">
						ZOOM 접속 정보
					</span>
					<div class="sub_detail_review add_bottom_60">
						<div id="comments">
							<ol>
								<li>&nbsp;&nbsp;실시간 수강 URL : <a href="<?php echo $it['it_zoom_url']; ?>" target="_blank"><?php echo $it['it_zoom_url']; ?></a></li>
								<li>&nbsp;&nbsp;ZOOM 비밀번호 : <?php echo $it['it_zoom_pw']; ?></li>
							</ol>
						</div>
					</div>
				</div>
				<?
				}
				?>
			</div>
			<div class="col-md-5">
				<select name="" id="div_slt" class="frm_input full_input">
				<?php
				$sQuery = " SELECT *
							FROM han_shop_item_list
							WHERE it_id = '".$it_id."'
							ORDER BY ir_order ASC, ir_no ASC
							";
				//echo"<p>".$sQuery;
				$sql = sql_query($sQuery);

				for($i = 0; $rows = sql_fetch_array($sql); $i++){
					$lect = sql_fetch("SELECT * FROM han_shop_lec WHERE lt_id = '".$rows['it_id2']."' ");
				?>
					<option value="<?php echo $lect['lt_id']; ?>"><?php echo $lect['it_name']; ?></option>
				<?php } ?>
				</select>
				<div class="course_list" id="divLectureList" style="margin:0px;">
				<!-- 강의목록 추력 -->
				</div>
			</div>

			<div class="col-md-7">
				<div class="course_review">
					<span class="course_small_tit">
						수강후기 작성
					</span>
					<div class="review_area">
					<form name="fitemuse" method="post" action="./itemuseformupdate.php" onsubmit="return fitemuse_submit(this);" autocomplete="off" enctype="multipart/form-data">
						<input type="hidden" name="w" value="<?php echo $w; ?>">
						<input type="hidden" name="it_id" value="<?php echo $it_id; ?>">
						<input type="hidden" name="is_id" value="<?php echo $is_id; ?>">
						<input type="hidden" name="ct_id" value="<?php echo $ct_id; ?>">
						<input type="hidden" name="is_mobile_shop" value="1">
						<input type="hidden" name="is_score" value="<?php echo $is_score; ?>" id="is_score10" >

						<textarea name="is_content" placeholder="내용을 입력해 주세요" class="review_content"></textarea>
						<div class="file_input_div">
							<i class="fa fa-plus-circle file_input_img_btn" alt="open" /><input type="file" name="is_file" class="file_input_hidden" id="is_file" onchange="$('#fileName').html('파일명 : ' + this.value.replace(/c:\\facepath\\/i,''))" accept="image/*" /></i>
						</div>
						<label for="wr_file" class="wr_file">이미지첨부 <span>(200kb이하 파일만 등록가능합니다)</span></label>
						<span id="fileName" class="file_input_textbox"></span>

						<select name="is_score" class="frm_input" style="position: absolute;right: 100px;bottom: 19px;height: 37px;padding: 0px 7px;display: inline-block;" >
						<?php
						for($i = 5; $i >= 1; $i--){
							if($is_score == $i)
								$slt = "selected";
							else
								$slt = "";

							echo'<option value="'.$i.'">'.$i.' 점</option>';
						}
						?>
						</select>
						<button type="submit" class="review_submit">등록하기</button>
						</form>
					</div>
					<div class="sub_detail_review add_bottom_60">
						<div id="comments">
							<ol>
								<?php
								$my_id = $member['mb_id'];
								include_once(G5_SHOP_PATH.'/itemuse.php');
								?>
							</ol>
						</div>
						<div class="btn_more"><a href="/shop/review.php" style="width:180px;margin:70px auto 70px auto;">다른 수강평 더보기</a></div>

					</div>
				</div>
			</div>
			<div class="col-md-5">
				<div class="course_notice">
					<div class="course_small_tit">클래스 첨부파일</div>
					<ul>
						<?php
						// 등록된 강의 정보 가져오기
						$sQuery = " SELECT *
									FROM han_shop_item_list
									WHERE it_id = '".$it['it_id']."'
									ORDER BY ir_order ASC, ir_no ASC
									";
						$sql_s = sql_query($sQuery);

						for($k = 0; $han = sql_fetch_array($sql_s); $k++){
							$it_id2 = $han['it_id2'];

							$lec = sql_fetch("SELECT * FROM han_shop_lec WHERE lt_id = '".$it_id2."' ");

							$sQuery = " SELECT *
										FROM han_shop_chapter
										WHERE lt_id = '".$it_id2."'
										AND   cp_name != ''
										ORDER BY (cp_order + 0) ASC
										";
							$result = sql_query($sQuery);

							for($i = 0; $row = sql_fetch_array($result); $i++){
						?>
							<li>
							<!--이두희 <span class="date">2019.05.05 11:11</span>-->
							<?php echo stripslashes($lec['it_name']); ?>
							<p>클래스 <?php echo sprintf("%02d", ($i + 1)); ?> - <?php echo stripslashes($row['cp_name']); ?></p>
							<?php
							$sQuery = " SELECT *
										FROM han_shop_movie
										WHERE lt_id = '".$it_id2."'
										AND   cp_id = '".$row['cp_id']."'
										AND   mv_use = '1'
										AND   mv_file != ''
										ORDER BY (mv_order + 0) ASC, mv_id DESC ";
							$sql = sql_query($sQuery);

							for($j = 0; $rstc = sql_fetch_array($sql); $j++){
							?>
							<a href="/data/movie<?php echo $rstc['mv_file']; ?>" class="wr_file" download><i class="fa fa-download"></i> <span><?php echo $rstc['mv_file_name']; ?></span></a>
							<?php
							}
							if($j == 0)
								echo"<a href='javascript:void(0);'><i class=\"fa fa-download\"></i> <span>등록된 첨부파일이 없습니다.</span></a>";
							?>
							</li>
						<?php
							}
						}
						?>
					</ul>
				</div>

				<div class="course_notice" style="border-top:0px;">
					<div class="course_small_tit">클래스 공지</div>
					<ul>
						<?php
						$sQuery = " SELECT *
									FROM han_write_notice
									WHERE wr_is_comment = 0
									AND   wr_1 LIKE '%".$rst['it_id']."%'
									GROUP BY wr_id
									ORDER BY wr_id DESC
									LIMIT 3
									";
						$sql = sql_query($sQuery);
						for($i = 0; $row = sql_fetch_array($sql); $i += 1) {
							$subject = conv_subject($row['wr_subject'], 24, '…');
							$l_href = get_pretty_url('notice', $row['wr_id'], $qstr);
						?>
							<li>
								한컴아카데미 <span class="date"><?php echo date("Y.m.d H:i", strtotime($row['wr_datetime'])); ?></span>
								<p><a href="<?php echo $l_href; ?>"><?php echo $subject; ?></a></p>
							</li>
						<?php
						}
						if($i == 0) echo "<li>등록된 공지사항이 없습니다.</li>";
						?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>

<script>
var page = "<?php echo $page; ?>";
var it_id = "<?php echo $it_id; ?>";
var im_id = "<?php echo $first_mv['mv_id']; ?>";
var im_key = "<?php echo $first_mv['mv_url']; ?>";

function get_lect_list(){
	$.ajax({
		type:"post",
		url: "/ajax/myclass_list.php",
		data: {
			it_id: "<?php echo $rst['it_id']; ?>"
		},
		success: function(data){
			$("#divLectureList").html(data);

			// 강의 챕터 오픈
			var lt_id = "#" + "<?php echo $first_mv['cp_id']; ?>";
			$(lt_id).children("ul.mv_list").show();

			// 보던 영상 위치로
			var lec_list_top = $(".mv_url[data-url="+im_key+"]" ).offset().top - 200;
			$("#divLectureList").animate({scrollTop: lec_list_top}, 500);
		}
	});
}

function get_myclass_list(tab){
	var link_page = "./ajax/myclass_"+ tab +".php";

	$(".mc_li").removeClass("on");
	$("#"+tab).addClass("on");

	$.ajax({
		type:"post",
		url: link_page,
		data: {
			bo_table: tab,
			wr_id: "<?php echo $rst['it_id']; ?>",
			ct_id: "<?php echo $ct_id; ?>",
			page: page
		},
		success: function(data){
			$("#comments").html(data);
			if(tab == 'feed') $("#fd_cnt").text( $("#feed_cnt").attr("data-id") );
		}
	});
}

function get_mv_url(){
	$.ajax({
		type:"post",
		url: "/ajax/call_player_jwt.php",
		data: {
			it_id: it_id,
			im_id: im_id,
			im_key: im_key
		},
		success: function(data){
			$("#kollus_mv").attr("src", data);
		}
	});
}

$(function() {
	$("a.dep1").on("click",function(){
		if($(this).hasClass('active')) {
			$(this).children('i').addClass('fa-angle-down');
			$(this).children('i').removeClass('fa-angle-up');
		}else{

			$(this).children('i').removeClass('fa-angle-down');
			$(this).children('i').addClass('fa-angle-up');
		}
		$(this).next("ul.dep2").toggleClass('active');
		$(this).toggleClass('active');
	});

	$(document).on("click",".mv_url",function(){
		if($(this).attr("data-url") == ""){
			alert('클래스가 곧 오픈됩니다.');
			return false;
		}

		it_id = $(this).attr("data-it");
		im_id = $(this).attr("data-mv");
		im_key = $(this).attr("data-url");
		get_mv_url();

		$.ajax({
			type:"POST",
			url:"/ajax/mv_log.php",
			data: {
					it_id: $(this).attr("data-it")
				,	cp_id: $(this).attr("data-cp")
				,	mv_id: $(this).attr("data-mv")
				,	mb_id: "<?php echo $member['mb_id']; ?>"
			},
			success:function(data){
				console.log(data);
			}
		});
	});

	$(".mc_li").click(function(){
		if(location.hash != $(this).attr('data-id')){
			page = '1'; // 탭 변경시 페이지 리셋
		}
		location.hash = $(this).attr('data-id');
		get_myclass_list($(this).attr('data-id'));
	});

	$("#div_slt").change(function(){
		var lec_list_top = $("#" + $(this).val() ).offset().top;

		$("#divLectureList").animate({scrollTop: lec_list_top}, 500);
	});

	if(location.hash){
		var my_hash = location.hash.replace('#', '');
		get_myclass_list(my_hash);
	}else{
		get_myclass_list('feed');
	}

	get_lect_list();
	get_mv_url();
});

function fitemuse_submit(f)
{
	if($(".review_content").val() == ""){
		alert('수강후기 내용을 입력해 주세요.');
		return false;
	}

    return true;
}
</script>
<!-- } 내 클래스 끝 -->

<?php
include_once("./_tail.php");
?>