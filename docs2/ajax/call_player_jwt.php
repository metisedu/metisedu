<?php
include_once("./_common.php");

/**
 * base64_urlencode
 *
 * @param string $str
 * @return string
 */
function base64_urlencode($str) {
    return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
}

/**
 * jwt_encode
 *
 * @param array $payload
 * @param string $key
 * @return string
 */
function jwt_encode($payload, $key) {
    $jwtHead = base64_urlencode(json_encode(array('typ' => 'JWT', 'alg' => 'HS256')));
    $jsonPayload = base64_urlencode(json_encode($payload));
    $signature = base64_urlencode(hash_hmac('SHA256', $jwtHead . '.' . $jsonPayload, $key, true));

    return $jwtHead . '.' . $jsonPayload . '.' . $signature;
}

if(preg_match("#^https?://.+#", $_POST['im_key']) || preg_match("#^http?://.+#", $_POST['im_key'])){ // 콜러스 이외 URL 있는 영상
	$webTokenURL = $_POST['im_key'];
}else{
	$securityKey = 'hancomacademy';
	$customKey = '89f335b3e895c3727b6636087eb6731dd2196ba9f5550e717449d37ac277d650';
	$mediaContentKey = ($_POST['im_key'])? $_POST['im_key']:'lldalInp';

	$it_id = empty($_POST['it_id'])? '':$_POST['it_id'];
	$im_id = empty($_POST['im_id'])? '':$_POST['im_id'];
	$clientUserId = "|".$member['mb_id']."|".$it_id."|".$im_id."|";		// 사이트 USER ID
	$expireTime = 7200; // 120 min
	$mediaItems = array(
		array(
			'media_content_key' => $mediaContentKey,
		),
	//    array(
	//        'media_content_key' => $otherMediaContentKey,
	//        'intr' => true,
	//        'is_seekable' => true,
	//    ),
	);

	$payload = array(
		'mc' => array(),
		'cuid' => $clientUserId,
		'expt' => time() + $expireTime,
	);

	foreach ($mediaItems as $mediaItem) {
		$mcClaim = array();
		$mcClaim['mckey'] = $mediaItem['media_content_key'];
	//    $mcClaim['mcpf'] = $mediaProfileKey;
	//    $mcClaim['intr'] = (int)$mediaItem['is_intro'];
	//    $mcClaim['seek'] = (int)$mediaItem['is_seekable'];
	//    $mcClaim['seekable_end'] = $seekableEnd;
	//    $mcClaim['disable_playrate'] = (int)$disablePlayrate;
		$payload['mc'][] = $mcClaim;
	}

	$jwtToken = jwt_encode($payload, $securityKey);

	$webTokenURL = 'http://v.kr.kollus.com/s?jwt=' . $jwtToken . '&custom_key=' . $customKey . '&autoplay';
}
echo $webTokenURL;
?>