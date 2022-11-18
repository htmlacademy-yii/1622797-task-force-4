<?php

use yii\db\Migration;

/**
 * Class m221103_165902_rename_response_table
 */
class m221103_165902_rename_response_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('response', 'offers');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221103_165902_rename_response_table cannot be reverted.\n";

        return false;
    }
}
