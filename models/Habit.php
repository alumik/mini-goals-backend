<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "habit".
 *
 * @property int $id
 * @property string $name
 * @property int $is_private
 * @property int $type
 * @property int $icon
 * @property string $color
 * @property string $description
 *
 * @property HabitIcon $icon0
 * @property HabitType $type0
 * @property HabitUser[] $habitUsers
 * @property User[] $users
 */
class Habit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'habit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'icon'], 'required'],
            [['is_private', 'type', 'icon'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['color'], 'string', 'max' => 7],
            [['icon'], 'exist', 'skipOnError' => true, 'targetClass' => HabitIcon::className(), 'targetAttribute' => ['icon' => 'id']],
            [['type'], 'exist', 'skipOnError' => true, 'targetClass' => HabitType::className(), 'targetAttribute' => ['type' => 'id']],
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
            'is_private' => 'Is Private',
            'type' => 'Type',
            'icon' => 'Icon',
            'color' => 'Color',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIcon0()
    {
        return $this->hasOne(HabitIcon::className(), ['id' => 'icon']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType0()
    {
        return $this->hasOne(HabitType::className(), ['id' => 'type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHabitUsers()
    {
        return $this->hasMany(HabitUser::className(), ['id_habit' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'id_user'])->viaTable('habit_user', ['id_habit' => 'id']);
    }
}
