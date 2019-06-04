<?php

use yii\db\Migration;

/**
 * Class m190604_103400_init_habit_module
 */
class m190604_103400_init_habit_module extends Migration
{
    private function tableHabitIcon()
    {
        $this->createTable('habit_icon', [
            'id' => $this->primaryKey(),
            'class' => $this->string()->notNull()->unique(),
        ]);
    }

    private function tableHabitType()
    {
        $this->createTable('habit_type', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
        ]);
    }

    private function tableHabit()
    {
        $this->createTable('habit', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'is_private' => $this->boolean()->notNull()->defaultValue(true),
            'type' => $this->integer()->null(),
            'icon' => $this->integer()->notNull(),
            'color' => $this->string(7)->notNull()->defaultValue('#ffffff'),
            'description' => $this->text()->null(),
        ]);

        $this->addForeignKey(
            'habit_habit_type_id_fk',
            'habit',
            'type',
            'habit_type',
            'id'
        );

        $this->addForeignKey(
            'habit_habit_icon_id_fk',
            'habit',
            'icon',
            'habit_icon',
            'id'
        );
    }

    private function tableHabitUserState()
    {
        $this->createTable('habit_user_state', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
        ]);

        $this->batchInsert(
            'habit_user_state',
            ['id', 'name'],
            [
                [1, '正常'],
                [2, '已归档'],
                [3, '已删除'],
            ]
        );
    }

    private function tableHabitUser()
    {
        $this->createTable('habit_user', [
            'id' => $this->primaryKey(),
            'id_user' => $this->integer()->notNull(),
            'id_habit' => $this->integer()->notNull(),
            'state' => $this->integer()->notNull(),
            'share_level' => $this->integer()
                ->notNull()
                ->comment("[0 => '不公开', 1 => '公布内容', 2 => '公布坚持情况']")
                ->defaultValue(0),
        ]);

        $this->addForeignKey(
            'habit_user_user_id_fk',
            'habit_user',
            'id_user',
            'user',
            'id'
        );

        $this->addForeignKey(
            'habit_user_habit_id_fk',
            'habit_user',
            'id_habit',
            'habit',
            'id'
        );

        $this->addForeignKey(
            'habit_user_habit_user_state_id_fk',
            'habit_user',
            'state',
            'habit_user_state',
            'id'
        );

        $this->createIndex(
            'habit_user_id_user_id_habit_uindex',
            'habit_user',
            ['id_user', 'id_habit'],
            true
        );
    }

    private function tableHabitLike()
    {
        $this->createTable('habit_like', [
            'id_habit_user' => $this->integer()->notNull(),
            'id_user' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
        ]);

        $this->addPrimaryKey(
            'habit_like_pk',
            'habit_like',
            ['id_habit_user', 'id_user', 'date']
        );

        $this->addForeignKey(
            'habit_like_habit_user_id_fk',
            'habit_like',
            'id_habit_user',
            'habit_user',
            'id'
        );

        $this->addForeignKey(
            'habit_like_user_id_fk',
            'habit_like',
            'id_user',
            'user',
            'id'
        );
    }

    private function tableHabitCheck()
    {
        $this->createTable('habit_check', [
            'id_habit_user' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
        ]);

        $this->addPrimaryKey(
            'habit_check_pk',
            'habit_check',
            ['id_habit_user', 'date']
        );

        $this->addForeignKey(
            'habit_check_habit_user_id_fk',
            'habit_check',
            'id_habit_user',
            'habit_user',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->tableHabitIcon();
        $this->tableHabitType();
        $this->tableHabit();
        $this->tableHabitUserState();
        $this->tableHabitUser();
        $this->tableHabitLike();
        $this->tableHabitCheck();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190604_103400_init_habit_module cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190604_103400_init_habit_module cannot be reverted.\n";

        return false;
    }
    */
}
