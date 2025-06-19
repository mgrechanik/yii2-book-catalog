<?php
/**
 * This file is part of the mgrechanik/yii2-book-catalog project
 *
 * @copyright Copyright (c) Mikhail Grechanik <mike.grechanik@gmail.com>
 * @license https://github.com/mgrechanik/yii2-book-catalog/blob/main/LICENSE.md
 * @link https://github.com/mgrechanik/yii2-book-catalog
 */
declare(strict_types=1);

namespace app\models;

use yii\base\Model;

/**
 * Login form
 */
class ChooseYearForm extends Model
{
    /**
     * @var Год
     */
    public string $year;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['year'], 'match', 'pattern' => '/^\d{4}$/'],
            // Иногда книги заводят на следующий год, поэтому плюс 1
            [['year'], 'number', 'min' => 1900, 'max' => intval(date('Y')) + 1],
        ];
    }
}
