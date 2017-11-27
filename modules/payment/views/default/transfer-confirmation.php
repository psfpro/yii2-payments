<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @author Sergey Pantushin
 *
 * @var $model \app\modules\payment\models\TransferForm
 */

$this->title = 'Transfer';
$this->params['breadcrumbs'][] = $this->title;

$transfer = $model->getTransferTransaction();
?>
<div>
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to transfer funds:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'username')->staticControl() ?>
    <?= Html::activeHiddenInput($model, 'username') ?>

    <?= $form->field($model, 'amount')->staticControl() ?>
    <?= Html::activeHiddenInput($model, 'amount') ?>

    <table class="table">
        <tr>
            <td>Source account</td>
            <td><?= $transfer->getSourceAccount()->getNumber() ?></td>
        </tr>
        <tr>
            <td>Beneficiary account</td>
            <td><?= $transfer->getBeneficiaryAccount()->getNumber() ?></td>
        </tr>
        <tr>
            <td>Amount</td>
            <td><?= $transfer->getAmount() ?></td>
        </tr>
        <?php foreach ($transfer->getCharges() as $charge) { ?>
        <tr class="info">
            <td><?= $charge->getDescription() ?></td>
            <td><?= $charge->getChargeAmount($transfer->getAmount()) ?></td>
        </tr>
        <?php } ?>
        <tr class="info">
            <td>Total Charge</td>
            <td><?= $transfer->getTotalCharge() ?></td>
        </tr>
        <tr>
            <td><b>Total</b></td>
            <td><?= $transfer->getAmountWithTotalCharge() ?></td>
        </tr>
    </table>

    <?= Html::activeHiddenInput($model, 'confirm', ['value' => true]) ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Confirm', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>