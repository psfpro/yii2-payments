<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use app\modules\payment\models\PaymentOperation;

/**
 * @var $user \app\models\User
 */
$account = \app\modules\payment\models\PaymentAccount::findOne(1);
$operationDataProvider = new ActiveDataProvider([
    'query' => $account->getPaymentOperations(),
    'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
]);
?>
<h1>System Account</h1>
<p><b>Account</b><br>Number:<?= $account->id ?><br>Balance: <?= $account->balance ?></p>
<?= Html::a('Return', ['index'], ['class' => 'btn btn-primary']) ?>&nbsp;

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

