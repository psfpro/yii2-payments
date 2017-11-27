<?php
/**
 * @author Sergey Pantushin
 */

namespace app\models;


use app\modules\payment\models\PaymentAccount;
use yii\base\Model;

class RegistrationForm extends Model
{
    /**
     * @var string username
     */
    public $username;
    /**
     * @var string password
     */
    public $password;
    /**
     * @var User
     */
    protected $user;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'email'],
            ['username', 'string', 'max' => 255],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'This email address has already been taken.'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'password' => 'Password',
        ];
    }
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signUp()
    {
        if (!$this->validate()) {
            return null;
        }
        $transaction = \Yii::$app->getDb()->beginTransaction();
        $this->user = new User();
        $this->user->setAttributes($this->attributes);
        $this->user->setPassword($this->password);
        $this->user->auth_key = \Yii::$app->getSecurity()->generateRandomString();
        if (empty($this->account_id)) {
            $account = new PaymentAccount();
            $account->balance = 0;
            $account->save();

            $this->user->account_id = $account->id;
        }
        if ($this->user->save()) {
            $transaction->commit();
            \Yii::$app->user->login($this->user);

            return $this->user;
        } else {
            $transaction->rollBack();

            return null;
        }
    }
    /**
     * @return User|null
     */
    public function getUser()
    {
        return $this->user;
    }
}