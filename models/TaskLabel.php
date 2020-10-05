<?php

namespace app\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

/**
 * This is the model class for table "task_label".
 *
 * @property int $id
 * @property int $id_user
 * @property string $name
 *
 * @property WxUser $user
 * @property TaskListTaskLabelRelation[] $taskListTaskLabelRelations
 * @property TaskList[] $taskLists
 */
class TaskLabel extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_label';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'name'], 'required'],
            [['id_user'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['id_user', 'name'], 'unique', 'targetAttribute' => ['id_user', 'name']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => WxUser::class, 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Id User',
            'name' => 'Name',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(WxUser::class, ['id' => 'id_user']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTaskListTaskLabelRelations()
    {
        return $this->hasMany(TaskListTaskLabelRelation::class, ['id_task_label' => 'id']);
    }

    /**
     * 获取任务列表
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getTaskLists()
    {
        return $this->hasMany(TaskList::class, ['id' => 'id_task_list'])->viaTable('task_list_task_label_relation', ['id_task_label' => 'id']);
    }

    /**
     * 删除无主标签
     *
     * @param $user WxUser
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public static function clean($user)
    {
        $labels = $user->taskLabels;
        foreach ($labels as $label) {
            if (!$label->taskLists) {
                $label->delete();
            }
        }
    }
}
