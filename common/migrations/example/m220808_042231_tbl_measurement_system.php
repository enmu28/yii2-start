<?php

use yii\db\Migration;

/**
 * Class m220805_082750_tbl_measurement_system
 */
class m220808_042231_tbl_measurement_system extends Migration
{
    /**
     * {@inheritdoc}
     */
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
        $this->createTable('{{%tbl_measurement_system}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(150)->notNull(),
        ]);

    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable('{{%tbl_measurement_system}}');
    }
}
