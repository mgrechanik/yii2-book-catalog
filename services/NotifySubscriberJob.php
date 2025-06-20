<?php
/**
 * This file is part of the mgrechanik/yii2-book-catalog project
 *
 * @copyright Copyright (c) Mikhail Grechanik <mike.grechanik@gmail.com>
 * @license https://github.com/mgrechanik/yii2-book-catalog/blob/main/LICENSE.md
 * @link https://github.com/mgrechanik/yii2-book-catalog
 */
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
        // file_put_contents(\Yii::getAlias('@runtime/queuedata/tel' . $this->phone . ' - ' . $this->author_id . ' - ' . $this->book_id), 'выполнил');

        // отправляю sms
        $cache = \Yii::$app->cache;
        $key = 'notify about book - ' . $this->book_id . ' - ' . $this->author_id;
        $data = $cache->get($key);
        if ($data === false) {
            if (($author = Author::findOne($this->author_id)) && ($book = Book::findOne($this->book_id))) {
                $data = urlencode('У автора ' . $author->fio . ' появилась книга ' . $book->name);
            } else {
                $data = '';
            }
            $cache->set($key, $data);
        }

        if ($data) {
            $apiKey = \Yii::$app->params['smspilotkey'];
            $res = file_get_contents('https://smspilot.ru/api.php?send=' . $data . '&to=' . $this->phone . '&apikey=' . $apiKey . '&format=json');
            $res = json_decode($res);
            if (isset($res->error)) {
                \Yii::warning('Не получилось отправить SMS');
            }
        }

    }
}
