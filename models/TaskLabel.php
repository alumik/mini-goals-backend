<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task_label".
 *
 * @property int $id
 * @property int $id_user
 * @property string $name
 *
 * @property User $user
 * @property TaskListTaskLabelRelation[] $taskListTaskLabelRelations
 * @property TaskList[] $taskLists
 */
class TaskLabel extends \yii\db\ActiveRecord
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
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
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
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskListTaskLabelRelations()
    {
        return $this->hasMany(TaskListTaskLabelRelation::className(), ['id_task_label' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskLists()
    {
        return $this->hasMany(TaskList::className(), ['id' => 'id_task_list'])->viaTable('task_list_task_label_relation', ['id_task_label' => 'id']);
    }
}
