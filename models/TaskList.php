<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task_list".
 *
 * @property int $id
 * @property string $name
 * @property int $id_user
 * @property int $archived
 * @property int $order
 *
 * @property Task[] $tasks
 * @property WxUser $user
 * @property TaskListTaskLabelRelation[] $taskListTaskLabelRelations
 * @property TaskLabel[] $taskLabels
 */
class TaskList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'id_user'], 'required'],
            [['id_user', 'archived', 'order'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => WxUser::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'id_user' => 'Id User',
            'archived' => 'Archived',
            'order' => 'Order',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['id_task_list' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(WxUser::className(), ['id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskListTaskLabelRelations()
    {
        return $this->hasMany(TaskListTaskLabelRelation::className(), ['id_task_list' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskLabels()
    {
        return $this->hasMany(TaskLabel::className(), ['id' => 'id_task_label'])->viaTable('task_list_task_label_relation', ['id_task_list' => 'id']);
    }

    /**
     * 新建任务列表
     *
     * @param $attributes
     * @return TaskList
     */
    public static function create($attributes)
    {
        $model = new self();
        $model->setAttributes($attributes);
        return $model;
    }
}
