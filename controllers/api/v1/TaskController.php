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
     * 获取任务列表
     *
     * @param $openid
     * @param $archived
     * @return TaskList[]
     */
    public function actionIndex($openid, $archived)
    {
        $user = WxUser::findOne(['openid' => $openid]);
        return $user->getTaskLists($archived);
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

    /**
     * 切换任务列表归档状态
     */
    public function actionArchive()
    {
        $param = Yii::$app->request->post();

        $user = WxUser::findOne(['openid' => $param['openid']]);
        $task_list = TaskList::findOne($param['id_task_list']);
        if ($task_list->id_user == $user->id) {
            $task_list->toggleArchived();
        }
    }
}
