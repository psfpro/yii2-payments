<?php

use yii\db\Migration;

/**
 * Class m171122_220504_init
 */
class m171122_220504_init extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%payment_account}}', [
            'id' => $this->primaryKey()->unsigned(),
            'balance' => $this->integer()->unsigned(),
            'description' => $this->string()->null(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->insert('{{%payment_account}}', [
            'id' => 1,
            'balance' => 0,
            'description' => 'Счет для комиссий'
        ]);

        $this->createTable('{{%payment_transaction}}', [
            'id' => $this->primaryKey()->unsigned(),
            'type' => 'ENUM("deposit","withdrawal","transfer") NOT NULL',
            'source_account_id' => $this->integer()->unsigned(),
            'beneficiary_account_id' => $this->integer()->unsigned(),
            'amount' => $this->integer(),
            'description' => $this->string()->null(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->createIndex('idx_payment_transaction__source_account_id', '{{%payment_transaction}}', 'source_account_id');
        $this->addForeignKey('fk_payment_transaction__source_account_id', '{{%payment_transaction}}', 'source_account_id', '{{%payment_account}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_payment_transaction__beneficiary_account_id', '{{%payment_transaction}}', 'beneficiary_account_id');
        $this->addForeignKey('fk_payment_transaction__beneficiary_account_id', '{{%payment_transaction}}', 'beneficiary_account_id', '{{%payment_account}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%payment_operation}}', [
            'id' => $this->primaryKey()->unsigned(),
            'transaction_id' => $this->integer()->unsigned(),
            'account_id' => $this->integer()->unsigned(),
            'amount' => $this->integer(),
            'description' => $this->string()->null(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->createIndex('idx_payment_operation__transaction_id', '{{%payment_operation}}', 'transaction_id');
        $this->addForeignKey('fk_payment_operation__transaction_id', '{{%payment_operation}}', 'transaction_id', '{{%payment_transaction}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_payment_operation__account_id', '{{%payment_operation}}', 'account_id');
        $this->addForeignKey('fk_payment_operation__account_id', '{{%payment_operation}}', 'account_id', '{{%payment_account}}', 'id', 'CASCADE', 'CASCADE');


        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(50)->notNull(),
            'password' => $this->string(255)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'account_id' => $this->integer()->unsigned()->notNull(),
            'created_at' => $this->timestamp(),
        ], $tableOptions);

        $this->createIndex('idx_user__account_id', '{{%user}}', 'account_id');
        $this->addForeignKey('fk_user__account_id', '{{%user}}', 'account_id', '{{%payment_account}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m171122_220504_init cannot be reverted.\n";

        return false;
    }
}
