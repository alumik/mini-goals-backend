<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "habit_like".
 *
 * @property int $id_habit_user
 * @property int $id_user
 * @property string $date
 *
 * @property HabitUser $habitUser
 * @property User $user
 */
class HabitLike extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'habit_like';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_habit_user', 'id_user', 'date'], 'required'],
            [['id_habit_user', 'id_user'], 'integer'],
            [['date'], 'safe'],
            [['id_habit_user', 'id_user', 'date'], 'unique', 'targetAttribute' => ['id_habit_user', 'id_user', 'date']],
            [['id_habit_user'], 'exist', 'skipOnError' => true, 'targetClass' => HabitUser::className(), 'targetAttribute' => ['id_habit_user' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_habit_user' => 'Id Habit User',
            'id_user' => 'Id User',
            'date' => 'Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHabitUser()
    {
        return $this->hasOne(HabitUser::className(), ['id' => 'id_habit_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
}
