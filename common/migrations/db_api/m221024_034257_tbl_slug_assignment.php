<?php

use yii\db\Migration;

/**
 * Class m220805_082652_tbl_vendor
 */
class m221024_034257_tbl_slug_assignment extends Migration
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
        $this->createTable('{{%slug_assignment}}', [
            'id' => $this->primaryKey(),
            'slug_name_id' => $this->integer()->notNull(),
            'slug_action_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk_slug_name', '{{%slug_assignment}}', 'slug_name_id', '{{%slug_name}}', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_slug_action', '{{%slug_assignment}}', 'slug_action_id', '{{%slug_action}}', 'id', 'cascade', 'cascade');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_slug_name', '{{%slug_assignment}}');
        $this->dropForeignKey('fk_slug_action', '{{%slug_assignment}}');
        $this->dropTable('{{%slug_assignment}}');
    }
}
