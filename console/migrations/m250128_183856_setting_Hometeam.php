<?php

use yii\db\Migration;

/**
 * Class m250128_183856_setting_Hometeam
 */
class m250128_183856_setting_Hometeam extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('setting','home_team','VARCHAR(255)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250128_183856_setting_Hometeam cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250128_183856_setting_Hometeam cannot be reverted.\n";

        return false;
    }
    */
}
