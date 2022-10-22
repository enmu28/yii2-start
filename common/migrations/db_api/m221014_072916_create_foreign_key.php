<?php

use yii\db\Migration;

/**
 * Class m221014_072916_create_foreign_key
 */
class m221014_072916_create_foreign_key extends Migration
{
    public function init()
    {
        $this->db = 'db_api';
        parent::init();
    }
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk_newspaper_category', '{{%newspaper}}', 'category_id', '{{%category}}', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_newspaper_log', '{{%newspaper}}', 'log_id', '{{%news_art_resource_log}}', 'id', 'cascade', 'cascade');

        $this->addForeignKey('fk_article_category', '{{%article}}', 'category_id', '{{%category}}', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_article_log', '{{%article}}', 'log_id', '{{%news_art_resource_log}}', 'id', 'cascade', 'cascade');

        $this->addForeignKey('fk_map_resource', '{{%map}}', 'resource_id', '{{%resource}}', 'id', 'cascade', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_newspaper_category', '{{%newspaper}}');
        $this->dropForeignKey('fk_newspaper_log', '{{%newspaper}}');

        $this->dropForeignKey('fk_article_category', '{{%newspaper}}');
        $this->dropForeignKey('fk_article_log', '{{%newspaper}}');

        $this->dropForeignKey('fk_map_resource', '{{%map}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221014_072916_create_foreign_key cannot be reverted.\n";

        return false;
    }
    */
}
