<?php

namespace app\controllers\api\v1;

use app\models\TaskLabel;
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
     * @param $name
     * @return TaskList[]
     */
    public function actionIndex($openid, $archived, $name)
    {
        $user = WxUser::findOne(['openid' => $openid]);
        $task_lists = $user->getTaskLists($archived, $name)->all();
        foreach ($task_lists as &$task_list) {
            $task_list->labels = $task_list->taskLabels;
        }
        return $task_lists;
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

    /**
     * 获取并设置任务列表标签
     *
     * @return TaskLabel[]|null
     */
    public function actionLabel()
    {
        if (Yii::$app->request->isGet) {
            $param = Yii::$app->request->get();

            $user = WxUser::findOne(['openid' => $param['openid']]);
            return $user->taskLabels;
        } else if (Yii::$app->request->isPost) {
            $param = Yii::$app->request->post();

            $user = WxUser::findOne(['openid' => $param['openid']]);
            $task_list = TaskList::findOne($param['content']['id_task_list']);

            $task_list->unlinkAll('taskLabels', true);
            foreach ($param['content']['labels'] as $label) {
                $model = TaskLabel::findOne(['id_user' => $user->id, 'name' => $label]);
                if (!$model) {
                    $model = new TaskLabel();
                    $model->id_user = $user->id;
                    $model->name = $label;
                    $model->save();
                }
                $task_list->link('taskLabels', $model);
            }
        }
        return null;
    }
}
