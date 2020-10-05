<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $openid
 * @property TaskLabel[] $taskLabels
 */
class WxUser extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['openid', 'required'],
            ['openid', 'string', 'max' => 255],
            ['openid', 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openid' => 'Openid',
        ];
    }

    /**
     * 获取用户创建的标签
     *
     * @return ActiveQuery
     */
    public function getTaskLabels()
    {
        return $this->hasMany(TaskLabel::class, ['id_user' => 'id']);
    }

    /**
     * 获取任务列表
     *
     * @param $archived
     * @param $name
     * @param $label
     * @return ActiveQuery
     */
    public function getTaskLists($archived, $name, $label)
    {
        $query = TaskList::find()
            ->where([
                'task_list.id_user' => $this->id,
                'task_list.archived' => $archived,
            ])
            ->andWhere(['like', 'task_list.name', $name])
            ->orderBy(['order' => SORT_DESC]);
        if (intval($label) != 0) {
            $query = $query
                ->joinWith('taskLabels')
                ->andWhere(['task_label.id' => $label]);
        }

        return $query;
    }

    /**
     * 获取当前可用的任务列表顺序编号
     *
     * @return int
     */
    public function getTaskListOrder()
    {
        $task_list = $this->hasOne(TaskList::class, ['id_user' => 'id'])
            ->orderBy(['order' => SORT_DESC])
            ->one();
        /* @var $task_list TaskList */
        if ($task_list) {
            return $task_list->order + 1;
        }
        return 0;
    }

    /**
     * 添加任务列表
     *
     * @param $task_list TaskList
     */
    public function pushTaskList($task_list)
    {
        $task_list->order = $this->getTaskListOrder();
        $task_list->id_user = $this->id;
        $task_list->save();
    }
}
