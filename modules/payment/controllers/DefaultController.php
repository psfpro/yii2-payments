<?php

namespace app\modules\payment\controllers;

use app\models\User;
use app\modules\payment\entity\Account;
use app\modules\payment\entity\Deposit;
use app\modules\payment\entity\Withdraw;
use app\modules\payment\models\TransferForm;
use app\modules\payment\service\PaymentService;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Default controller for the `payment` module
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'user' => $this->identity(),
        ]);
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionSystem()
    {
        return $this->render('system', [
            'user' => $this->identity(),
        ]);
    }

    public function actionTransfer()
    {
        $model = new TransferForm();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->isConfirmed()) {
                try {
                    $this->getPaymentService()->transfer($model->getTransferTransaction());

                    return $this->render('transfer-result', ['success' => true]);
                } catch (\Exception $exception) {
                    return $this->render('transfer-result', ['success' => false, 'message' => $exception->getMessage()]);
                }
            }

            return $this->render('transfer-confirmation', [
                'model' => $model,
            ]);
        }

        return $this->render('transfer', [
            'model' => $model,
        ]);
    }

    public function actionDeposit()
    {
        // TODO: Implement form and integration with fiat

        try {
            $deposit = new Deposit(new Account($this->identity()->account->id), 10000);
            $this->getPaymentService()->deposit($deposit);

            return $this->render('deposit', ['success' => true]);
        } catch (\Exception $exception) {
            return $this->render('deposit', ['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function actionWithdraw()
    {
        // TODO: Implement form and integration with fiat


        try {
            $withdraw = new Withdraw(new Account($this->identity()->account->id), 10000);
            $this->getPaymentService()->withdraw($withdraw);

            return $this->render('withdraw', ['success' => true]);
        } catch (\Exception $exception) {
            return $this->render('withdraw', ['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    /**
     * @return User
     */
    protected function identity()
    {
        /** @var User $user */
        $user = \Yii::$app->user->getIdentity();

        return $user;
    }

    /**
     * @return PaymentService
     */
    protected function getPaymentService()
    {
        return new PaymentService();
    }
}
