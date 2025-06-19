<?php

declare(strict_types=1);

namespace app\services;

use app\models\entities\GuestSubscribe;

/**
 * Автору создали новую книгу.
 * Надо об этом в очередь закинуть по всем подписчикам автора
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
