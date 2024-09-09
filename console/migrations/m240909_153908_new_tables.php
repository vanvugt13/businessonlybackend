<?php

use yii\db\Migration;

/**
 * Class m240909_153908_new_tables
 */
class m240909_153908_new_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_image',['id'=>'pk',
        'user_id'=>'int',
        'image'=>'LONGBLOB']);

        $this->addForeignKey('fk_user_image_user_id','user_image','user_id','user','id','CASCADE','CASCADE');

        $this->createTable('post_image',['id'=>'pk',
        'post_id'=>'int',
        'image'=>'LONGBLOB']);

        $this->addForeignKey('fk_post_image_post_id','post_image','post_id','post','id','CASCADE','CASCADE');

        $this->createTable('company_image',['id'=>'pk',
        'company_id'=>'int',
        'image'=>'LONGBLOB']);

        $this->addForeignKey('fk_company_image_company_id','company_image','company_id','company','id','CASCADE','CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240909_153908_new_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240909_153908_new_tables cannot be reverted.\n";

        return false;
    }
    */
}
