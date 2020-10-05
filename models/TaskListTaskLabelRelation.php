<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "task_list_task_label_relation".
 *
 * @property int $id_task_label
 * @property int $id_task_list
 *
 * @property TaskLabel $taskLabel
 * @property TaskList $taskList
 */
class TaskListTaskLabelRelation extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_list_task_label_relation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_task_label', 'id_task_list'], 'required'],
            [['id_task_label', 'id_task_list'], 'integer'],
            [['id_task_label', 'id_task_list'], 'unique', 'targetAttribute' => ['id_task_label', 'id_task_list']],
            [['id_task_label'], 'exist', 'skipOnError' => true, 'targetClass' => TaskLabel::class, 'targetAttribute' => ['id_task_label' => 'id']],
            [['id_task_list'], 'exist', 'skipOnError' => true, 'targetClass' => TaskList::class, 'targetAttribute' => ['id_task_list' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_task_label' => 'Id Task Label',
            'id_task_list' => 'Id Task List',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskLabel()
    {
        return $this->hasOne(TaskLabel::class, ['id' => 'id_task_label']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskList()
    {
        return $this->hasOne(TaskList::class, ['id' => 'id_task_list']);
    }
}
