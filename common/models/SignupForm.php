<?php
namespace common\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Perfiles;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $type;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required', 'message' => Yii::$app->params['error_username']],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::$app->params['error_unique_username']],
            ['username', 'string', 'min' => 2, 'max' => 255],
            /*
            ['email', 'trim'],
            ['email', 'required', 'message' => Yii::$app->params['error_email']],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::$app->params['error_unique_email']],
            */
            ['password', 'required', 'message' => Yii::$app->params['error_password']],
            ['password', 'string', 'min' => 6, 'tooShort' => Yii::$app->params['error_password_longitud']],

            ['type','required', 'message' => Yii::$app->params['error_type']],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        //$user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->status = User::STATUS_ACTIVE;

        if($user->save()){
            $perfil = new Perfiles();
            $perfil->user_id = $user->id;
            $perfil->g05_tipo = $this->type;
            return $perfil->save();
        }

        return false;

    }


     public function attributeLabels()
    {
        return [
            'username' => Yii::$app->params['lbl_username'],
            //'email' => Yii::$app->params['lbl_email'],
            'password' => Yii::$app->params['lbl_password'],
            'type' => Yii::$app->params['lbl_type'],
        ];
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
