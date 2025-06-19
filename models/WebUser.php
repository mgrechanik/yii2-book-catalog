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

class WebUser extends \yii\web\User
{
    /**
     * Админ ли это (в базе отметка в поле `is_admin`)
     * @return bool
     */
    public function getIsAdmin() : bool
    {
        return ($this->isGuest || !$this->identity->isAdmin) ? false : true;
    }
}
