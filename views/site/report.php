<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \app\models\ChooseYearForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Отчет за ' . $model->year . ' год';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Выберите  год  :</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'year-form', 'method' => 'GET']); ?>

                <?= $form->field($model, 'year')->textInput(['maxLength' => 4]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Построить отчет', ['class' => 'btn btn-primary', 'name' => 'report-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <?php if ($report) { ?>
        <div class="row">
            <div class="col-lg-5">
                <h2>Список топ авторов:</h2>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Автор</th>
                        <th scope="col">Количество книг</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $i = 0;
        foreach ($report as $row) {
            $i++;
            ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= Html::encode($row['fio']) ?></td>
                            <td><?= Html::encode($row['count']) ?></td>
                        </tr>
                    <?php
        }
        ?>

                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>

</div>
