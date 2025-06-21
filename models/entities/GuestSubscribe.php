<?php
/**
 * This file is part of the mgrechanik/yii2-book-catalog project
 *
 * @copyright Copyright (c) Mikhail Grechanik <mike.grechanik@gmail.com>
 * @license https://github.com/mgrechanik/yii2-book-catalog/blob/main/LICENSE.md
 * @link https://github.com/mgrechanik/yii2-book-catalog
 */
declare(strict_types=1);

namespace app\models\entities;

/**
 * This is the model class for table "guest_subscribes".
 *
 * @property int $id
 * @property string $phone Телефон гостя
 * @property int $author_id Id автора
 *
 * @property Author $a
 */
class GuestSubscribe extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'guest_subscribes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone'], 'required'],
            [['author_id'], 'integer'],
            [['phone'], 'string', 'max' => 15],
            [['phone'], 'match', 'pattern' => '/^\+7 (\d){3}-(\d){3}-(\d){4}$/'],
            ['phone', 'filter', 'filter' => function ($value) {
                return strtr($value, ['+' => '', ' ' => '', '-' => '']);
            }],
            [['phone', 'author_id'], 'unique', 'targetAttribute' => ['phone', 'author_id'], 'message' => 'Данный телефон уже подписан на этого автора'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Ваш телефон',
            'author_id' => 'Id автора',
        ];
    }

    /**
     * Gets query for [[A]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }

}
