<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
define('_NOHEADER_',true);

include_once(G5_THEME_PATH.'/head.php');
?>

<section id="sub_course">
	<div class="container">
		<div class="row">
			<div class="col-md-7">
				<div class="course_tit">
					파이썬을 활용한 데이터 분석
					<span>1. 파이썬 프로그래밍 - 01. 파이썬이란?</span>
				</div>
				<div class="course_movie">
					MOVIE
				</div>
				<div class="course_review">
					<span class="course_small_tit">
						수강 후기 작성
					</span>
					<div class="review_area">
						<textarea name="wr_content" placeholder="내용을 입력해 주세요" class="review_content"></textarea>
						<div class="file_input_div">
							<i class="fa fa-plus-circle file_input_img_btn" alt="open" /><input type="file" name="file_1" class="file_input_hidden" onchange="$('#fileName').html('파일명 : ' + this.value.replace(/c:\\facepath\\/i,''))" accept="image/*" /></i>

						</div>
						<label for="wr_file" class="wr_file">이미지첨부 <span>(200kb이하 파일만 등록가능합니다)</span></label>
						<span id="fileName" class="file_input_textbox"></span>




						<button type="submit" class="review_submit">등록하기</button>
					</div>
					<div class="sub_detail_review add_bottom_60">
						<div id="comments">
							<ol>
								<?php
								for($i = 0;$i < 4;$i += 1){ ?>
								<li>
								<div class="avatar"><a href="#"><img src="/img/avatar.png" alt=""></a></div>
								<div class="comment_right clearfix">
									<div class="comment_info">
										<span class="writer">홍길동</span><span class="date">2020.01.23</span>
									</div>
									<p>
										설치할때 파일이 안렬ㅇㄹㄴㅇㅁㄹ
									</p>

									<div class="comment_action">
										<button type="button" class="reply"><i class="fa fa-comment"></i> 댓글쓰기</button>
										<button type="button" class="modify" wr-id="">수정</button>
										<button type="button" class="del" wr-id="">삭제</button>
									</div>
								</div>
								</li>
								<?php } ?>

							</ol>
						</div>
						<div class="btn_more"><a href="#" style="width:180px;margin:70px auto 70px auto;">다른 수강평 더보기</a></div>

					</div>
				</div>
			</div>
			<div class="col-md-5">
				<div class="course_list">
					<ul>
						<?php
						for($i  = 0;$i < 4;$i += 1) {
							$ac = "";
							$aa = "down";
							if($i == 0) {$ac = "active"; $aa = "up";}
						?>
						<li class=""><a href="javascript://"  class="dep1 <?php echo $ac?>">
						<span>CLASS 0<?php echo $i+1?></span>
						파이썬 프로그래밍의 이해
						<i class="fa fa-angle-<?php echo $aa?>"></i>
						</a>
							<ul class="dep2 <?php echo $ac?>">
								<?php
								for($j = 0;$j < 4;$j += 1) {
									//ing : 수강중, complete : 수강완료
									$cl = '';
									$ac = '';
									if($j == 0) $cl = 'complete';
									if($j == 1) {$cl = 'ing'; $ac='active';}
								?>
								<li><a href="javascript://"  class="<?php echo $ac?>"><?php echo $j+1?>강. 파이썬 프로그래밍이란?
								<span class="<?php echo $cl?>">30:00 수강완료</span>
								</a>
								</li>
								<?php } ?>
							</ul>
						</li>
						<?php } ?>
					</ul>
				</div>
				<div class="course_notice">
					<div class="course_small_tit">클래스 공지</div>
					<ul>
						<?php for($i = 0;$i < 2;$i += 1) { ?>
						<li>이두희 <span class="date">2019.05.05 11:11</span>
						<p>클래스 01 - 2강의 파이썬 데이터 샘플 입니다.</p>
						<?php for($j = 0;$j < 2;$j += 1) { ?>
						<a href="#" class="wr_file"><i class="fa fa-download"></i> <span>실습자료파일 01.xls</span></a>
						<?php } ?>
						</li>
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
<script>
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
</script>
</section>
<?php
include_once(G5_THEME_PATH.'/tail.php');
?>