<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $openid
 * @property string $name
 * @property int $id_task_lists
 *
 * @property HabitLike[] $habitLikes
 * @property HabitUser[] $habitUsers
 * @property Habit[] $habits
 * @property TaskLabel[] $taskLabels
 * @property TaskList $taskLists
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
            [['id_task_lists'], 'integer'],
            [['openid', 'name'], 'string', 'max' => 255],
            [['openid'], 'unique'],
            [['id_task_lists'], 'unique'],
            [['id_task_lists'], 'exist', 'skipOnError' => true, 'targetClass' => TaskList::className(), 'targetAttribute' => ['id_task_lists' => 'id']],
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
            'id_task_lists' => 'Id Task Lists',
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
     * @return \yii\db\ActiveQuery
     */
    public function getTaskLists()
    {
        return $this->hasOne(TaskList::className(), ['id' => 'id_task_lists']);
    }
}
