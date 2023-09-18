<?php

use yii\db\Migration;

/**
 * Class m221106_203256_add_column_show_contacts_users_table
 */
class m221106_203256_add_column_show_contacts_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'show_contacts', $this->boolean()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221106_203256_add_column_show_contacts_users_table cannot be reverted.\n";

        return false;
    }
}
