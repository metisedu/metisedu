<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
define('_NOHEADER_',true);

include_once(G5_THEME_PATH.'/head.php');
?>
<section id="sub-header">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="sub_cover" style="">
					<div class="sub_cover_contents">
						<div class="ribbon">
							<span class="ribbon1">딥러닝</span>
							<span class="ribbon2">중급</span>
						</div>
						<div class="sub_cover_tit">파이썬을 활용한 데이터 분석 집중 클래스</div>
						<div class="sub_cover_rating">
							<div class="rating">
								<i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class=" icon-star-empty"></i>
							</div>
						</div>
						<div class="sub_cover_score">4.2</div>
						<div class="sub_cover_price"><?php echo number_format(4200000)?>원</div>
						<div class="sub_cover_txt1">평생소장</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="sub_view_content">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="sub_detail_tit"><!-- 강의소개 -->Lecture Information</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-9">
				<div class="sub_detail_content add_bottom_60">
					<div style="width:100%;min-height:500px;background-color:#ececec">
					에디터영역
					</div>
				</div>
				<div class="sub_detail_content add_bottom_60">
					<div class="sub_detail_tit"><!-- 커리큘럼 -->Curriculum</div>

					<div class="sub_detail_course_subj">파이썬을 활용한 데이터 분석 <i class="fa fa-angle-down" aria-hidden="true"></i></div>
					<div class="sub_detail_course_dep1">01. 파이썬 프로그램의 이해 <i class="fa fa-angle-down" aria-hidden="true"></i></div>
					<div class="sub_detail_course_dep2">
						<ul>
							<li><a href="#">1강. 파이썬 프로그래밍이란? <span class="time">18:30</span> <i class="fa fa-play" aria-hidden="true"></i></a>
							<li><a href="#">2강. 파이썬 프로그래밍이란? <span class="time">18:30</span> <i class="fa fa-lock" aria-hidden="true"></i></a>
						</ul>
					</div>
					<div class="sub_detail_course_dep1">02. 파이썬으로 데이터 분석하기 <i class="fa fa-angle-down" aria-hidden="true"></i></div>
					<div class="sub_detail_course_dep2">
						<ul>
							<li><a href="#">1강. 파이썬 프로그래밍이란? <span class="time">18:30</span> <i class="fa fa-play" aria-hidden="true"></i></a>
							<li><a href="#">2강. 파이썬 프로그래밍이란? <span class="time">18:30</span> <i class="fa fa-lock" aria-hidden="true"></i></a>
						</ul>
					</div>
				</div>
				<div class="sub_detail_review add_bottom_60">
					<div class="sub_detail_tit">수강후기</div>
					<div id="comments">
						<ol>
							<?php
							for($i = 0;$i < 4;$i += 1){ ?>
							<li>
							<div class="avatar"><a href="#"><img src="/img/avatar.png" alt=""></a></div>
							<div class="comment_right clearfix">
								<div class="comment_rating">
									<i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class=" icon-star-empty"></i>
									<span class="score">4.2</span>
								</div>
								<div class="comment_info">
									강의명 : <a href="#">그로스해킹 - 데이터와 실험을 통해 성장하는 서비스를 만드는 방법</a><span>ㆍ</span> 좋아요:<span class="like"><i class="icon-heart"></i>2</span> <span>ㆍ</span><span class="writer">글쓴이 : HJOO</span><span>ㆍ</span>2020.01.23
								</div>
								<p>
									 온라인 마케터는 물론이고 서비스의 성장과 관련된 사람들이라면 꼭 봐야할 강의 입니다. 
스타트업이라면 모든 구성원이 스터디 해야되는 부분이라고 생각해요. 
보통 막연하게 광고를 집행하거나 GA 그래프 기울기만 보는 팀이 많은데, 이 강의는 디테일하게 그것들을 어떻게 분석하고
바라볼 것인가에 대해서 이론과 경험 모두를 아우르며 설명해 줍니다.
배우게 되는것도 많고 서비스의 성장을 책임지는 사람으로서 반성하게 되는 부분도 많네요. 완전 강추!!
								</p>
							</div>							
							</li>
							<?php } ?>

						</ol>
					</div>
					<div class="btn_more"><a href="#"><!-- 다른 수강평 더보기 -->See more</a></div>
					
				</div>

				

				<div class="sub_detail_relation add_bottom_60">
					<div class="sub_detail_tit">함께 수강하면 좋은 강의</div>
						<?php
						for($i = 0;$i < 4;$i += 1) {
						?>
						<div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 list-item">
							<div class="col-item">
							<span class="ribbon_course">
								<span class="ribbon1">딥러닝</span>
								<span class="ribbon2">중급</span>
							</span>
								<div class="photo">
									<a href="/sub/view/"><img src="/img/item285x285.png" alt=""></a>
								</div>
								<div class="info">
									<div class="row">
										<div class="course_info col-md-12 col-sm-12">
											<h4>파이썬으로 시작하는 딥러닝 기초 집중 클래스 반</h4>
											<div class="rating">
											<i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class=" icon-star-empty"></i>
											</div>
											<div class="clearfix">
												<span class="pull-left teacher">이두희 | 온라인</span>
												<span class="pull-right price"><?php echo number_format(296000)?>원</span>
											</div>


										</div>
									</div>
									
								</div>
							</div>
						</div>
						<?php } ?>
				</div>

			</div>
			<aside class="col-md-3">
				<div class="box_style_4 sub_detail_action">
					<div class="media">
                        <div class="pull-left">
                            <img src="/img/item_thumb_68x68.jpg" class="" alt="">
                        </div>
                        <div class="media-body">
                            <h5 class="media-heading"><a href="#">파이썬을 활용한 데이터 분석 집중 클래스</a></h5>                           
                        </div>
						<div class="sub_detail_action_price">
							<strike>500,000원</strike><br />
							250,000원
						</div>
                    </div>
					<div class="sub_detail_action_info">
						<dl>
							<dt>수강기간</dt>
							<dd>평생 소장</dd>
							<dt>수강평</dt>
							<dd class="">

								<i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class=" icon-star-empty"></i> 4.3


							</dd>
							<dt>강사</dt>
							<dd>이두희</dd>
						</dl>
					</div>
					<div class="sub_detail_action_btn">
						<a href="javascript://" class="direct_buy">강의 구매하기</a>
						<a href="javascript://" class="go_cart">장바구니</a>
					</div>
					
				</div>
			</aside>
		</div>
	</div>
<script>
$(".box_cate_tit").on("click",function(){

	if($(this).hasClass("active")) {
		$(this).removeClass("active");
		$(this).parent().parent().removeClass("active");
		$(this).parent().next(".box_category").slideUp();
	}else{
		$(this).addClass("active");
		$(this).parent().parent().addClass("active");
		$(this).parent().next(".box_category").slideDown();
	}
});

$(".sub_detail_course_dep1").on("click",function(){
	$(this).next(".sub_detail_course_dep2").toggle("slideUp");
});
</script>
</section>

<?php
include_once(G5_THEME_PATH.'/tail.php');
?>