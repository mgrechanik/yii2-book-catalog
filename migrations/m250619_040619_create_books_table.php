<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%books}}`.
 */
class m250619_040619_create_books_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%books}}', [
            'id' => $this->primaryKey(),
            'name'=> $this->string(100)->notNull()->comment('Название книги'),
            'description' => $this->text()->comment('Описание книги'),
            'year' => $this->integer()->notNull()->comment('Год издания'),
            'isbn' => $this->string(17)->unique()->comment('ISBN книги, если известен'),
            'photo' => $this->string('100')->comment('Путь к картинке обложки'),
            'id_user' => $this->integer()->notNull()->comment('id пользователя, добавившего книгу'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('books_year_idx', 'books', 'year');
        $this->createIndex('books_name_idx', 'books', 'name');
        $this->createIndex('books_isbn_idx', 'books', 'isbn');

        $this->addForeignKey('books_id_user_fk', 'books', 'id_user', 'user', 'id' , 'CASCADE', 'CASCADE' );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%books}}');
    }
}
