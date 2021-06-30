<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_THEME_PATH.'/head.php');
?>

<section id="sub_content">
	<div class="container">
		<div class="row">
			<aside class="col-lg-3 col-md-4 col-sm-4">
				<div class="box_style_1">
            	<h4><a href="#" class="box_cate_tit active">프로그램 세부 설정</a></h4>
				<div class="box_category">
					<span class="box_subj">난이도</span>
					<ul class="submenu-col">
						<li><div class="page__toggle">
							<label class="toggle">
							  <input class="toggle__input" type="checkbox" name="diff" value="1">
							  <span class="toggle__label">
								<span class="toggle__text"> 초보자도 쉽게 가능해요</span>
							  </span>
							</label>
							</div>
						</li>
						<li><div class="page__toggle">
							<label class="toggle">
							  <input class="toggle__input" type="checkbox" name="diff" value="2">
							  <span class="toggle__label">
								<span class="toggle__text"> 다소 어려울 수 있어요</span>
							  </span>
							</label>
							</div>
						</li>
						<li><div class="page__toggle">
							<label class="toggle">
							  <input class="toggle__input" type="checkbox" name="diff" value="3">
							  <span class="toggle__label">
								<span class="toggle__text"> 숙련자라면 OK</span>
							  </span>
							</label>
							</div>
						</li>
					</ul>
					
					<div class="hr15"></div>
					<span class="box_subj">할인여부</span>
					<ul class="submenu-col">
						<li><div class="page__toggle">
							<label class="toggle">
							  <input class="toggle__input" type="checkbox" name="dis" value="1">
							  <span class="toggle__label">
								<span class="toggle__text"> 할인중 20%</span>
							  </span>
							</label>
							</div>
						</li>
						<li><div class="page__toggle">
							<label class="toggle">
							  <input class="toggle__input" type="checkbox" name="dis" value="2">
							  <span class="toggle__label">
								<span class="toggle__text"> 국비 환급 과정</span>
							  </span>
							</label>
							</div>
						</li>
						<li><div class="page__toggle">
							<label class="toggle">
							  <input class="toggle__input" type="checkbox" name="dis" value="3">
							  <span class="toggle__label">
								<span class="toggle__text"> 쿠폰강의</span>
							  </span>
							</label>
							</div>
						</li>
						
					</ul>
				</div>
			</div>

				<?php for($j = 0;$j < 5;$j += 1) { 
					$active = '';
					if($j == 0) $active = 'active';
				?>
				<div class="box_style_2 <?php echo $active?>">
				<h4><a href="#" class="box_cate_tit <?php echo $active?>">데이터 사이언스<?php echo $j?></a></h4>				
				<div class="box_category <?php echo $active?>">
				<ul class="submenu-col">
					<li><a href="" class="active">머신러닝</a></li>
					<?php for($i = 0;$i < 5;$i+= 1) { ?>
					<li><a href="#" class="">파이썬파이썬</a></li>
					<?php } ?>
				</ul>
				</div>
				</div>
				<?php } ?>
				
				
			</aside>

			<div class="col-lg-9 col-md-8 col-sm-8 sub-list">
				<div class="row">
					<?php
					for($i = 0;$i < 16;$i += 1) {
					?>
					<div class="col-lg-3 col-md-6 list-item">
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
				<div class="row text-center btn_more">
					
						<a href="#" style="margin:30px auto">더 보기</a>
					
				</div>

			</div>
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
</script>
</section>

<?php
include_once(G5_THEME_PATH.'/tail.php');
?>