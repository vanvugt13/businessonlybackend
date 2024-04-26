<?php

use yii\db\Migration;

/**
 * Class m230915_113532_user_token
 */
class m230915_113532_user_token extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	$this->addColumn('user','token','varchar(255)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230915_113532_user_token cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230915_113532_user_token cannot be reverted.\n";

        return false;
    }
    */
}
