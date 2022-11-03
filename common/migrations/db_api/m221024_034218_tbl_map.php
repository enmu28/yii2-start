<?php

use yii\db\Migration;

/**
 * Class m220805_082652_tbl_vendor
 */
class m221024_034218_tbl_map extends Migration
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
//       id resource_id target url title shape html_map html_svg
        $this->createTable('{{%map}}', [
            'id' => $this->primaryKey(),
            'resource_id' => $this->integer(),
            'target' => $this->string(),
            'url' => $this->text(),
            'title' => $this->text(),
            'shape' => $this->text(),
            'html_map' => $this->text(),
            'html_svg' => $this->text(),
        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable('{{%map}}');
    }
}
