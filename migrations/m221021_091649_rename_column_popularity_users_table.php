<?php

use yii\db\Migration;

/**
 * Class m221021_091649_rename_column_popularity_users_table
 */
class m221021_091649_rename_column_popularity_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('users', 'popularity', 'grade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221021_091649_rename_column_popularity_users_table cannot be reverted.\n";

        return false;
    }
}
