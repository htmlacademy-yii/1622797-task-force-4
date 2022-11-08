<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tasks}}`.
 */
class m221005_160550_create_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tasks}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->string(),
            'city_id' => $this->integer(),
            'date_creation' => $this->dateTime()
                ->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'category_id' => $this->integer()->notNull(),
            'customer_id' => $this->integer()->notNull(),
            'executor_id' => $this->integer(),
            'status' => $this->string(20)->defaultValue('new'),
            'budget' => $this->integer()->notNull(),
            'period_execution' => $this->dateTime()->notNull()
        ]);

        $this->addForeignKey(
            'tasks_for_customer',
            'tasks',
            'customer_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'tasks_for_executor',
            'tasks',
            'executor_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'tasks_for_categories',
            'tasks',
            'category_id',
            'categories',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'tasks_to_cities',
            'tasks',
            'city_id',
            'cities',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tasks}}');
        $this->dropForeignKey('tasks_to_cities', 'tasks');
        $this->dropForeignKey('tasks_for_categories', 'tasks');
        $this->dropForeignKey('tasks_for_executor', 'tasks');
        $this->dropForeignKey('tasks_for_customer', 'tasks');
    }
}
