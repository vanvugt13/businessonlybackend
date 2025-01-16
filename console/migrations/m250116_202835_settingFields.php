<?php

use yii\db\Migration;

/**
 * Class m250116_202835_settingFields
 */
class m250116_202835_settingFields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('setting','news_url','TEXT COMMENT "Url of news"');
        $this->addColumn('setting','background_color','TEXT COMMENT "Main background color"');
        $this->addColumn('setting','background_template','TEXT COMMENT "Main background template (logo)"');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250116_202835_settingFields cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250116_202835_settingFields cannot be reverted.\n";

        return false;
    }
    */
}
