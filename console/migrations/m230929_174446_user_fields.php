<?php

use yii\db\Migration;

/**
 * Class m230929_174446_user_fields
 */
class m230929_174446_user_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user','description','TEXT');
        $this->addColumn('user','company_name','VARCHAR(255)');
        $this->addColumn('user','image','BLOB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230929_174446_user_fields cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230929_174446_user_fields cannot be reverted.\n";

        return false;
    }
    */
}
