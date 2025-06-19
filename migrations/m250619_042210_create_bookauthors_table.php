<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bookauthors}}`.
 */
class m250619_042210_create_bookauthors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('bookauthors', [
            'id_b' => $this->integer()->notNull()->comment('id книги'),
            'id_a' => $this->integer()->notNull()->comment('id автора'),
        ]);
        $this->addPrimaryKey('bookauthors_pk','bookauthors', ['id_b', 'id_a']);
        $this->addForeignKey('bookauthors_id_b_fk', 'bookauthors', 'id_b', 'books', 'id' , 'CASCADE', 'CASCADE' );
        $this->addForeignKey('bookauthors_id_a_fk', 'bookauthors', 'id_a', 'authors', 'id' , 'CASCADE', 'CASCADE' );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bookauthors}}');
    }
}
