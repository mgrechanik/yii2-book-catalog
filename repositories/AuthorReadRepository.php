<?php

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
            ->from(['ba' => 'bookauthors'])
            ->leftJoin('authors a', 'ba.id_a = a.id')
            ->leftJoin('books b', 'ba.id_b = b.id')
            ->where(['b.year' => $year])
            ->groupBy('a.id, b.year')
            ->orderBy('count desc')
            ->limit(10);

        return $query->all();


    }
}
