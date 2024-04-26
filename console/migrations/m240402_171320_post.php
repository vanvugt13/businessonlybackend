<?php

use yii\db\Migration;

/**
 * Class m240402_171320_post
 */
class m240402_171320_post extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('post',[
            'id'=>'pk',
            'user_id'=>"int",
            'title'=>"VARCHAR(100)",
            'description'=>'TEXT',
            'category'=>'int',
            'image'=>"longblob",
            'visible_till'=>"int",
            'created_at'=>'int',
            'updated_at'=>'int',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240402_171320_post cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240402_171320_post cannot be reverted.\n";

        return false;
    }
    */
}
