<?php

use yii\db\Migration;

/**
 * Class m250128_130633_logo_favo
 */
class m250128_130633_logo_favo extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('setting','favo_icon','LONGBLOB COMMENT "Base icon to create favo icons from"');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250128_130633_logo_favo cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250128_130633_logo_favo cannot be reverted.\n";

        return false;
    }
    */
}
