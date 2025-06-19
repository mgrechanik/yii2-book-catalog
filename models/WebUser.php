<?php

declare(strict_types=1);

namespace app\models;

class WebUser extends \yii\web\User
{
    public function getIsAdmin()
    {
        return ($this->isGuest || !$this->identity->isAdmin) ? false : true;
    }
}
