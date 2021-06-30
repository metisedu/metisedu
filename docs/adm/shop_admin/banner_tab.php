<div id="bn_tab">
	<a href="/adm/shop_admin/banner_main.php"><button type="button" class="on">사이트 메인</button></a>
	<a href="/adm/shop_admin/banner_category_list.php"><button type="button">강좌 리스트</button></a>
	<a href="/adm/newwinlist.php"><button type="button">팝업 배너</button></a>
</div>
<div class="local_ov01 local_ov">
	<button type="button" class="chart_tab <?php if($PHP_SELF == '/adm/shop_admin/banner_main.php') echo"on"; ?>" onclick="location.href='./banner_main.php';">메인배너</button>
	<button type="button" class="chart_tab <?php if($PHP_SELF == '/adm/shop_admin/banner_main_cate.php') echo"on"; ?>" onclick="location.href='./banner_main_cate.php';">메인 카테고리</button>
	<button type="button" class="chart_tab <?php if($PHP_SELF == '/adm/shop_admin/banner_main_review.php') echo"on"; ?>" onclick="location.href='./banner_main_review.php';">수강후기</button>
	<!--<button type="button" class="chart_tab <?php if($PHP_SELF == '/adm/shop_admin/banner_main_bottom.php') echo"on"; ?>" onclick="location.href='./banner_main_bottom.php';">하단배너</button>-->
	<button type="button" class="chart_tab <?php if($PHP_SELF == '/adm/shop_admin/banner_main_movie.php') echo"on"; ?>" onclick="location.href='./banner_main_movie.php';">하단영상</button>
</div>