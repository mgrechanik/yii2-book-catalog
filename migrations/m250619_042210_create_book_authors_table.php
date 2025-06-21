<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book_authors}}`.
 */
class m250619_042210_create_book_authors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('book_authors', [
            'book_id' => $this->integer()->notNull()->comment('id книги'),
            'author_id' => $this->integer()->notNull()->comment('id автора'),
        ]);
        $this->addPrimaryKey('book_authors_pk','book_authors', ['book_id', 'author_id']);
        $this->addForeignKey('book_authors_book_id_fk', 'book_authors', 'book_id', 'books', 'id' , 'CASCADE', 'CASCADE' );
        $this->addForeignKey('book_authors_author_id_fk', 'book_authors', 'author_id', 'authors', 'id' , 'CASCADE', 'CASCADE' );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%book_authors}}');
    }
}
