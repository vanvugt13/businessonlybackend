<?php

use yii\db\Migration;

/**
 * Class m230722_173203_chat
 */
class m230722_173203_chat extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	$this->createTable('chat',['id'=>'pk',
				'source_user_id'=>'int',
				'message'=>'TEXT',
				'destination_user_id'=>'int',
				'created_at'=>'int'
	]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230722_173203_chat cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230722_173203_chat cannot be reverted.\n";

        return false;
    }
    */
}
