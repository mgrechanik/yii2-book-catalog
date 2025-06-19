<?php

declare(strict_types=1);

namespace app\models\forms;

use app\models\entities\Book;
use app\models\entities\Author;
use Yii;
use app\services\BookImageServiceInterface;

/**
 *
 */
class BookUpdateForm extends BookCreateForm
{
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
