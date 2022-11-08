<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m221005_160544_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'email' => $this->string(255)->notNull(),
            'password' => $this->string(64)->notNull(),
            'city_id' => $this->integer()->notNull(),
            'date_creation' => $this->dateTime()
                ->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'rating' => $this->tinyInteger()->defaultValue(0),
            'popularity' => $this->integer()->defaultValue(0),
            'avatar_file_id' => $this->integer()->notNull(),
            'birthday' => $this->dateTime(),
            'phone' => $this->string(32),
            'telegram' => $this->string(64),
            'bio' => $this->string(),
            'orders_num' => $this->integer()->defaultValue(0),
            'status' => $this->string(20)->notNull(),
            'is_executor' => $this->boolean()->notNull()
        ]);

        $this->addForeignKey(
            'users_to_files',
            'users',
            'avatar_file_id',
            'files',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'users_to_cities',
            'users',
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
        $this->dropTable('{{%users}}');
        $this->dropForeignKey('users_to_file', 'users');
        $this->dropForeignKey('users_to_cities', 'users');
    }
}
