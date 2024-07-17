<?php

namespace app\components;

use app\models\UserRefreshTokens;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use Yii;

class TokenTools
{
    public static function validateToken($tokenString)
    {
        $signer = new Sha256();

        $parsedToken = (new Parser())->parse((string) $tokenString);

        if (!$parsedToken->verify($signer, Yii::$app->params['jwt']['key'])) {
            return false;
        }

        $data = new ValidationData();
        $data->setIssuer(Yii::$app->params['jwt']['issuer']);
        $data->setAudience(Yii::$app->params['jwt']['audience']);
        $data->setId(Yii::$app->params['jwt']['id']);

        if (!$parsedToken->validate($data)) {
            return false;
        }

        if ($parsedToken->isExpired()) {
            return false;
        }

        return true;
    }
    public static function getUserId($token){
        $parsedToken = (new Parser())->parse((string) $token);
        return $parsedToken->getClaim('user_id');
    }
    public static function clearRefreshToken()
    {
        $cookies = Yii::$app->response->cookies;
        $cookies->remove('refreshToken');
    }
}