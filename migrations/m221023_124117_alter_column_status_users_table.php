<?php

use yii\db\Migration;

/**
 * Class m221023_124117_alter_column_status_users_table
 */
class m221023_124117_alter_column_status_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('users', 'status', $this->string(20)->Null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221023_124117_alter_column_status_users_table cannot be reverted.\n";

        return false;
    }
}
