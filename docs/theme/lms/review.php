<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
define('_NOHEADER_',true);

include_once(G5_THEME_PATH.'/head.php');
?>
<section id="sub_community">
	<div class="container">
		<div class="row">
			<?php
			include_once(G5_THEME_PATH.'/community.left.php');
			?>
			
			<div class="col-md-9">
				<div class="community">
					<div class="community-tit">
						수강후기 모아보기
					</div>
					
					<div class="community-wrap">
						<div class="row add_bottom_30">
							<div class="col-md-12">
								<div class="sub_detail_review">
									<div id="comments">
										<ol>
											<?php
											for($i = 0;$i < 4;$i += 1){ ?>
											<li>
											<div class="avatar"><a href="#"><img src="/img/item_thumb_105x105.jpg" alt=""></a></div>
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
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
include_once(G5_THEME_PATH.'/tail.php');
?>