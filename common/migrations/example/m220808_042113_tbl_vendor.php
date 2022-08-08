<?php

use yii\db\Migration;

/**
 * Class m220805_082652_tbl_vendor
 */
class m220808_042113_tbl_vendor extends Migration
{
    public function init()
    {
        $this->db = 'example';
        parent::init();
    }

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_vendor}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(150)->notNull(),
//            'container' => $this->string(150)->notNull(),
//            'price' => $this->integer()->notNull(),
//            'id_measurement_system' => $this->integer(),
//            'created_at' => $this->integer(),
        ]);

//        $this->addForeignKey('fk_system', '{{%tbl_vendor}}', 'id_measurement_system', '{{%tbl_measurement_system}}', 'id', 'cascade', 'cascade');

    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
//        $this->dropForeignKey('fk_system', '{{%tbl_vendor}}');
        $this->dropTable('{{%tbl_vendor}}');
    }
}
