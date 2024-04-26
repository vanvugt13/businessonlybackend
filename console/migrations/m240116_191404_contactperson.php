<?php

use yii\db\Migration;

/**
 * Class m240116_191404_contactperson
 */
class m240116_191404_contactperson extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("user",'contactperson','VARCHAR(255)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240116_191404_contactperson cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240116_191404_contactperson cannot be reverted.\n";

        return false;
    }
    */
}
