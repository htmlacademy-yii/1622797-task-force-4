<?php

use yii\db\Migration;

/**
 * Class m221021_090659_rename_column_rating_feedback_table
 */
class m221021_090659_rename_column_rating_feedback_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('feedback', 'rating', 'grade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221021_090659_rename_column_rating_feedback_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221021_090659_rename_column_rating_feedback_table cannot be reverted.\n";

        return false;
    }
    */
}
