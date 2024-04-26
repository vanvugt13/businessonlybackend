<?php

use yii\db\Migration;

/**
 * Class m240312_181741_news
 */
class m240312_181741_news extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('news',[
            'id'=>'pk',
            'vvog_id'=>"int",
            'title'=>'VARCHAR(255)',
            'intro'=>'TEXT',
            'text'=>"TEXT",
            'date'=>'datetime',
            'category'=>"VARCHAR(255)",
            'image'=>"TEXT",
            'url'=>'TEXT',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240312_181741_news cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240312_181741_news cannot be reverted.\n";

        return false;
    }
    */
}
