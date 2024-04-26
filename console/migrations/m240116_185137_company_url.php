<?php

use yii\db\Migration;

/**
 * Class m240116_185137_company_url
 */
class m240116_185137_company_url extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("company",'url','TEXT');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240116_185137_company_url cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240116_185137_company_url cannot be reverted.\n";

        return false;
    }
    */
}
