<?php

use yii\db\Migration;

/**
 * Class m221021_092334_delete_order_nums_users_table
 */
class m221021_092334_delete_order_nums_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('users', 'orders_num');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221021_092334_delete_order_nums_users_table cannot be reverted.\n";

        return false;
    }
}
