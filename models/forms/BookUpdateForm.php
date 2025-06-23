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

    /**
     * @var int Флажок, что нужно удалить картинку, когда не перекрываем новой картинкой
     * 1 - надо, 0 - нет
     */
    public int $needDeleteOldImage = 0;

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
        $rules[] = ['needDeleteOldImage', 'in', 'range' => [0, 1]];
        return $rules;
    }

    /**
     * @return Book
     */
    public function getBook()
    {
        return $this->book;
    }

    public function isNeedToDeleteOldImage()
    {
        return $this->needDeleteOldImage == 1;
    }



}
