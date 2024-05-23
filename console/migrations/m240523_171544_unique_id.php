<?php

use yii\db\Migration;

/**
 * Class m240523_171544_unique_id
 */
class m240523_171544_unique_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user','unique_id','VARCHAR(255)');
        $this->addColumn('company','unique_id','VARCHAR(255)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240523_171544_unique_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240523_171544_unique_id cannot be reverted.\n";

        return false;
    }
    */
}
