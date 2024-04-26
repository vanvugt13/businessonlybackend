<?php

use yii\db\Migration;

/**
 * Class m240116_184129_company
 */
class m240116_184129_company extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('company',['id'=>'pk',
            'name'=>"Varchar(255)",
            'description'=>"TEXT",
            'logo'=>"longblob",
            'created_at'=>'int',
            'updated_at'=>'int',

            ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240116_184129_company cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240116_184129_company cannot be reverted.\n";

        return false;
    }
    */
}
