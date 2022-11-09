<?php

use yii\db\Migration;

/**
 * Class m221109_152634_add_column_address_lat_long_tasks_table
 */
class m221109_152634_add_column_address_lat_long_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tasks', 'address', $this->string(255)->null());
        $this->addColumn('tasks', 'latitude', $this->string(15)->null());
        $this->addColumn('tasks', 'longitude', $this->string(15)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221109_152634_add_column_address_lat_long_tasks_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221109_152634_add_column_address_lat_long_tasks_table cannot be reverted.\n";

        return false;
    }
    */
}
