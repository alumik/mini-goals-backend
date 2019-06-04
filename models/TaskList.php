<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task_list".
 *
 * @property int $id
 * @property string $name
 * @property int $archived
 * @property int $id_prev
 * @property int $id_next
 *
 * @property Task[] $tasks
 * @property TaskList $prev
 * @property TaskList $taskList
 * @property TaskList $next
 * @property TaskList $taskList0
 * @property TaskListTaskLabelRelation[] $taskListTaskLabelRelations
 * @property TaskLabel[] $taskLabels
 * @property User $user
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
            [['name'], 'required'],
            [['archived', 'id_prev', 'id_next'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['id_prev'], 'unique'],
            [['id_next'], 'unique'],
            [['id_prev'], 'exist', 'skipOnError' => true, 'targetClass' => TaskList::className(), 'targetAttribute' => ['id_prev' => 'id']],
            [['id_next'], 'exist', 'skipOnError' => true, 'targetClass' => TaskList::className(), 'targetAttribute' => ['id_next' => 'id']],
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
            'archived' => 'Archived',
            'id_prev' => 'Id Prev',
            'id_next' => 'Id Next',
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
    public function getPrev()
    {
        return $this->hasOne(TaskList::className(), ['id' => 'id_prev']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskList()
    {
        return $this->hasOne(TaskList::className(), ['id_prev' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNext()
    {
        return $this->hasOne(TaskList::className(), ['id' => 'id_next']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskList0()
    {
        return $this->hasOne(TaskList::className(), ['id_next' => 'id']);
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
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id_task_lists' => 'id']);
    }
}
