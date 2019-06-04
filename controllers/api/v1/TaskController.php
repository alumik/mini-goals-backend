<?php

namespace app\controllers\api\v1;

use app\models\Task;
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
     * 获取任务
     *
     * @param $openid
     * @param $id_task_list
     * @return array|null
     */
    public function actionIndex($openid, $id_task_list)
    {
        $user = WxUser::findOne(['openid' => $openid]);
        $task_list = TaskList::findOne($id_task_list);

        if ($task_list->id_user == $user->id) {
            $unfinished_tasks = $task_list->getTasks(false)->all();
            $finished_tasks = $task_list->getTasks(true)->all();
            return [
                'unfinished' => $unfinished_tasks,
                'finished' => $finished_tasks,
            ];
        }
        return null;
    }

    /**
     * 创建任务
     */
    public function actionCreate()
    {
        $param = Yii::$app->request->post();

        $user = WxUser::findOne(['openid' => $param['openid']]);
        $task_list = TaskList::findOne($param['content']['id_task_list']);

        if ($task_list->id_user == $user->id) {
            $task = new Task();
            $task->id_task_list = $task_list->id;
            $task->content = $param['content']['content'];
            $task->save();
        }
    }

    /**
     * 更新任务
     */
    public function actionUpdate()
    {
        $param = Yii::$app->request->post();
        $user = WxUser::findOne(['openid' => $param['openid']]);
        $task = Task::findOne($param['content']['id']);

        if ($task->taskList->id_user == $user->id) {
            $task->setAttributes($param['content']);
            $task->save();
        }
    }
}
