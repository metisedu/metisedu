<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 쿠폰
$cp_count = 0;
$sql = " select cp_id
            from {$g5['g5_shop_coupon_table']}
            where mb_id IN ( '{$member['mb_id']}', '전체회원' )
              and cp_start <= '".G5_TIME_YMD."'
              and cp_end >= '".G5_TIME_YMD."' ";
$res = sql_query($sql);

for($k=0; $cp=sql_fetch_array($res); $k++) {
    if(!is_used_coupon($member['mb_id'], $cp['cp_id']))
        $cp_count++;
}

$act1 = $act2 = $act3 = $act4 = $act5 = $act6 = $act7 = $act8 = "";
if($PHP_SELF == '/shop/cart.php' || $PHP_SELF == '/shop/mypage.php'){
	$act1 = "active";
}elseif($PHP_SELF == '/shop/coupon.php'){
	$act2 = "active";
}else if($PHP_SELF == '/shop/orderinquiry.php' || $PHP_SELF == '/shop/refund.php'){
	$act3 = "active";
}else if($PHP_SELF == '/bbs/member_confirm.php' || $PHP_SELF == '/bbs/register_form.php'){
	$act5 = "active";
}else if($PHP_SELF == '/shop/profile.php'){
	$act6 = "active";
}else if($PHP_SELF == '/shop/reward_log.php'){
    $act7 = "active";
}else if($PHP_SELF == '/shop/equity_nft.php'){
    $act8 = "active";
}else{
	$act1 = "active";
}

$fb_url = $in_url = $yt_url = $ho_url = "javascript:void(0);";
$icon01 = $icon02 = $icon03 = $icon04 = "";
if($member['mb_fb']){
	$fb_url = (strpos($member['mb_fb'], 'https://') === false)? "https://".$member['mb_fb']:$member['mb_fb'];
	$icon01 = "active";
}
if($member['mb_in']){
	$in_url = (strpos($member['mb_in'], 'https://') === false)? "https://".$member['mb_in']:$member['mb_in'];
	$icon02 = "active";
}
if($member['mb_yt']){
	$yt_url = (strpos($member['mb_yt'], 'https://') === false)? "https://".$member['mb_yt']:$member['mb_yt'];
	$icon03 = "active";
}
if($member['mb_ho']){
	$ho_url = (strpos($member['mb_ho'], 'https://') === false)? "https://".$member['mb_ho']:$member['mb_ho'];
	$icon04 = "active";
}
?>
<style>
.box_style_4 .media {
	background:#0a0a09;padding: 37px 18px 37px 50px;
}

.my_top {
	float:unset;
}
.media-body {
	width:300px;
}
.mypage-menu {
	display: table-cell;
    width: 679px;
}
.mypage-menu ul {
	overflow:hidden;
}
.mypage-menu ul li {
	width: 33.3%;
    float: left;
	background: transparent;
    border-bottom: 0px;
}
.mypage-menu ul li a {
	color:#4a4a4a;
}
.mypage-menu ul li a.active {
    color: #edad58;
	background: transparent;
}
.my_con {
	float:unset;
	margin:0 auto;
	padding-top:52px;
}

@media screen and (max-width: 768px){
	.box_style_4 .media {
		padding: 25px 28px;
	}

	.profile_img {
		width: 80px;
		height: 80px;
		border-radius: 40px;
	}
	.profile_img img {width:100%;}
	.media-body {
		width: 200px;
		margin-top:0px;
	}
	.profile_info .profile_nick {
		margin-top: 0px;
	}
	.mypage-menu {
		border-top: 1px solid #edad58;
		width: 100%;
	}
	.mypage-menu ul li {
		width: 100%;
		padding:0px;
	}
	.mypage-menu ul li a {
		padding-left: 0px;
	}

	.my_con {
		padding-top:0px;
	}
}
</style>
<aside class="col-md-12 my_top">
	<div class="box_style_4">
		<div class="media" style="">
			<div class="profile_img pull-left">
				<?php if(file_exists($mb_img_path)) {  ?>
				<img src="<?php echo $mb_img_url ?>" class="profile_img" alt="회원이미지">
				<?php }else{  ?>
				<img src="/img/avatar_120x120.png">
				<?php } ?>
				<a href="/bbs/member_confirm.php?url=register_form.php" class="modify"></a>
			</div>
			<div class="media-body text-left profile_info">
				<div class="profile_nick">
					<?php echo $member['mb_name']; ?>
					<div class="profile_icon_wrap">
						<ul>
							<li><a href="<?php echo $fb_url; ?>" target="_blank" class="profile_icon facebook <?php echo $icon01; ?>"></a></li>
							<li><a href="<?php echo $in_url; ?>" target="_blank" class="profile_icon instagram <?php echo $icon02; ?>"></a></li>
							<li><a href="<?php echo $yt_url; ?>" target="_blank" class="profile_icon youtube <?php echo $icon03; ?>"></a></li>
							<li><a href="<?php echo $ho_url; ?>" target="_blank" class="profile_icon homepage <?php echo $icon04; ?>"></a></li>
						</ul>
					</div>
				</div>
				<span class="profile_line1"></span>
				<div class="profile_coupon">Available Coupons <a href="/shop/coupon.php" class="coupon_num"><?php echo number_format($cp_count); ?> ea</a></div>
				<a href="/bbs/logout.php" class="profile_logout">Logout</a>
			</div>
			<div class="mypage-menu">
				<ul>
					<li><a href="/shop/mypage.php#class" class="<?php echo $act1; ?>"><!-- 내 강의보기 -->My Lessons</a></li>
					<li><a href="/shop/coupon.php" class="<?php echo $act2; ?>"><!-- 쿠폰내역 -->Coupon details</a></li>
					<li><a href="/shop/orderinquiry.php" class="<?php echo $act3; ?>"><!-- 결제내역 -->Payment list</a></li>
					<li><a href="/shop/mypage.php#done" class="<?php echo $act4; ?>"><!-- 이전 수강정보 -->Finished course</a></li>
					<li><a href="/bbs/member_confirm.php?url=register_form.php" class="<?php echo $act5; ?>"><!-- 회원정보수정 -->Member information</a></li>
                    <li><a href="/shop/reward_log.php" class="<?php echo $act7; ?>"><!-- 리워드 -->Reward</a></li>
                    <li><a href="/shop/equity_nft.php" class="<?php echo $act8; ?>"><!-- NFT -->Equity NFT</a></li>
				</ul>
			</div>
		</div>
	</div>
</aside>