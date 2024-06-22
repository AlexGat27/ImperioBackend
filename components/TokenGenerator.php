<?php

namespace app\components;

use app\models\User;
use app\models\UserRefreshTokens;
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
        $model = UserRefreshTokens::findOne(['user_id' => $this->user->id, 'user_agent' => $this->user_agent, 'ip' => $this->ip_address]);
        if (!$model) {
            throw new UnauthorizedHttpException('Refresh token not found');
        }
        $refreshToken = (string) $model->token;

        if (!TokenTools::validateToken($refreshToken)) {
            return $this->generateTokens();
        }

        return false;
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
            ->set('role_id', $this->user->role_id)
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
            ->sign($signer, Yii::$app->params['jwt']['key'])
            ->getToken();

        return $refreshToken;
    }

    private function saveRefreshToken($tokenString, $expiration_time)
    {
        $model = UserRefreshTokens::findOne(['user_id' => $this->user->id, 'user_agent' => $this->user_agent, 'ip' => $this->ip_address]);
        if (!$model) {
            $model = new UserRefreshTokens();
        }
        $model->user_id = $this->user->id;
        $model->token = (string) $tokenString;
        $model->ip = $this->ip_address;
        $model->user_agent = $this->user_agent;

        $expirationDate = new \DateTime();
        $expirationDate->setTimestamp(time() + $expiration_time);
        $model->expiration_date = $expirationDate->format('Y-m-d H:i:s');

        return $model->save();
    }
}
