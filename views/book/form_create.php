<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use app\models\entities\Author;

/** @var yii\web\View $this */
/** @var app\models\forms\BookCreateForm $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>

    <?= $form->field($model, 'year')->textInput(['maxlength' => 4]) ?>

    <?= $form->field($model, 'isbn')->widget(\yii\widgets\MaskedInput::class, [
        'mask' => '999-9-999-99999-9',
    ]) ?>


    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <?php
        foreach ($model->authors as $key => $val) {
            print $form->field($model, $key)->dropDownList(['' => ''] + Author::getAuthotsList());
        }
    ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
