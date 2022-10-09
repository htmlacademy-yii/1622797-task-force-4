<?php

use yii\db\Migration;

/**
 * Class m221009_161402_cities_loading
 */
class m221009_161402_cities_loading extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $citiesSqlInsert = file_get_contents(
            './web/data/cities-sql.sql'
        );

        $this->execute($citiesSqlInsert);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221009_161402_cities_loading cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221009_161402_cities_loading cannot be reverted.\n";

        return false;
    }
    */
}
