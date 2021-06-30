<?php
include_once('/home/ubuntu/www/application/views/kollus/kollus_func_v2.php');

// SET values
$access_token = GN_ACCESS_TOKEN;
$security_key = GN_SECURITY_KEY;
$custom_key = GN_CUSTOM_KEY;
$expire_time = GN_EXPIRE_TIME;

$im_width = ($data['width'])? $data['width']:"680";
$im_height = ($data['height'])? $data['height']:"400";
$media_content_key = ($data['im_key'])? $data['im_key']:'uIBE93N1';	// 채널 내의 media_contents_key
$media_profile_key = '';			// media_profile 선택 (ex : catenoid-pc1-high, catenoid-tablet2-high, catenoid-mobile1-normal ...)

$it_id = empty($data['it_id'])? '':$data['it_id'];
$im_id = empty($data['im_id'])? '':$data['im_id'];
$client_user_id = "|".$member['mb_id']."|".$it_id."|".$im_id."|";		// 사이트 USER ID
//$awt_code = kollus_get_awt_code($access_token, $client_user_id, $security_key);	// audio water marking code

// set jwt head set
$JWTHead = array(
	'typ'	=> 'JWT',
	'alg'	=> 'HS256'
);
$JWTHead = base64url_encode(json_encode($JWTHead));

// set paylaod
$payload = array(
	'cuid'	=> $client_user_id,
	'expt'	=> $expire_time,
	'awtc'	=> $awt_code,
	'mc'	=> array(
		array(
			'mckey' => $media_content_key,
			'mcpf'	=> $media_profile_key
		)
	)
);
$payload = base64url_encode(json_encode($payload));

// set signature
$signature = base64url_encode(hash_hmac('SHA256', $JWTHead.'.'.$payload, $security_key, true));

// create jwt
$JWTstr = $JWTHead.'.'.$payload.'.'.$signature;

if($media_content_key == 'uIBE93N1') echo"<p>등록된 키값이 없어 임시 영상이 재생됩니다.";
?>
<iframe width="<?php echo $im_width; ?>" height="<?php echo $im_height; ?>" frameborder="0" src="http://v.kr.kollus.com/s?jwt=<?=$JWTstr?>&custom_key=<?=$custom_key?>" frameborder="0" allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe>