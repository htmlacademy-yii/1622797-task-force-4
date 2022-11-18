<?php

use yii\db\Migration;

/**
 * Class m221031_220328_alter_column_description_tasks_table
 */
class m221031_220328_alter_column_description_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('tasks', 'description', $this->string()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221031_220328_alter_column_description_tasks_table cannot be reverted.\n";

        return false;
    }
}
