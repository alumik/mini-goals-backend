<?php

namespace app\components\api\v1;

use app\models\TaskList;
use app\models\WxUser;
use Yii;
use yii\base\Action;

class TaskListAction extends Action
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        if (Yii::$app->request->isGet) {
            $param = Yii::$app->request->get();

            $user = WxUser::findOne(['openid' => $param['openid']]);
            $task_lists = $user->getTaskLists($param['archived'], $param['name'])->all();
            foreach ($task_lists as &$task_list) {
                /* @var $task_list TaskList */
                $task_list->labels = $task_list->taskLabels;
            }
            return $task_lists;


        } else if (Yii::$app->request->isPost) {
            $param = Yii::$app->request->post();

            $user = WxUser::findOne(['openid' => $param['openid']]);
            $task_list = new TaskList();
            $task_list->setAttributes($param['content']);
            $user->pushTaskList($task_list);


        } else if (Yii::$app->request->isPatch) {
            $param = Yii::$app->request->bodyParams;

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


        return null;
    }
}
