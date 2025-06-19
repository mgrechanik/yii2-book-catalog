<?php

declare(strict_types=1);

namespace app\services;

use app\models\entities\Author;
use app\models\entities\Book;

/**
 * Обработка отправки сообщения подписанту
 */
class NotifySubscriberJob extends \yii\base\BaseObject implements \yii\queue\JobInterface
{
    public string $phone;
    public int $book_id;

    public int $author_id;

    public function execute($queue)
    {
        // Просто сохраняю файл, проверяю что работает
        file_put_contents(\Yii::getAlias('@runtime/queuedata/tel' . $this->phone . ' - ' . $this->author_id . ' - ' . $this->book_id), 'выполнил');

        // отправляю sms
        if (($author = Author::findOne($this->author_id)) && ($book = Book::findOne($this->book_id))) {
            $text = urlencode('У автора ' . $author->fio . ' появилась книга ' . $book->name);
            $phone = trim($this->phone, '+');
            $key = \Yii::$app->params['smspilotkey'];
            $res = file_get_contents('https://smspilot.ru/api.php?send=' . $text . '&to=' . $phone . '&apikey=' . $key . '&format=json');
            $res = json_decode($res);
            if (isset($res->error)) {
                \Yii::warning('Не получилось отправить SMS');
            }
        }



    }
}
