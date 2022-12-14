<?php

use yii\db\Migration;

/**
 * Class m220805_082652_tbl_vendor
 */
class m221024_034145_tbl_category extends Migration
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
        // id name created_at updated_at
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'page_number' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable('{{%category}}');
    }
}
