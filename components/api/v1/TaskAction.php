<?php

namespace app\components\api\v1;

use app\models\Task;
use app\models\TaskList;
use app\models\WxUser;
use Yii;
use yii\base\Action;
use yii\db\StaleObjectException;

class TaskAction extends Action
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        if (Yii::$app->request->isGet) {
            $param = Yii::$app->request->get();

            $user = WxUser::findOne(['openid' => $param['openid']]);
            $task_list = TaskList::findOne($param['id_task_list']);

            if ($task_list->id_user == $user->id) {
                $unfinished_tasks = $task_list->getTasks(false)->all();
                $finished_tasks = $task_list->getTasks(true)->all();
                return [
                    'unfinished' => $unfinished_tasks,
                    'finished' => $finished_tasks,
                ];
            }


        } else if (Yii::$app->request->isPost) {
            $param = Yii::$app->request->post();

            $user = WxUser::findOne(['openid' => $param['openid']]);
            $task_list = TaskList::findOne($param['content']['id_task_list']);

            if ($task_list->id_user == $user->id) {
                $task = new Task();
                $task->id_task_list = $task_list->id;
                $task->content = $param['content']['content'];
                $task->save();
            }


        } else if (Yii::$app->request->isPatch) {
            $param = Yii::$app->request->bodyParams;
            $user = WxUser::findOne(['openid' => $param['openid']]);
            $task = Task::findOne($param['content']['id']);

            if ($task->taskList->id_user == $user->id) {
                $task->setAttributes($param['content']);
                $task->save();
            }


        } else if (Yii::$app->request->isDelete) {
            $param = Yii::$app->request->post();
            $user = WxUser::findOne(['openid' => $param['openid']]);
            $task = Task::findOne($param['content']['id']);

            if ($task->taskList->id_user == $user->id) {
                try {
                    $task->delete();
                } catch (StaleObjectException $e) {
                } catch (\Throwable $e) {
                }
            }
        }


        return null;
    }
}
