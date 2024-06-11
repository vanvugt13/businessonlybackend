<?php

use yii\db\Migration;

/**
 * Class m240611_191539_notification
 */
class m240611_191539_notification extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('push_subscribers',[
             'id'=>'pk',
             'raw_data'=>'LONGBLOB',
             'endpoint'=>"TEXT",
             'expirationDate'=>"TEXT",
             'keys'=>'BLOB',
             'created_at'=>'int',
             'updated_at'=>'int',

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240611_191539_notification cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240611_191539_notification cannot be reverted.\n";

        return false;
    }
    */
}
