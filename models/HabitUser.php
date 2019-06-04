<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "habit_user".
 *
 * @property int $id
 * @property int $id_user
 * @property int $id_habit
 * @property int $state
 * @property int $share_level [0 => '不公开', 1 => '公布内容', 2 => '公布坚持情况']
 *
 * @property HabitCheck[] $habitChecks
 * @property HabitLike[] $habitLikes
 * @property Habit $habit
 * @property HabitUserState $state0
 * @property WxUser $user
 */
class HabitUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'habit_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_habit', 'state'], 'required'],
            [['id_user', 'id_habit', 'state', 'share_level'], 'integer'],
            [['id_user', 'id_habit'], 'unique', 'targetAttribute' => ['id_user', 'id_habit']],
            [['id_habit'], 'exist', 'skipOnError' => true, 'targetClass' => Habit::className(), 'targetAttribute' => ['id_habit' => 'id']],
            [['state'], 'exist', 'skipOnError' => true, 'targetClass' => HabitUserState::className(), 'targetAttribute' => ['state' => 'id']],
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
            'id_user' => 'Id User',
            'id_habit' => 'Id Habit',
            'state' => 'State',
            'share_level' => 'Share Level',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHabitChecks()
    {
        return $this->hasMany(HabitCheck::className(), ['id_habit_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHabitLikes()
    {
        return $this->hasMany(HabitLike::className(), ['id_habit_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHabit()
    {
        return $this->hasOne(Habit::className(), ['id' => 'id_habit']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState0()
    {
        return $this->hasOne(HabitUserState::className(), ['id' => 'state']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(WxUser::className(), ['id' => 'id_user']);
    }
}
