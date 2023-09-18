<?php

use yii\db\Migration;

/**
 * Class m221031_220740_alter_column_period_execution_tasks_table
 */
class m221031_220740_alter_column_period_execution_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('tasks', 'period_execution', $this->date()->Null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221031_220740_alter_column_period_execution_tasks_table cannot be reverted.\n";

        return false;
    }
}
