<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%response}}`.
 */
class m221005_160556_create_response_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%response}}', [
            'id' => $this->primaryKey(),
            'date_creation' => $this->dateTime()
                ->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'task_id' => $this->integer()->notNull(),
            'executor_id' => $this->integer()->notNull(),
            'price' => $this->integer(),
            'comment' => $this->string(),
            'refuse' => $this->tinyInteger()
        ]);

        $this->addForeignKey(
            'executor_to_response',
            'response',
            'executor_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'task_to_response',
            'response',
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
        $this->dropTable('{{%response}}');
        $this->dropForeignKey('task_to_response', 'response');
        $this->dropForeignKey('executor_to_response', 'response');
    }
}
