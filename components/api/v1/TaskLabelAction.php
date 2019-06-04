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
        if (Yii::$app->request->isGet) {
            $param = Yii::$app->request->get();

            $user = WxUser::findOne(['openid' => $param['openid']]);

            return $user->taskLabels;

        } else if (Yii::$app->request->isPost) {
            $param = Yii::$app->request->post();

            $user = WxUser::findOne(['openid' => $param['openid']]);
            $task_list = TaskList::findOne($param['content']['id_task_list']);

            if ($task_list->id_user == $user->id) {
                $task_list->unlinkAll('taskLabels', true);
                $task_list->addLabels($user, $param['content']['labels']);
                TaskLabel::clean($user);
            }
        }

        return null;
    }
}
