<?php

use yii\db\Migration;

/**
 * Class m240415_190300_event_sponsor
 */
class m240415_190300_event_sponsor extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('event_sponsor',[
            'id'=>'pk',
            'event_id'=>'int',
            'user_id'=>'int',
            'sponsor_type'=>'int',
            'created_at'=>'int',
            'updated_at'=>'int',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240415_190300_event_sponsor cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240415_190300_event_sponsor cannot be reverted.\n";

        return false;
    }
    */
}
