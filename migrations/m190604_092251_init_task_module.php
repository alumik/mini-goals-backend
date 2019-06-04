<?php

use yii\db\Migration;

/**
 * Class m190604_092251_init_task_module
 */
class m190604_092251_init_task_module extends Migration
{
    private function tableUser()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'openid' => $this->string()->notNull()->unique(),
            'name' => $this->string()->notNull(),
            'id_task_lists' => $this->integer()->null()->unique(),
        ]);
    }

    private function tableTaskLabel()
    {
        $this->createTable('task_label', [
            'id' => $this->primaryKey(),
            'id_user' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
        ]);

        $this->addForeignKey(
            'task_label_user_id_fk',
            'task_label',
            'id_user',
            'user',
            'id'
        );

        $this->createIndex(
            'task_label_id_user_name_uindex',
            'task_label',
            ['id_user', 'name'],
            true
        );
    }

    private function tableTaskList()
    {
        $this->createTable('task_list', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'archived' => $this->boolean()->notNull()->defaultValue(false),
            'id_prev' => $this->integer()->null()->unique(),
            'id_next' => $this->integer()->null()->unique(),
        ]);

        $this->addForeignKey(
            'user_task_list_id_fk',
            'user',
            'id_task_lists',
            'task_list',
            'id'
        );

        $this->addForeignKey(
            'task_list_task_list_id_fk',
            'task_list',
            'id_prev',
            'task_list',
            'id'
        );

        $this->addForeignKey(
            'task_list_task_list_id_fk_2',
            'task_list',
            'id_next',
            'task_list',
            'id'
        );
    }

    private function tableTask()
    {
        $this->createTable('task', [
            'id' => $this->primaryKey(),
            'id_task_list' => $this->integer()->notNull(),
            'content' => $this->text()->notNull(),
            'finished' => $this->boolean()->notNull()->defaultValue(false),
        ]);

        $this->addForeignKey(
            'task_task_list_id_fk',
            'task',
            'id_task_list',
            'task_list',
            'id'
        );
    }

    private function tableTaskListTaskLabelRelation()
    {
        $this->createTable('task_list_task_label_relation', [
            'id_task_label' => $this->integer()->notNull(),
            'id_task_list' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey(
            'task_list_task_label_relation_pk',
            'task_list_task_label_relation',
            ['id_task_label', 'id_task_list']
        );

        $this->addForeignKey(
            'task_list_task_label_relation_task_list_id_fk',
            'task_list_task_label_relation',
            'id_task_list',
            'task_list',
            'id'
        );

        $this->addForeignKey(
            'task_list_task_label_relation_task_label_id_fk',
            'task_list_task_label_relation',
            'id_task_label',
            'task_label',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->tableUser();
        $this->tableTaskLabel();
        $this->tableTaskList();
        $this->tableTask();
        $this->tableTaskListTaskLabelRelation();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190604_092251_init_task_module cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190604_092251_init_task_module cannot be reverted.\n";

        return false;
    }
    */
}
