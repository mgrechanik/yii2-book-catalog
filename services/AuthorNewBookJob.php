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

use app\models\entities\GuestSubscribe;

/**
 * Автору создали новую книгу.
 * Надо об этом в очередь закинуть задачи по уведомлению всех подписчиков автора
 */
class AuthorNewBookJob extends \yii\base\BaseObject implements \yii\queue\JobInterface
{
    public int $book_id;

    public int $author_id;

    public function execute($queue)
    {
        $subs = GuestSubscribe::find()->where(['id_a' => $this->author_id])->asArray()->all();
        foreach ($subs as $sub) {
            \Yii::$app->queue->push(new \app\services\NotifySubscriberJob([
                'phone' => $sub['phone'],
                'book_id' => $this->book_id,
                'author_id' => $this->author_id
            ]));
        }


    }
}
