<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%feedback}}`.
 */
class m221005_160556_create_feedback_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%feedback}}', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer()->notNull(),
            'executor_id' => $this->integer()->notNull(),
            'task_id' => $this->integer()->notNull(),
            'date_creation' => $this->dateTime()
                ->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'description' => $this->string()->notNull(),
            'rating' => $this->tinyInteger()->notNull()
        ]);

        $this->addForeignKey(
            'customer_to_feedback',
            'feedback',
            'customer_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'executor_to_feedback',
            'feedback',
            'executor_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'tasks_for_feedback',
            'feedback',
            'task_id',
            'tasks',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%feedback}}');
        $this->dropForeignKey('tasks_for_feedback', 'feedback');
        $this->dropForeignKey('executor_to_feedback', 'feedback');
        $this->dropForeignKey('customer_to_feedback', 'feedback');
    }
}
