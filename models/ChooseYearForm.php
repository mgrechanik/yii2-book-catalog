<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\entities\User;

/**
 * Login form
 */
class ChooseYearForm extends Model
{
    public $year;
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
