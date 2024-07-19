<?php

namespace app\components;

use app\models\User;
use Yii;
use yii\web\UnauthorizedHttpException;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Parser;

class TokenGenerator
{
    private $user;
    private $ip_address;
    private $user_agent;

    public function __construct($user, $ipAddress, $userAgent)
    {
        $this->user = $user;
        $this->ip_address = $ipAddress;
        $this->user_agent = $userAgent;
    }

    public function generateTokens()
    {
        $params = Yii::$app->params;
        $accessToken = $this->generateAccessToken($params);
        $refreshToken = $this->generateRefreshToken($params);

        if ($this->saveRefreshToken($refreshToken, $params['jwt']['refresh_expire'])) {
            return (string) $accessToken;
        } else {
            return false;
        }
    }

    public function refreshTokens()
    {
        $refreshToken = Yii::$app->request->cookies->getValue('refreshToken');

        if (!$refreshToken) {
            Yii::$app->response->statusCode = 401;
            return ['status' => 'error', 'message' => 'Refresh token not found'];
        }

        if (TokenTools::validateToken($refreshToken)) {
            return $this->generateTokens();
        }

        Yii::$app->response->statusCode = 401;
        return ['status' => 'error', 'message' => 'Invalid or expired refresh token'];
    }

    private function generateAccessToken($params)
    {
        $jwtBuilder = new Builder();
        $signer = new Sha256();

        $accessToken = $jwtBuilder
            ->setIssuer($params['jwt']['issuer'])
            ->setAudience($params['jwt']['audience'])
            ->setId($params['jwt']['id'], true)
            ->setIssuedAt(time())
            ->setExpiration(time() + $params['jwt']['access_expire'])
            ->set('user_id', $this->user->id)
            ->sign($signer, Yii::$app->params['jwt']['key'])
            ->getToken();

        return $accessToken;
    }

    private function generateRefreshToken($params)
    {
        $jwtBuilder = new Builder();
        $signer = new Sha256();

        $refreshToken = $jwtBuilder
            ->setIssuer($params['jwt']['issuer'])
            ->setAudience($params['jwt']['audience'])
            ->setId($params['jwt']['id'], true)
            ->setIssuedAt(time())
            ->setExpiration(time() + $params['jwt']['refresh_expire'])
            ->set('user_id', $this->user->id)
            ->sign($signer, Yii::$app->params['jwt']['key'])
            ->getToken();

        return $refreshToken;
    }

    private function saveRefreshToken($tokenString, $expiration_time)
    {
        $cookie = new \yii\web\Cookie([
            'name' => 'refreshToken',
            'value' => (string) $tokenString,
            'expire' => time() + $expiration_time,
            'httpOnly' => true, // С помощью этого параметра обеспечивается доступ к cookie только через HTTP протокол
            'secure' => false, // Используйте secure flag для безопасности (если используете HTTPS)
        ]);

        Yii::$app->response->cookies->add($cookie);

        return true;
    }
}
