<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use app\modules\payment\models\PaymentOperation;

/**
 * @var $user \app\models\User
 */
$account = $user->account;
$operationDataProvider = new ActiveDataProvider([
    'query' => $account->getPaymentOperations(),
    'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
]);
?>
<h1>Payments</h1>
<p><b>Account</b><br>Number:<?= $account->id ?><br>Balance: <?= $account->balance ?></p>
<?= Html::a('Transfer', ['transfer'], ['class' => 'btn btn-primary']) ?>&nbsp;
<?= Html::a('Deposit', ['deposit'], ['class' => 'btn btn-default']) ?>&nbsp;
<?= Html::a('Withdraw', ['withdraw'], ['class' => 'btn btn-default']) ?>&nbsp;
<?= Html::a('System information', ['system'], ['class' => 'btn btn-default']) ?>

<h2>Payment Operations</h2>
<?= GridView::widget([
    'options' => ['id' => 'hostess-grid'],
    'dataProvider' => $operationDataProvider,
    'columns' => [
        'amount' => [
            'value' => function(PaymentOperation $model) {
                return '<b class="' . ($model->amount > 0 ? 'text-success' : 'text-danger') . '">' . $model->amount . '</b>';
            },
            'attribute' => 'amount',
            'format' => 'raw',
        ],
        'description',
        'created_at',
        'transaction' => [
            'value' => function(PaymentOperation $model) {
                return
                    '<p>ID:' . $model->transaction->id . '</p>'
                    . '<p>Source Account: ' . $model->transaction->source_account_id . '</p>'
                    . '<p>Beneficiary Account: ' . $model->transaction->beneficiary_account_id . '</p>'
                    . '<p>Description: ' . $model->transaction->description . '</p>';
            },
            'label' => 'Transaction Details',
            'format' => 'raw',
        ],
    ],
]); ?>

