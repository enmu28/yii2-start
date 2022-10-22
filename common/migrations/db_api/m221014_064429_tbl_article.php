<?php

use yii\db\Migration;

/**
 * Class m220805_082652_tbl_vendor
 */
class m221014_064429_tbl_article extends Migration
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
//        id category_id name title slug url excerpt content status published_at log_id
        $this->createTable('{{%article}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'name' => $this->text(),
            'title' => $this->text(),
            'slug' => $this->text(),
            'url' => $this->text(),
            'excerpt' => $this->text(),
            'content' => $this->text(),
            'status' => $this->integer()->defaultValue(1),
            'published_at' => $this->integer(),
        ]);
        //id category_id type color author page_number status published_at log_id
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable('{{%newspaper}}');
    }
}
