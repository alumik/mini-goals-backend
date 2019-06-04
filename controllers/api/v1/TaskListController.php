<?php

namespace app\controllers\api\v1;

use app\models\TaskList;
use app\models\WxUser;
use Yii;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\Response;

class TaskListController extends Controller
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
        $task_list = new TaskList();
        $task_list->setAttributes($param['content']);
        $user->pushTaskList($task_list);
    }

    /**
     * 更新任务列表
     */
    public function actionUpdate()
    {
        $param = Yii::$app->request->post();

        $user = WxUser::findOne(['openid' => $param['openid']]);
        $task_lists = $param['content'];
        foreach ($task_lists as $task_list) {
            $model = TaskList::findOne($task_list['id']);
            if ($model->id_user == $user->id) {
                $model->setAttributes($task_list);
                $model->save();
            }
        }
    }
}
