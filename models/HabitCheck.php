<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "habit_check".
 *
 * @property int $id_habit_user
 * @property string $date
 *
 * @property HabitUser $habitUser
 */
class HabitCheck extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'habit_check';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_habit_user', 'date'], 'required'],
            [['id_habit_user'], 'integer'],
            [['date'], 'safe'],
            [['id_habit_user', 'date'], 'unique', 'targetAttribute' => ['id_habit_user', 'date']],
            [['id_habit_user'], 'exist', 'skipOnError' => true, 'targetClass' => HabitUser::className(), 'targetAttribute' => ['id_habit_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_habit_user' => 'Id Habit User',
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
}
