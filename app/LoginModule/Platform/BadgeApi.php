<?php

namespace App\LoginModule\Platform;

use App\Badge;

class BadgeApi {

    static function post($url, $request) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request));
        $res = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($res, true);
        return json_last_error() === JSON_ERROR_NONE ? $res : false;
    }


    static function verify($url, $code) {
        $request = [
            'action' => 'verifyCode',
            'code' => $code
        ];
        $res = self::post($url, $request);
        if($res && isset($res['userInfos'])) {
            return [
                'login' => array_get($res['userInfos'], 'sLogin'),
                'email' => array_get($res['userInfos'], 'sEmail'),
                'first_name' => array_get($res['userInfos'], 'sFirstName'),
                'last_name' => array_get($res['userInfos'], 'sLastName'),
                'student_id' => array_get($res['userInfos'], 'sStudentId'),
                'gender' => array_get($res['userInfos'], 'sSex'),
            ];
        }
        return false;
    }


    static function update($url, $code, $user_id) {
        $post_data = [
            'action' => 'updateInfos',
            'code' => $code,
            'idUser' => $user_id
        ];
        $res = self::post($badgeUrl, $post_data);
        return $res && $res['success'];
    }


    static function remove($url, $code) {
        $request = [
            'action' => 'removeByCode',
            'code' => $code
        ];
        $res = self::post($url, $request);
        return $res && $res['success'];
    }

}