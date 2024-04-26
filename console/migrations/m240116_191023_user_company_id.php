<?php

use yii\db\Migration;

/**
 * Class m240116_191023_user_company_id
 */
class m240116_191023_user_company_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user','company_id','int');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240116_191023_user_company_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240116_191023_user_company_id cannot be reverted.\n";

        return false;
    }
    */
}
