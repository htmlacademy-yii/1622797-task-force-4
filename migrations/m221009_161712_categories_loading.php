<?php

use yii\db\Migration;

/**
 * Class m221009_161712_categories_loading
 */
class m221009_161712_categories_loading extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $categorySqlInsert = file_get_contents(
            './web/data/categories-sql.sql'
        );

        $this->execute($categorySqlInsert);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221009_161712_categories_loading cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221009_161712_categories_loading cannot be reverted.\n";

        return false;
    }
    */
}
