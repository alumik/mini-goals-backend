<?php

namespace app\components\api\v1;

use app\models\TaskLabel;
use app\models\TaskList;
use app\models\WxUser;
use Yii;
use yii\base\Action;
use yii\db\StaleObjectException;

class TaskLabelAction extends Action
{
    /**
     * @return TaskLabel[]|null
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function run()
    {
        $session_id = Yii::$app->request->headers['Session-ID'];
        $openid = Yii::$app->cache->get($session_id);
        $user = WxUser::findOne(['openid' => $openid]);

        if (Yii::$app->request->isGet) {
            return $user->taskLabels;

        } else if (Yii::$app->request->isPost) {
            $param = Yii::$app->request->post();

            $task_list = TaskList::findOne($param['id_task_list']);
            if ($task_list->id_user == $user->id) {
                $task_list->unlinkAll('taskLabels', true);
                $task_list->addLabels($user, $param['labels']);
                TaskLabel::clean($user);
            }
        }

        return null;
    }
}
