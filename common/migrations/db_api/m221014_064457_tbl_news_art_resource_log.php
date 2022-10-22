<?php

use yii\db\Migration;

/**
 * Class m220805_082652_tbl_vendor
 */
class m221014_064457_tbl_news_art_resource_log extends Migration
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
//      id news_art_resource_id news_art_resource_type action by_user_id created_at updated_at
        $this->createTable('{{%news_art_resource_log}}', [
            'id' => $this->primaryKey(),
            'news_art_resource_id' => $this->integer(),
            'news_art_resource_type' => $this->text(),
            'action' => $this->text(),
            'by_user_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable('{{%news_art_resource_log}}');
    }
}
