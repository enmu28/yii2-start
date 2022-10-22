<?php

use yii\db\Migration;

/**
 * Class m220805_082652_tbl_vendor
 */
class m221014_064358_tbl_newspaper extends Migration
{
    public function init()
    {
        $this->db = 'db_api';
        parent::init();
    }

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        //id category_id type color author page_number status published_at log_id
        $this->createTable('{{%newspaper}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'type' => $this->integer(),
            'color' => $this->string(),
            'author' => $this->text(),
            'page_number' => $this->integer(),
            'status' => $this->integer()->defaultValue(1),
            'published_at' => $this->integer(),
        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable('{{%newspaper}}');
    }
}
