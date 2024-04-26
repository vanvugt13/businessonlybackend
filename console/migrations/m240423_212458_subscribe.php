<?php

use yii\db\Migration;

/**
 * Class m240423_212458_subscribe
 */
class m240423_212458_subscribe extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable("subscribe",[
            'id'=>'pk',
            'news_id'=>'int',
            'post_id'=>'int',
            'event_id'=>'int',
            'user_id'=>'int',
            'created_at'=>'int',
            'updated_at'=>'int',

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240423_212458_subscribe cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240423_212458_subscribe cannot be reverted.\n";

        return false;
    }
    */
}
