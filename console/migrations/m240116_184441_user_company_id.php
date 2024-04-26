<?php

use yii\db\Migration;

/**
 * Class m240116_184441_user_company_id
 */
class m240116_184441_user_company_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTAble('user_company',['id'=>'pk',
            'user_id'=>'int',
            'company_id'=>'int',
            'created_at'=>'int',
            'updated_at'=>'int']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240116_184441_user_company_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240116_184441_user_company_id cannot be reverted.\n";

        return false;
    }
    */
}
