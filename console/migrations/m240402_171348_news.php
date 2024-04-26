<?php

use yii\db\Migration;

/**
 * Class m240402_171348_news
 */
class m240402_171348_news extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        return true;

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240402_171348_news cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240402_171348_news cannot be reverted.\n";

        return false;
    }
    */
}
