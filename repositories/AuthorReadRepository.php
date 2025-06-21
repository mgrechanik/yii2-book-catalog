<?php
/**
 * This file is part of the mgrechanik/yii2-book-catalog project
 *
 * @copyright Copyright (c) Mikhail Grechanik <mike.grechanik@gmail.com>
 * @license https://github.com/mgrechanik/yii2-book-catalog/blob/main/LICENSE.md
 * @link https://github.com/mgrechanik/yii2-book-catalog
 */
declare(strict_types=1);

namespace app\repositories;

use yii\db\Query;
use yii\db\Expression;

/**
 * Репозиторий для всех выборок, касающихся авторов
 */
class AuthorReadRepository
{
    /**
     * ТОП 10 авторов по указанному году
     */
    public function getTopTenAuthors(int $year): array
    {
        $query = new Query();
        $query
            ->select(['a.id', 'b.year','a.fio', 'count' => new Expression('COUNT(*)')])
            ->from(['ba' => 'book_authors'])
            ->leftJoin('authors a', 'ba.author_id = a.id')
            ->leftJoin('books b', 'ba.book_id = b.id')
            ->where(['b.year' => $year])
            ->groupBy('a.id, b.year, a.fio')
            ->orderBy('count desc')
            ->limit(10);

        return $query->all();


    }
}
