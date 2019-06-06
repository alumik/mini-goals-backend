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
     * @return array|null
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function run()
    {
        $session_id = Yii::$app->request->headers['Session-ID'];
        $openid = Yii::$app->cache->get($session_id);
        $user = null;
        if ($openid) {
            $user = WxUser::findOne(['openid' => $openid]);
        }

        if (Yii::$app->request->isPost) {
            $param = Yii::$app->request->post();

            $task_list = TaskList::findOne($param['content']['id_task_list']);
            if ($task_list->id_user == $user->id) {
                $task = new Task();
                $task->setAttributes($param['content']);
                $task->save();
            }

        } else if (Yii::$app->request->isPut) {
            $param = Yii::$app->request->bodyParams;

            $task = Task::findOne($param['content']['id']);
            if ($task->taskList->id_user == $user->id) {
                $task->setAttributes($param['content']);
                $task->save();
            }

        } else if (Yii::$app->request->isDelete) {
            $param = Yii::$app->request->get();

            $task = Task::findOne($param['id_task']);
            if ($task->taskList->id_user == $user->id) {
                $task->delete();
            }
        }

        return null;
    }
}
