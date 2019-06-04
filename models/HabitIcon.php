<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "habit_icon".
 *
 * @property int $id
 * @property string $class
 *
 * @property Habit[] $habits
 */
class HabitIcon extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'habit_icon';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['class'], 'required'],
            [['class'], 'string', 'max' => 255],
            [['class'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class' => 'Class',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHabits()
    {
        return $this->hasMany(Habit::className(), ['icon' => 'id']);
    }
}
