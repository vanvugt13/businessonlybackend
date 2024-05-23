<?php

use yii\db\Migration;

/**
 * Class m240523_173243_unique_id_post
 */
class m240523_173243_unique_id_post extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $this->addColumn('post','unique_id','VARCHAR(255)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240523_173243_unique_id_post cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240523_173243_unique_id_post cannot be reverted.\n";

        return false;
    }
    */
}
