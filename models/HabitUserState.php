<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "habit_user_state".
 *
 * @property int $id
 * @property string $name
 *
 * @property HabitUser[] $habitUsers
 */
class HabitUserState extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'habit_user_state';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHabitUsers()
    {
        return $this->hasMany(HabitUser::className(), ['state' => 'id']);
    }
}
