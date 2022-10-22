<?php

use yii\db\Migration;

/**
 * Class m220805_082652_tbl_vendor
 */
class m221017_032930_create_tbl_slug_action extends Migration
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
        $this->createTable('{{%slug_action}}', [
            'id' => $this->primaryKey(),
            'name' => $this->text(),
            'action_controller' => $this->text(),
            'action_name' => $this->text(),
        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable('{{%slug_action}}');
    }
}
