<?php

use yii\db\Migration;

/**
 * Class m221021_091753_alter_column_grade_users_table
 */
class m221021_091753_alter_column_grade_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(
            'users',
            'grade',
            $this->float(3)->defaultValue(0)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221021_091753_alter_column_grade_users_table cannot be reverted.\n";

        return false;
    }
}
