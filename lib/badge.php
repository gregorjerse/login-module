<?php

require_once __DIR__.'/connect.php';

function verifyCode($badgeUrl, $code) {
	$code = trim($code);

	$post_request = ['action' => 'verifyCode', 'code' => $code];

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $badgeUrl);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_request));

	$server_output = curl_exec ($ch);

	curl_close ($ch);

	try {
	   $server_output = json_decode($server_output, true);
	} catch(Exception $e) {
	   return array('success' => false, 'error' => 'cannot read badge json return: '.$e->getMessage());
	}

	if (!$server_output) {
		return ['success' => false, 'error' => 'error_badge_code_invalid'];	
	}
	return ['success' => true, 'userInfos' => $server_output];
}

function verifyBadge($badgeUrl, $verifInfos, $verifType) {
	if ($verifType == 'code') {
		if (!$verifInfos || !isset($verifInfos['code'])) {
			return ['success' => false, 'error' => 'missing code'];
		}
		return verifyCode($badgeUrl, $verifInfos['code']);
	} else {
		return ['success' => false, 'error' => 'unknown verif type '.$verifType];	
	}
}

function isBadgeRegistered($badgeUrl, $verifInfos, $verifType) {
	global $db;
	if ($verifType == 'code') {
		if (!$verifInfos || !isset($verifInfos['code'])) {
			return ['success' => false, 'error' => 'missing code'];
		}
		// WARNING: the serialization of $verifInfos into $jBadgeInfos *must* be unique
		//          in order to be able to perform textual comparisons in SQL
		$code = trim($verifInfos['code']);
		$jBadgeInfos = json_encode(['code' => $code], JSON_UNESCAPED_UNICODE);
		$stmt = $db->prepare("select user_badges.idUser, users.sLogin from user_badges
			join users on users.ID = user_badges.idUser
			where jBadgeInfos = :jBadgeInfos and sBadge = :badge;");
		$stmt->execute(['jBadgeInfos' => $jBadgeInfos, 'badge' => $badgeUrl]);
		$res = $stmt->fetch();
		if (!$res) {
			return ['success' => true, 'result' => false];
		}
		return ['success' => true, 'result' => $res];
	} else {
		return ['success' => false, 'error' => 'unknown verif type '.$verifType];
	}
}

function addBadge($idUser, $badge, $badgeInfos, $verifType) {
	global $db;
	if (!$idUser || !$badge) return;
	$jBadgeInfos = $badgeInfos ? json_encode(['code' => trim($badgeInfos['code'])], JSON_UNESCAPED_UNICODE) : null;
	$stmt = $db->prepare('SELECT id from `user_badges` where `idUser` = :idUser and sBadge = :badge;');
    $stmt->execute(['idUser' => $idUser, 'badge' => $badge]);
    $idBadge = $stmt->fetchColumn();
    if (!$idBadge) {
        $stmt = $db->prepare('INSERT INTO `user_badges` (`idUser`, `sBadge`, `jBadgeInfos`) VALUES (:idUser, :badge, :jBadgeInfos);');
        $stmt->execute(['idUser' => $idUser, 'badge' => $badge, 'jBadgeInfos' => $jBadgeInfos]);
    }
}

function updateBadgeInfos($idUser, $badgeUrl, $badgeInfos, $verifType) {
	if (!$idUser || !$badgeUrl || !$badgeInfos || !$verifType || !isset($badgeInfos['code'])) return ['success' => false, 'error' => 'missing argument'];
	$post_data = ['action' => 'updateInfos'];
	if ($verifType == 'code') {
		$post_data['idUser'] = $idUser;
		$post_data['code'] = $badgeInfos['code'];
	} else {
		return ['success' => false, 'error' => 'unknown verification type: '.$verifType];
	}

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $badgeUrl);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));

	$server_output = curl_exec ($ch);

	curl_close ($ch);

	try {
	   $server_output = json_decode($server_output, true);
	} catch(Exception $e) {
	   return array('success' => false, 'error' => 'cannot read badge json return: '.$e->getMessage());
	}

	if (!$server_output) {
		return ['success' => false, 'error' => 'badge/updateInfos failed! this should not happen!'];	
	}
	if (!$server_output['success']) {
		return ['success' => false, 'error' => 'badge/updateInfos failed: '.$server_output['error']];	
	}
	return $server_output;
}

function addBadgesInSession() {
	global $db;
	if (!isset($_SESSION['modules']['login']['idUser'])) {
		return;
	}
	$stmt = $db->prepare('select user_badges.sBadge, user_badges.bDoNotPossess from user_badges where idUser = :idUser;');
	$stmt->execute(['idUser' => $_SESSION['modules']['login']['idUser']]);
	$allBadges = $stmt->fetchAll();
	$aBadges = [];
	$notBadges = [];
	foreach ($allBadges as $badge) {
		if (!intval($badge['bDoNotPossess'])) {
			$aBadges[] = $badge['sBadge'];
		} else {
			$notBadges[] = $badge['sBadge'];
		}
	}
	$_SESSION['modules']['login']['aBadges'] = $aBadges;
	$_SESSION['modules']['login']['aNotBadges'] = $notBadges;
}

function verifyAndAddBadge($badgeUrl, $verifInfos, $verifType) {
	if (!isset($_SESSION['modules']['login']['idUser'])) {
		return ['success' => false, 'error' => 'adding badge on unlogged user, this should not happen!'];
	}
	$verifData = verifyBadge($badgeUrl, $verifInfos, $verifType);
	if (!$verifData['success']) {
		return $verifData;
	}
	addBadge($_SESSION['modules']['login']['idUser'], $badgeUrl, $verifInfos, $verifType);
	$infos = updateBadgeInfos($_SESSION['modules']['login']['idUser'], $badgeUrl, $verifInfos, $verifType);
	addBadgesInSession();
	return $infos;
}