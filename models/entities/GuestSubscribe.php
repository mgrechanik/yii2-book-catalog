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
 * @property int $id_a Id автора
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
            [['id_a'], 'integer'],
            [['phone'], 'string', 'max' => 12],
            [['phone'], 'match', 'pattern' => '/^\+7(\d){10}$/'],
            [['phone', 'id_a'], 'unique', 'targetAttribute' => ['phone', 'id_a']],
            [['id_a'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['id_a' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Телефон гостя',
            'id_a' => 'Id автора',
        ];
    }

    /**
     * Gets query for [[A]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'id_a']);
    }

}
