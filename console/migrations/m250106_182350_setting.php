<?php

use yii\db\Migration;

/**
 * Class m250106_182350_setting
 */
class m250106_182350_setting extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('setting',[
            'id'=>'pk',
            'mode'=>'int default 10 COMMENT "Default TEST= 10, (20 = live)"',
            'beheerderMail_test'=>'TEXT',
            'from_test'=>'TEXT',
            'to_test'=>'TEXT',
            'title'=>'TEXT',
            'theme_color'=>"VARCHAR(10)",
            'created_at'=>'int',
            'updated_at'=>'int',
        ]);

        $this->createTable('setting_email',[
            'id'=>"pk",
            'mail_type'=>"int",
            'subject'=>'TEXT',
            'body'=>'TEXT',
            'from'=>"TEXT",
            'to'=>"TEXT",
            'cc'=>"TEXT",
            'bcc'=>'TEXT',
            'created_at'=>'int',
            'updated_at'=>"int",
        ]);

        $this->createTable('setting_sponsortype',[
            'id'=>"pk",
            'type'=>"int",
            'name'=>'TEXT',
            'price'=>'TEXT',
            'maximumAllowedSubscribers'=>"int",
            'order'=>"int",
            'created_at'=>'int',
            'updated_at'=>"int",
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250106_182350_setting cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250106_182350_setting cannot be reverted.\n";

        return false;
    }
    */
}
