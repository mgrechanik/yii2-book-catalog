<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\entities\Author $model */
/** @var yii\bootstrap5\ActiveForm $form */

$this->title = 'Страница автора : "' . $model->fio . '"';
if (Yii::$app->user->isAdmin) {
    $this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['index']];
}
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="author-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (Yii::$app->user->isAdmin) { ?>
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php } ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'fio',
        ],
    ]) ?>

</div>

<?php
    if (Yii::$app->user->isGuest) {
        ?>
    <div class="subscribe-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($modelSubscribe, 'phone')->textInput(['maxlength' => 12]) ?>

        <div class="form-group">
            <?= Html::submitButton('Подписаться на автора', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php
    }
?>