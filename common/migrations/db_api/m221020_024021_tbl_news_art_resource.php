<?php

use yii\db\Migration;

/**
 * Class m220805_082652_tbl_vendor
 */
class m221020_024021_tbl_news_art_resource extends Migration
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
        $this->createTable('{{%news_art_resource}}', [
            'id' => $this->primaryKey(),
            'news_art_id' => $this->integer(),
            'news_art_type' => $this->text(),
            'resource_id' => $this->integer(),
        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable('{{%news_art_resource}}');
    }
}
