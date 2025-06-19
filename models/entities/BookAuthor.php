<?php

declare(strict_types=1);

namespace app\models\entities;

use Yii;

/**
 * This is the model class for table "bookauthors".
 *
 * @property int $id_b id книги
 * @property int $id_a id автора
 *
 * @property Author $a
 * @property Book $b
 */
class BookAuthor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bookauthors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_b', 'id_a'], 'required'],
            [['id_b', 'id_a'], 'integer'],
            [['id_b', 'id_a'], 'unique', 'targetAttribute' => ['id_b', 'id_a']],
            [['id_a'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['id_a' => 'id']],
            [['id_b'], 'exist', 'skipOnError' => true, 'targetClass' => Book::class, 'targetAttribute' => ['id_b' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_b' => 'id книги',
            'id_a' => 'id автора',
        ];
    }

    /**
     * Gets query for [[A]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Author::class, ['id' => 'id_a']);
    }

    /**
     * Gets query for [[B]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBook(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Book::class, ['id' => 'id_b']);
    }

}
