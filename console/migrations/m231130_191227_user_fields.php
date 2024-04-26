<?php

use yii\db\Migration;

/**
 * Class m231130_191227_user_fields
 */
class m231130_191227_user_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	$this->addColumn('user','url','TEXT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231130_191227_user_fields cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231130_191227_user_fields cannot be reverted.\n";

        return false;
    }
    */
}
