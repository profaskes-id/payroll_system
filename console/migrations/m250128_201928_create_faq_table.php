<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%faq}}`.
 */
class m250128_201928_create_faq_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%faq}}', [
            'id_faq' => $this->primaryKey(),
            'question' => $this->text()->notNull(),
            'answer' => $this->text()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1), // 0 or 1
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%faq}}');
    }
}
