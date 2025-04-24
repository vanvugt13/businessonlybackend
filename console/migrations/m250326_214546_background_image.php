<?php

use yii\db\Migration;

/**
 * Class m250326_214546_background_image
 */
class m250326_214546_background_image extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('setting','background_image','TEXT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250326_214546_background_image cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250326_214546_background_image cannot be reverted.\n";

        return false;
    }
    */
}
