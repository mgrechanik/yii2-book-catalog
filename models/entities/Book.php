<?php
/**
 * This file is part of the mgrechanik/yii2-book-catalog project
 *
 * @copyright Copyright (c) Mikhail Grechanik <mike.grechanik@gmail.com>
 * @license https://github.com/mgrechanik/yii2-book-catalog/blob/main/LICENSE.md
 * @link https://github.com/mgrechanik/yii2-book-catalog
 */
declare(strict_types=1);

namespace app\models\entities;

use app\services\BookImageServiceInterface;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property string $name Название книги
 * @property string|null $description Описание книги
 * @property string|null $isbn ISBN книги, если известен
 * @property int $year Год издания
 * @property string|null $photo Путь к картинке обложки
 * @property int $user_id id пользователя, добавившего книгу
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Author[] $as
 * @property Bookauthor[] $bookauthors
 * @property User $user
 */
class Book extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function init()
    {
        parent::init();
        $this->on(ActiveRecord::EVENT_AFTER_DELETE, [$this, 'handlerAfterDelete']);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books';
    }

    public static function create($name, $description, $isbn, $photo, $user_id, $year): self
    {
        $book = new static();
        $book->name = $name;
        $book->description = $description;
        $book->isbn = $isbn;
        $book->photo = $photo;
        $book->user_id = $user_id;
        $book->year = $year;
        return $book;
    }

    public function handlerAfterDelete($event)
    {
        $imgService = Yii::createObject(BookImageServiceInterface::class);
        if ($this->photo) {
            $imgService->deleteImage($this->photo);
        }
    }

    /**
     * Устанавливаю книге новых авторов
     *
     * @param int[] $newIds
     * @return void
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function setNewAuthors(array $newIds): void
    {
        // Проверяем существующих, присутствуют ли еще
        $existed = $this->bookauthors;
        $existedIds = [];
        foreach ($existed as $bookAuthor) {
            if (!in_array($bookAuthor->author_id, $newIds)) {
                $bookAuthor->delete();
            }
            $existedIds[] = $bookAuthor->author_id;
        }

        // Проверяем на новых
        foreach ($newIds as $idAuthor) {
            if (!in_array($idAuthor, $existedIds)) {
                $bookAuthor = new BookAuthor();
                $bookAuthor->book_id = $this->id;
                $bookAuthor->author_id = $idAuthor;
                // Сохраняю в очередь задачу, что у автора появилась новая книга
                // Эта задача, выполняясь, в свою очередь, создаст задачи по отправке оповещений подписчикам
                Yii::$app->queue->push(new \app\services\AuthorNewBookJob([
                    'book_id' => $this->id,
                    'author_id' => $idAuthor
                ]));
                if (!$bookAuthor->save()) {
                    throw new \Exception('Unable to save authors of the book');
                }
            }
        }
    }

    /**
     * Проверяю что данная книга была создана пользователем с этим id
     * @param int $user_id
     * @return bool
     */
    public function checkCreatedByUser(int $user_id): bool
    {
        return $this->user_id == $user_id;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название книги',
            'description' => 'Описание книги',
            'isbn' => 'ISBN книги',
            'photo' => 'Путь к картинке обложки',
            'year' => 'Год издания',
            'user_id' => 'id пользователя, добавившего книгу',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[As]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors(): \yii\db\ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])->viaTable('book_authors', ['book_id' => 'id']);
    }

    /**
     * Gets query for [[Bookauthors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookauthors(): \yii\db\ActiveQuery
    {
        return $this->hasMany(Bookauthor::class, ['book_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser(): \yii\db\ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
