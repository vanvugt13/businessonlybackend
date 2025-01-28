<?php

use yii\db\Migration;

/**
 * Class m250128_184303_links
 */
class m250128_184303_links extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('setting','sponsor_options_url','TEXT COMMENT "Link to show in the dialog if sponsor type is used"');
        $this->addColumn('setting','calendar_url','TEXT COMMENT "Link to fetch calendar data"');
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250128_184303_links cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250128_184303_links cannot be reverted.\n";

        return false;
    }
    */
}
