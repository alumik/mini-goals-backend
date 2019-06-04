<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $openid
 * @property string $name
 *
 * @property HabitLike[] $habitLikes
 * @property HabitUser[] $habitUsers
 * @property Habit[] $habits
 * @property TaskLabel[] $taskLabels
 */
class WxUser extends \yii\db\ActiveRecord
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
            [['openid', 'name'], 'required'],
            [['openid', 'name'], 'string', 'max' => 255],
            [['openid'], 'unique'],
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
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHabitLikes()
    {
        return $this->hasMany(HabitLike::className(), ['id_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHabitUsers()
    {
        return $this->hasMany(HabitUser::className(), ['id_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHabits()
    {
        return $this->hasMany(Habit::className(), ['id' => 'id_habit'])->viaTable('habit_user', ['id_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskLabels()
    {
        return $this->hasMany(TaskLabel::className(), ['id_user' => 'id']);
    }

    /**
     * 获取任务列表
     *
     * @param $archived
     * @return ActiveQuery
     */
    public function getTaskLists($archived)
    {
        return TaskList::find()
            ->where([
                'id_user' => $this->id,
                'archived' => $archived,
            ])
            ->orderBy(['order' => SORT_DESC]);
    }

    /**
     * 获取当前可用的任务列表顺序编号
     *
     * @return int
     */
    public function getTaskListOrder()
    {
        $task_list = $this->hasOne(TaskList::className(), ['id_user' => 'id'])
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
