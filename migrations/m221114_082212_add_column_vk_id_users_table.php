<?php

use yii\db\Migration;

/**
 * Class m221114_082212_add_column_vk_id_users_table
 */
class m221114_082212_add_column_vk_id_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'vk_id', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221114_082212_add_column_vk_id_users_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221114_082212_add_column_vk_id_users_table cannot be reverted.\n";

        return false;
    }
    */
}
