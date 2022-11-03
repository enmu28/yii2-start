<?php

use yii\db\Migration;

/**
 * Class m220805_082652_tbl_vendor
 */
class m221024_034305_tbl_slug_name extends Migration
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
        $this->createTable('{{%slug_name}}', [
            'id' => $this->primaryKey(),
            'user_name' => $this->text(),
            'name' => $this->text(),
            'slug' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable('{{%slug_name}}');
    }
}
