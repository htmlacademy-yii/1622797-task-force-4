<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tasks_files}}`.
 */
class m221005_160623_create_tasks_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tasks_files}}', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'file_id' => $this->integer()->notNull()
        ]);

        $this->addForeignKey(
            'tasks_files_to_tasks',
            'tasks_files',
            'task_id',
            'tasks',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'tasks_files_to_file',
            'tasks_files',
            'file_id',
            'files',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tasks_files}}');
        $this->dropForeignKey('tasks_files_to_file', 'tasks_files');
        $this->dropForeignKey('tasks_files_to_task', 'tasks_files');
    }
}
