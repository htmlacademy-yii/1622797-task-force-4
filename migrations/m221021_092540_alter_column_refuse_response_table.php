<?php

use yii\db\Migration;

/**
 * Class m221021_092540_alter_column_refuse_response_table
 */
class m221021_092540_alter_column_refuse_response_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(
            'response',
            'refuse',
            $this->string(20)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221021_092540_alter_column_refuse_response_table cannot be reverted.\n";

        return false;
    }
}
