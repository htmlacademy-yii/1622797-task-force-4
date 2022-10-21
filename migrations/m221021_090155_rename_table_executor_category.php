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

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221021_090155_rename_table_executor_category cannot be reverted.\n";

        return false;
    }
    */
}
