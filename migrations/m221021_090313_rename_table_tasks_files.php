<?php

use yii\db\Migration;

/**
 * Class m221021_090313_rename_table_tasks_files
 */
class m221021_090313_rename_table_tasks_files extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('tasks_files', 'tasksFiles');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221021_090313_rename_table_tasks_files cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221021_090313_rename_table_tasks_files cannot be reverted.\n";

        return false;
    }
    */
}
