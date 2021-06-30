<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_SKIN_URL.'/style.css">', 0);
?>

<?php
$max_width = $max_height = 0;
$main_banners = array();

for ($i=0; $row=sql_fetch_array($result); $i++)
{
    $main_banners[] = $row;

    //print_r2($row);
    // 테두리 있는지
    $bn_border  = ($row['bn_border']) ? ' class="sbn_border"' : '';;
    // 새창 띄우기인지
    $bn_new_win = ($row['bn_new_win']) ? ' target="_blank"' : '';

    $bimg = G5_DATA_PATH.'/banner/'.$row['bn_id'];
    if ($row['bn_alt'])
    {
        $banner = '';

		$bn_alt = explode("||",$row['bn_alt']);
		$bimg_str = "";
		if (file_exists($bimg) && $row['bn_id']) {
			$bimg_str = G5_DATA_URL.'/banner/'.$row['bn_id'];
		}

		echo '<div class="swiper-slide main_top_banner" style="background-image:url('.$bimg_str.');">'.PHP_EOL;
		if ($row['bn_url'][0] == '#')
            $banner .= '<a href="'.$row['bn_url'].'" class="main_top_banner_url">';
        else if ($row['bn_url']) {
            $banner .= '<a href="'.G5_SHOP_URL.'/bannerhit.php?bn_id='.$row['bn_id'].'"'.$bn_new_win.' class="main_top_banner_url">';
        }
		echo $banner;
		//echo $banner.'<div class="title" data-swiper-parallax="-300">'.$bn_alt[0].'</div><div class="subtitle" data-swiper-parallax="-200">'.$bn_alt[1].'</div>';


		if($banner)
            echo '</a>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
    }
}
?>