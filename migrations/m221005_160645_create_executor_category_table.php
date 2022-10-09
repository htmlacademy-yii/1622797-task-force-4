<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%executor_category}}`.
 */
class m221005_160645_create_executor_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%executor_category}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull()
        ]);

        $this->addForeignKey(
            'executor_to_categories',
            'executor_category',
            'category_id',
            'categories',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'executor_to_users',
            'executor_category',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%executor_category}}');
        $this->dropForeignKey(
            'executor_to_users',
            'executor_category'
        );
        $this->dropForeignKey(
            'executor_to_categories',
            'executor_category'
        );
    }
}
