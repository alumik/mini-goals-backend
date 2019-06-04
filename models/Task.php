<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property int $id_task_list
 * @property string $content
 * @property int $finished
 *
 * @property TaskList $taskList
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_task_list', 'content'], 'required'],
            [['id_task_list', 'finished'], 'integer'],
            [['content'], 'string'],
            [['id_task_list'], 'exist', 'skipOnError' => true, 'targetClass' => TaskList::className(), 'targetAttribute' => ['id_task_list' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_task_list' => 'Id Task List',
            'content' => 'Content',
            'finished' => 'Finished',
        ];
    }

    /**
     * 获取任务列表
     *
     * @return ActiveQuery
     */
    public function getTaskList()
    {
        return $this->hasOne(TaskList::className(), ['id' => 'id_task_list']);
    }
}
