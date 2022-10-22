<?php

use yii\db\Migration;

/**
 * Class m220808_042244_tbl_container
 */
class m220808_042244_tbl_container extends Migration
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
        $this->createTable('{{%tbl_container}}', [
            'id' => $this->primaryKey(),
            'id_vendor' => $this->integer(),
            'id_measurement_system' => $this->integer(),
            'price' => $this->integer()->notNull(),
            'created_at' => $this->integer(),
        ]);

        $this->addForeignKey('fk_system', '{{%tbl_container}}', 'id_measurement_system', '{{%tbl_measurement_system}}', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_vendor', '{{%tbl_container}}', 'id_vendor', '{{%tbl_vendor}}', 'id', 'cascade', 'cascade');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_system', '{{%tbl_container}}');
        $this->dropForeignKey('fk_vendor', '{{%tbl_container}}');
        $this->dropTable('{{%tbl_container}}');
    }
}
