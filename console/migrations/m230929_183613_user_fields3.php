<?php

use yii\db\Migration;

/**
 * Class m230929_183613_user_fields3
 */
class m230929_183613_user_fields3 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("user",'email_address','VARCHAR(255)');
        $this->addColumn("user",'phone_number','VARCHAR(255)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230929_183613_user_fields3 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230929_183613_user_fields3 cannot be reverted.\n";

        return false;
    }
    */
}
