<?php

namespace app\components\api\v1;

use app\models\Task;
use app\models\TaskLabel;
use app\models\TaskList;
use app\models\WxUser;
use Yii;
use yii\base\Action;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

class TaskListAction extends Action
{
    /**
     * @return array|ActiveRecord[]|null
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function run()
    {
        if (Yii::$app->request->isGet) {
            $param = Yii::$app->request->get();

            $user = WxUser::findOne(['openid' => $param['openid']]);

            if (isset($param['id_task_list'])) {
                $task_lists = [TaskList::findOne($param['id_task_list'])];
            } else {
                $task_lists = $user->getTaskLists($param['archived'], $param['name'])->all();
            }

            foreach ($task_lists as &$task_list) {
                /* @var $task_list TaskList */
                $task_list->labels = $task_list->taskLabels;
                $task_list->grouped_tasks['unfinished']['count'] = $task_list->getTasks(false)->count();
                $task_list->grouped_tasks['unfinished']['content'] = $task_list->getTasks(false)->all();
                $task_list->grouped_tasks['finished']['count'] = $task_list->getTasks(true)->count();
                $task_list->grouped_tasks['finished']['content'] = $task_list->getTasks(true)->all();
            }
            return isset($param['id_task_list']) ? $task_lists[0] : $task_lists;


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


        } else if (Yii::$app->request->isDelete) {
            $param = Yii::$app->request->bodyParams;

            $user = WxUser::findOne(['openid' => $param['openid']]);
            $task_list = TaskList::findOne($param['content']['id']);

            if ($task_list->id_user == $user->id) {
                Task::deleteAll(['id_task_list' => $task_list->id]);
                $task_list->unlinkAll('taskLabels', true);
                $task_list->delete();
                TaskLabel::clean($user);
            }


        }
        return null;
    }
}
