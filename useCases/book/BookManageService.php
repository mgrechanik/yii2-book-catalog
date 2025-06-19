<?php

declare(strict_types=1);

namespace app\useCases\book;

use app\models\entities\Book;
use app\models\forms\BookCreateForm;
use app\models\forms\BookUpdateForm;
use app\services\BookImageServiceInterface;
use Yii;

/**
 * Класс по управлению книгами
 */
class BookManageService
{
    public function create(BookCreateForm $form): Book
    {
        $book = Book::create(
            $form->name,
            $form->description,
            $form->isbn,
            $form->imagePath,
            $form->id_user,
            $form->year
        );
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            if (!$book->save(false)) {
                throw new \Exception('Unable to save book');
            }
            $book->setNewAuthors($form->getAuthorIds());
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw new \DomainException($e->getMessage());
        }
        return $book;
    }


    public function update(BookUpdateForm $form): void
    {
        $book = $form->book;
        $book->name = $form->name;
        $book->description = $form->description;
        $book->isbn = $form->isbn;
        $book->year = $form->year;
        if ($book->photo !== $form->imagePath) {
            $imgService = Yii::createObject(BookImageServiceInterface::class);
            $imgService->deleteImage($book->photo);
        }
        $book->photo =  $form->imagePath;

        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            if (!$book->save(false)) {
                throw new \Exception('Unable to save book');
            }
            $book->setNewAuthors($form->getAuthorIds());
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw new \DomainException($e->getMessage());
        }

    }
}
