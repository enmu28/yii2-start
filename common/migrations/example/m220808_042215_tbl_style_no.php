<?php

use yii\db\Migration;

/**
 * Class m220805_082729_tbl_style_no
 */
class m220808_042215_tbl_style_no extends Migration
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
        $this->createTable('{{%tbl_style_no}}', [
            'id' => $this->primaryKey(),
            'id_container' => $this->integer()->notNull(),
            'style_no' => $this->integer(),
            'uom' => $this->integer(),
            'prefix' => $this->integer(),
            'sufix' => $this->integer(),
            'height' => $this->integer(),
            'width' => $this->integer(),
            'length' => $this->integer(),
            'weight' => $this->integer(),
            'upc' => $this->integer(),
            'size_1' => $this->integer(),
            'size_2' => $this->integer(),
            'size_3' => $this->integer(),
            'color_1' => $this->integer(),
            'color_2' => $this->integer(),
            'color_3' => $this->integer(),
            'carton' => $this->integer(),
        ]);

        $this->addForeignKey('fk_container', '{{%tbl_style_no}}', 'id_container', '{{%tbl_container}}', 'id', 'cascade', 'cascade');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_container', '{{%tbl_style_no}}');
        $this->dropTable('{{%tbl_style_no}}');
    }
}
