<?php

use yii\db\Migration;

/**
 * Class m250128_195401_extra_fields
 */
class m250128_195401_extra_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('setting','name_application','VARCHAR(255)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250128_195401_extra_fields cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250128_195401_extra_fields cannot be reverted.\n";

        return false;
    }
    */
}
