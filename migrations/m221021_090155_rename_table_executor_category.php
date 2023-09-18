<?php

use yii\db\Migration;

/**
 * Class m221021_090155_rename_table_executor_category
 */
class m221021_090155_rename_table_executor_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('executor_category', 'executorCategory');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221021_090155_rename_table_executor_category cannot be reverted.\n";

        return false;
    }
}
