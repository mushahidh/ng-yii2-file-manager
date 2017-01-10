<?php

namespace api\modules\v1\controllers;
//namespace \common\models;
use Yii;
use common\models\User;
use common\models\SignupForm;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior ;
use yii\db\Expression;
use common\components\AccessRule;
use yii\filters\AccessControl;
class UserController extends ActiveController
{
  public $modelClass = 'common\models\User';
   


  public function behaviors()
  {
   // $behaviors[] = parent::behaviors();
    return ArrayHelper::merge(parent::behaviors(),[
     [
   
     'class' => CompositeAuth::className(),
       'except' => ['signup', 'login', 'reset-password','options','request-password-reset'],
     'authMethods' => [
     HttpBearerAuth::className(),
     ],
     ],
        'corsFilter' => [
            'class' => \yii\filters\Cors::className(),
        ],
     'timestamp' => [
     'class' => TimestampBehavior::className(),

     ],
      [
          'class' => 'yii\filters\ContentNegotiator',
          'only' => ['view', 'index'],  // in a controller
              // if in a module, use the following IDs for user actions
              // 'only' => ['user/view', 'user/index']
              'formats' => [
                  'application/json' => Response::FORMAT_JSON,
              ],
           
          ],
     'access' => [
     'class' => AccessControl::className(),
    // We will override the default rule config with the new AccessRule class
     'ruleConfig' => [
     'class' => AccessRule::className(),
     ],
     'only' => ['create', 'index', 'delete','update','stats','deactivate-account'],
     'rules' => [[
     'actions' => ['create'],
     'allow' => true,
            // Allow users, moderators and admins to create
     'roles' => [
     User::ROLE_USER,

     ],
     ],
     [
     'actions' => ['index'],
     'allow' => true,
            // Allow moderators and admins to update
     'roles' => [

     User::ROLE_ADMIN
     ],
     ],
          [
     'actions' => ['update'],
     'allow' => true,
      'matchCallback' => function ($rule, $action) {
                        if ( Yii::$app->user->identity->id ==  Yii::$app->getRequest()->getQueryParam('id')) {
                            return true;   // let them in
                        }
                        return false;   // get lost
                    },
     'roles' => [
     User::ROLE_USER,
 


     ],
     ],
     [
     'actions' => ['delete','deactivate-account'],
     'allow' => true,
            // Allow admins to delete
     'roles' => [
     User::ROLE_ADMIN
     ],
     ],

         [
     'actions' => ['stats'],
     'allow' => true,
            // Allow admins to delete
     'roles' => [
     User::ROLE_ADMIN
     ],
     ],
     ],
     ],
     'contentNegotiator' => [
     'formats' => [
     'application/json' => Response::FORMAT_JSON,
     ],
     ],


     ] );

  }
  public function actionRequestPasswordReset()
    {
        $model = new \common\models\PasswordResetRequestForm();
        if (!$model->load(Yii::$app->request->post()) || !$model->validate()) {
            return $model;
        }

        if (!$model->sendEmail()) {
           Yii::t('app', 
                'Sorry, we are unable to reset password for email provided.');
        }

        return Yii::t('app', 'Check your email for further instructions.');

        
    }

    /**
     * Resets password.
     *
     * @param  string $token Password reset token.
     * @return string|\yii\web\Response
     *
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new \common\models\ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        $post=Yii::$app->request->post();

     
        $user=\common\models\User::findByPasswordResetToken($token);
      
        $user->password_hash = Yii::$app->security->generatePasswordHash($post['password']);
        $user->removePasswordResetToken();
        $user->access_token=Yii::$app->getSecurity()->generateRandomString();
        //return $user;
        if(!$user->save())
        {
            return $user->errors;
        }
        

        return  Yii::t('app', 'New password was saved.');
    
    }

    public function actionActivateAccount($token)
    {
        try {
            $user = new AccountActivation($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if (!$user->activateAccount()) {
         return "Not activated";
        }

      Yii::t('app', 
                'Your account is activated');
           
    
  }

  public function actionLogin() {
    $model = new \common\models\LoginForm();

    if ($model->load(\Yii::$app->getRequest()->getBodyParams(), '') && $model->login()) {  
      $data=[

        'access_token'=>\Yii::$app->user->identity->getAuthKey(),
       
      
      ];
      return $data;

    }

     else {
      Yii::$app->response->statusCode = 422;
      return  $model->firstErrors;
    }
  }

        private function signupWithActivation($user)
    {
        // sending email has failed
      $model= new \common\models\SignupForm();
        if (!$model->sendAccountActivationEmail($user)) {
            // display error message to user
           return Yii::t('app', 
                'We couldn\'t send you account activation email, please contact us.');

            // log this error, so we can debug possible problem easier.
            return Yii::t('app', 
                'could not sign up. 
                 Possible causes: verification email could not be sent.');
           }

        // everything is OK
        return Yii::t('app', 'To be able to log in, you need to confirm your registration. 
                Please check your email, we have sent you a message.');
    }

  public function actionSignup()
  {
     $user = new User();

    $user->scenario = User::SCENARIO_SIGNUP;
    $user->load(Yii::$app->getRequest()->getBodyParams(), '');
  //  return $user;
    $user->generateAccountActivationToken();
    $six_digit_random_number = mt_rand(100000, 999999);
    $user->verification_pin=$six_digit_random_number;

    ///////////////////////////////////////////////
    // For the time arrangment
    $user->status=User::STATUS_ACTIVE;
   // $user->sendEmail($user->email,$six_digit_random_number);
           // $user->generateAuthKey();
    if($user->save())
    {
     return $user;
    }

   else
   {
    Yii::$app->response->statusCode = 422;
   return $user->firstErrors;
  }




}



}




