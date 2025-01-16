<?php

use yii\db\Migration;

/**
 * Class m250116_202434_rename_vvog_id
 */
class m250116_202434_rename_vvog_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('news','vvog_id','news_id_customer');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250116_202434_rename_vvog_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250116_202434_rename_vvog_id cannot be reverted.\n";

        return false;
    }
    */
}
