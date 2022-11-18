<?php

use yii\db\Migration;

/**
 * Class m221031_220559_alter_column_budget_tasks_table
 */
class m221031_220559_alter_column_budget_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('tasks', 'budget', $this->integer()->Null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221031_220559_alter_column_budget_tasks_table cannot be reverted.\n";

        return false;
    }
}
