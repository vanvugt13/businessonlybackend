<?php

use yii\db\Migration;

/**
 * Class m250116_201438_settingFields
 */
class m250116_201438_settingFields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('setting','logo_url','TEXT COMMENT "Full url of logo" AFTER theme_color');
        $this->addColumn('setting','logo_blob','LONGBLOB COMMENT "Blob of logo" AFTER logo_url');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250116_201438_settingFields cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250116_201438_settingFields cannot be reverted.\n";

        return false;
    }
    */
}
