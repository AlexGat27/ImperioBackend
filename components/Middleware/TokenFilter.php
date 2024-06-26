<?php

namespace app\components\Middleware;

use app\components\TokenGenerator;
use app\models\User;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use Yii;
use yii\base\ActionFilter;
use yii\web\UnauthorizedHttpException;

class TokenFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        $authHeader = Yii::$app->request->getHeaders()->get('Authorization');

        if ($authHeader && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
            $token = $matches[1];

            try {
                $parsedToken = (new Parser())->parse((string) $token);
                $signer = new Sha256();
                $key = Yii::$app->params['jwt']['key'];

                // Проверка подписи токена
                if (!$parsedToken->verify($signer, $key)) {
                    throw new UnauthorizedHttpException('Invalid token signature');
                }

                // Проверка срока действия токена
                $data = new ValidationData();
                $data->setIssuer(Yii::$app->params['jwt']['issuer']);
                $data->setAudience(Yii::$app->params['jwt']['audience']);
                $data->setId(Yii::$app->params['jwt']['id']);

                if (!$parsedToken->validate($data)) {
                    throw new UnauthorizedHttpException('Token validation failed');
                }
                $user_id = $parsedToken->getClaim('user_id');
                $user = User::findOne($user_id);
                if ($user) {
                    $ipAddress = Yii::$app->request->userIP;
                    $userAgent = Yii::$app->request->userAgent;
                    if ($parsedToken->isExpired()) {
                        $tokenGenerator = new TokenGenerator($user, $ipAddress, $userAgent);
                        $newToken = $tokenGenerator->refreshTokens();
                        if ($newToken) {
                            Yii::$app->response->headers->set('Authorization', 'Bearer ' . $newToken);
                        }
                    }
                    Yii::$app->user->login($user);
                }
            } catch (\Exception $e) {
                throw new UnauthorizedHttpException($e->getMessage());
            }
        } else {
            throw new UnauthorizedHttpException('No token provided');
        }

        return parent::beforeAction($action);
    }
}
