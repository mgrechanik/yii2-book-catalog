<?php
/**
 * This file is part of the mgrechanik/yii2-book-catalog project
 *
 * @copyright Copyright (c) Mikhail Grechanik <mike.grechanik@gmail.com>
 * @license https://github.com/mgrechanik/yii2-book-catalog/blob/main/LICENSE.md
 * @link https://github.com/mgrechanik/yii2-book-catalog
 */
declare(strict_types=1);

namespace app\models\forms;

use app\models\entities\Book;
use app\services\BookImageServiceInterface;

/**
 * Форма редактирования книги
 */
class BookUpdateForm extends BookCreateForm
{
    /**
     * @var Book AR модель книги
     */
    private Book $book;

    public function __construct(Book $book, BookImageServiceInterface $imService, $config = [])
    {
        $this->book = $book;
        parent::__construct($imService, $config);
    }

    public function init()
    {
        parent::init();
        $book = $this->book;
        $this->name = $book->name;
        $this->description = $book->description;
        $this->isbn = $book->isbn;
        $this->year = $book->year;
        $this->imagePath = $book->photo;
        $authors = $book->authors;
        $i = 1;
        foreach ($authors as $author) {
            $this['author' . $i] = $author->id;
            $i++;
        }
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules['isbn'] = [['isbn'], 'unique', 'targetClass' => Book::class, 'filter' => ['<>', 'id', $this->book->id]];
        return $rules;
    }

    public function getBook()
    {
        return $this->book;
    }

}
