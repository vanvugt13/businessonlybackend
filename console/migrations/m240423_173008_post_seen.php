<?php

use yii\db\Migration;

/**
 * Class m240423_173008_post_seen
 */
class m240423_173008_post_seen extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('post_seen',[
            'id'=>'pk',
            'post_id'=>'int',
            'user_id'=>'int',
            'created_at'=>'int',
            'updated_at'=>'int',
        ]);

        $this->addForeignKey('fk_post_seen_post_id','post_seen','post_id','post','id','CASCADE','CASCADE');
        $this->addForeignKey('fk_post_seen_user_id','post_seen','user_id','user','id','CASCADE','CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240423_173008_post_seen cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240423_173008_post_seen cannot be reverted.\n";

        return false;
    }
    */
}
