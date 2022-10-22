<?php

use yii\db\Migration;

/**
 * Class m220805_082652_tbl_vendor
 */
class m221014_064436_tbl_resource extends Migration
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
//       id news_article_id news_article_type type name title path extension use_map status log_id created_at updated_at
        $this->createTable('{{%resource}}', [
            'id' => $this->primaryKey(),
            'news_article_id' => $this->integer()->null(),
            'news_article_type' => $this->string()->null(),
            'type' => $this->text(),
            'title' => $this->text(),
            'path' => $this->text(),
            'extension' => $this->text(),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
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
