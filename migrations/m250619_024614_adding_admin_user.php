<?php

use yii\db\Migration;
use app\models\entities\User;
class m250619_024614_adding_admin_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $user = new User();
        $user->status = User::STATUS_ACTIVE;
        $user->email = 'admin@mail.ru';
        $user->username = $_ENV['ADMIN_USERNAME'];
        $user->is_admin = 1;
        $user->setPassword($_ENV['ADMIN_PASSWORD']);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if ($user = User::findOne(['email' => 'admin@mail.ru'])) {
            $user->delete();
        }
    }


}
