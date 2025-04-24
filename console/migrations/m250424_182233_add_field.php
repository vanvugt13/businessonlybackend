<?php

use yii\db\Migration;

/**
 * Class m250424_182233_add_field
 */
class m250424_182233_add_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user_image','checked',$this->integer(1)->defaultValue(0));
        $this->addColumn('company_image','checked',$this->integer(1)->defaultValue(0));
        $this->addColumn('post_image','checked',$this->integer(1)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250424_182233_add_field cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250424_182233_add_field cannot be reverted.\n";

        return false;
    }
    */
}
