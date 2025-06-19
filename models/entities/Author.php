<?php

declare(strict_types=1);

namespace app\models\entities;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "authors".
 *
 * @property int $id
 * @property string|null $fio
 */
class Author extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'authors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fio'], 'default', 'value' => null],
            [['fio'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fio' => 'Фио',
        ];
    }

    /**
     * Gets query for [[Books]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooks(): \yii\db\ActiveQuery
    {
        return $this->hasMany(Book::class, ['id' => 'id_b'])->viaTable('bookauthors', ['id_a' => 'id']);
    }

    /**
     * Gets query for [[Subscribers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscribers(): \yii\db\ActiveQuery
    {
        return $this->hasMany(GuestSubscribe::class, ['id_a' => 'id']);
    }

    public static function getAuthotsList()
    {
        return ArrayHelper::map(self::find()->orderBy('id')->asArray()->all(), 'id', 'fio');
    }

}
