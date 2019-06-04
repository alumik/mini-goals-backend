<?php

namespace app\controllers\api\v1;

use app\models\TaskList;
use app\models\WxUser;
use Yii;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\Response;

class TaskController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * 添加任务列表
     */
    public function actionCreate()
    {
        $param = Yii::$app->request->post();

        $user = WxUser::findOne(['openid' => $param['openid']]);
        $user->pushTaskList(TaskList::create($param['content']));
    }
}
