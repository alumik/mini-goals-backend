<?php

namespace app\components\api\v1;

use app\models\WxUser;
use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\helpers\Json;

class UserAction extends Action
{
    /**
     * @return array|null
     * @throws Exception
     */
    public function run()
    {
        if (Yii::$app->request->isPost) {
            $param = Yii::$app->request->post();

            $wx_appid = Yii::$app->params['wx_appid'];
            $wx_secret = Yii::$app->params['wx_secret'];
            $url = 'https://api.weixin.qq.com/sns/jscode2session?grant_type=authorization_code'
                . '&appid=' . $wx_appid
                . '&secret=' . $wx_secret
                . '&js_code=' . $param['code'];

            $openid = Json::decode((file_get_contents($url)))['openid'];
            $user = WxUser::findOne(['openid' => $openid]);
            if (!$user) {
                $user = new WxUser();
                $user->openid = $openid;
                $user->save();
            }

            do {
                $session_id = Yii::$app->security->generateRandomString(32);
            } while (Yii::$app->cache->exists($session_id));
            Yii::$app->cache->set($session_id, $openid, 7200);
            return ['sessionId' => $session_id];
        }

        return null;
    }
}
