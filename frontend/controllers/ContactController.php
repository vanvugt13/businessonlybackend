<?php

namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\User;
use frontend\components\Image;
use frontend\models\Chat;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use yii\db\Expression;

/**
 * Site controller
 */
class ContactController extends Controller
{

	public $enableCsrfValidation = false;
    public $user;
    
	public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        $getHeaders = Yii::$app->request->headers;
        $bearer_token = trim(str_replace('Bearer','',$getHeaders->get('Authorization')));
        $this->user     =       User::find()->where(['token'=>$bearer_token])->one();

        
       // return true;
        return parent::beforeAction($action);
    }
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    public function actionNotifications($code=null)
    {
	    $this->enableCsrfValidation = false;	    
	    if(Yii::$app->request->isPost)
	    {
		    $notifications_file = Yii::getAlias('@runtime').'/notifications.json';
		    $array=[];
		    if(file_exists($notifications_file))
		    {
			    $array = json_decode(file_get_contents($notifications_file),true);
			    if(!is_array($array))
			    {
				    $array=[];
			    }
		    }

	    		$rawdata = file_get_contents("php://input");
		        $post_value = (array)json_decode($rawdata);
			$array[] = $post_value;
			print_R($array);
		    file_put_contents($notifications_file,json_encode($array));
		    return json_encode('Gelukt!');
	    }

		    //return json_encode('KLaar!!!');
    }

    public function actionGetChats($id)
    {
        if(empty($this->user->id))
        {
            return json_encode(['error'=>'not authorized']);
        }
	    $array =[];
        $source_id =    $this->user->id;
        $ids=[$id,$source_id];
	    $chats = \frontend\models\Chat::find()->where(['in','destination_user_id',$ids])->andWhere(['in','source_user_id',$ids])->all();
	    foreach($chats as $chat)
	    {
		    $array[]	=	['id'=>$chat->id,
			    'message'=>$chat->message,
			    'way'=>($id == $chat->source_user_id)?"in":"out",
			    'created_at'=>date("H:i",$chat->created_at),
				'contact_id'=>$id
		    ];
	    }
	    return json_encode($array);
    }

    public function actionGetContactitem($id)
    {
        if(empty($this->user->id))
        {
            if(Yii::$app->request->method !== 'OPTIONS')
            throw new \yii\web\HttpException(401, 'not authorized'); 
            return json_encode(['error'=>'not authorized']);
        }
      
        $user=    User::find()->joinWith('company')->where(['=','user.id',$id])->one();
        if($user == null)
        {
            return json_encode(['error'=>'invalid id; User not found']);
        }
     
        $array    =   ['id'=>$user->id,
        'profielimage'=>$user->image,
        'name'=>$user->username,
        'phone_number'=>$user->phone_number,
        'email_address'=>$user->email_address,
        'company_name'=>$user->company?->name,
        'contactperson'=>$user->contactperson,
        'companyDescription'=>$user->company?->description,
        'companyUrl'=>$user->company?->company_url,
        'companyImage'=>$user->company?->getFilename(),
        'description'=>$user->description];
	    return json_encode($array);
    }

    public function actionGetOwnContact()
    {
        if(empty($this->user->id))
        {
            return json_encode(['error'=>'not authorized']);
        }
      
        $otherUsers=    User::find()->joinWith('company')->where(['=','user.id',$this->user->id])->all();
        $array=[];
        if(count($otherUsers) > 0)
        {
            foreach($otherUsers as $users)
            {
                $array[]    =   ['id'=>$users->id,
              //  'image'=>$users->image,
                'userImage'=>$users->getFilename(),
                'name'=>$users->username,
                'company_name'=>$users->company?->name,
                'contactperson'=>$users->contactperson,
                'companyDescription'=>$users->company?->description,
                'companyUrl'=>$users->company?->company_url,
                'companyImage'=>$users->company?->getFilename(),
                'phone_number'=>$users->phone_number,
                'email_address'=>$users->email,
                'description'=>$users->description];
            }
        }
	    return json_encode($array);
    }

    public function actionSetPhonenumber(){
        if(empty($this->user->id))
        {
            return json_encode(['error'=>'not authorized']);
        }
            
        $rawdata = file_get_contents("php://input");
		$post_value = (array)json_decode($rawdata);

        $this->user->phone_number = $post_value[0];
            if($this->user->save()){
                return json_encode(['success'=>'gelukt']);
            }
            print_R($this->user->getErrors());
            exit;
             return json_encode(['success'=>'niet gelukt']);

        
    

    }

    public function actionImageUpload(){
        if(empty($this->user->id))
        {
            return json_encode(['error'=>'not authorized']);
        }
        $image = \yii\web\UploadedFile::getInstanceByName('image');

        $data=  base64_encode(file_get_contents($image->tempName));

      
        if(1==1){
            //check extension
            $this->user->imageApp = $data;

            if($this->user->save()){
                return json_encode(['success'=>'ge55lukt'.$data]);
            }
            print_R($this->user->getErrors());
            exit;
             return json_encode(['success'=>'niet gelukt']);
        }
        
    

    }

    public function actionGetLatestChats(){
        if(empty($this->user->id)) {
            return json_encode(['error'=>'not authorized']);
        }

        $subQuery = Chat::find()
        ->select([
            new Expression('(case when source_user_id='.$this->user->id.' then destination_user_id else source_user_id end) as chat_user_id'),
            new Expression('id AS message_id'),
            //new Expression('max(created_at) as last_message_datetime'),
           // new Expression('max(id) as last_message_id'),
        ]
        )
        ->orWhere(['source_user_id'=>$this->user->id])
        ->orWhere(['destination_user_id'=>$this->user->id]);
            
        $returnarray = [];
        $chats = User::find()->leftJoin(['chatquery'=>$subQuery],'chatquery.chat_user_id=user.id')
        ->select([new Expression('(select message from chat where id=max(chatquery.message_id)) as last_message'),
        new Expression('(select created_at from chat where id=chatquery.message_id) as last_message_datetime'),
        'max(message_id) as last_message_id',
        'chat_user_id',
        ])
        ->groupBy('chat_user_id')

        // echo $chats->createCommand()->getRawSql();
        // exit;
        ->all();
        foreach($chats as $chat){
            $usermodel = User::findOne($chat->chat_user_id);
            
            if($usermodel){
                $profileImage = $usermodel->image;
            }
            if($chat->chat_user_id == null){
                continue;
            }
            $data = ['partner_id'=>$chat->chat_user_id,
         //       'profile_image'=>$profileImage??'',
                'user_image'=>$chat->user->getFilename(),
                'last_message'=>$this->truncateMessage($chat->last_message),
                'display_name'=>$usermodel->contactperson??'',
                'last_message_datetime'=>date("d-m-Y H:i:s",$chat->last_message_datetime)];
            $returnarray[] = $data;
        }
       
        return json_encode($returnarray);
       


    }

    private function truncateMessage($message,$length=45){
        if(strlen($message) > $length){
            return substr($message,0,$length).'...';
        }
        return $message;
    }
    public function actionGetContacts($searchTerm=null)
    {
        if(empty($this->user->id))
        {
           return json_encode(['error'=>'not authorized']);
        }
      
        $otherUsers=    User::find()
            ->joinWith('company')
            ->where(['not in','user.id',$this->user->id]);
        if($searchTerm != null){
            $otherUsers->andWhere(['like','company.name',$searchTerm]);
        }
        if($searchTerm != null){
            $otherUsers->orWhere(['like','user.contactperson',$searchTerm]);
        }
        $otherUsers->orderBy('user.contactperson');
        $otherUsers = $otherUsers->all();
        $array=[];
        if(count($otherUsers) > 0)
        {
            foreach($otherUsers as $users)
            {
               
                $array[]    =   ['id'=>$users->id,
               // 'image'=>$users->image,
                'profielimage'=>$users->image,

                'name'=>$users->username,
                'company_name'=>$users->company?->name,
                'contactperson'=>$users->contactperson,
                'companyDescription'=>$users->company?->description,
                'companyUrl'=>$users->company?->company_url,
                'companyImage'=>$users->company?->getFilename(),
            ];
            }
        }
	    return json_encode($array);
    }
    public function removeEndpoint($endpoint)
    {

	    $notifications_file = Yii::getAlias('@runtime').'/notifications.json';
	    $array = json_decode(file_get_contents($notifications_file),true);
	    foreach($array as $key=>$item)
	    {
		    if($item['endpoint'] == $endpoint)
		    {
			    unset($array[$key]);
		    }
	    }
	    file_put_contents($notifications_file,json_encode($array));
    }
    public function actionSendnotifcations()
    {
//{"publicKey":"BAiH9NsTi0Qh5FqWcUY7TiaWw1nPST63dvrvooj0LjXYBJxrJ8wEdqxNEiJUZnE7AzQ3qvopql08fRw5FvNFg_4","privateKey":"BtQgFaO_aQhyphXQvEKzVIeZnmYs6nnfOKL1W0dcNtc"}
	    $auth = [
    'VAPID' => [
        'subject' => 'mailto:erik@familie-van-vugt.nl', // can be a mailto: or your website address
        'publicKey' => "BAiH9NsTi0Qh5FqWcUY7TiaWw1nPST63dvrvooj0LjXYBJxrJ8wEdqxNEiJUZnE7AzQ3qvopql08fRw5FvNFg_4", // (recommended) uncompressed public key P-256 encoded in Base64-URL
        'privateKey' => "BtQgFaO_aQhyphXQvEKzVIeZnmYs6nnfOKL1W0dcNtc", // (recommended) in fact the secret multiplier of the private key encoded in Base64-URL
    ],
	    ];
	    $notifications_file = Yii::getAlias('@runtime').'/notifications.json';
	    $array = json_decode(file_get_contents($notifications_file),true);
	    $webPush = new WebPush($auth);
	    $notifications=[];
	    foreach($array as $item)
	    {
		    $payload = [ 'notification'=>[
			    'title'=>'hello',
			    'options'=>[
			    'body'=>'berichtje',
			    'icon'=>"assets/main-page-logo-small-hat.png",
			    'badge'=>"assets/main-page-logo-small-hat.png",
			    'vibrate'=>[ 100,50,100],
			    "data"=>[
			    "dateOfArrival"=>time(),
			    'primaryKey'=>1,
			    'silent'=>false,
			    ],
			    ],
			    'actions'=>[["action"=>"https://nu.nl","title"=>"go to site"]],
		    ]
		];
		    $notification =         ['subscription' => Subscription::create($item),'payload'=>json_encode($payload)
	];
		$notifications[]	=	$notification;
	    }
// send multiple notifications with payload
	    foreach ($notifications as $notification) {
    		$webPush->queueNotification(
	        $notification['subscription'],
		        $notification['payload'] // optional (defaults null)
		);
	    }
	    $i=0;
	    $already_done=[];
	    foreach ($webPush->flush() as $report) {
		    echo '<pre>';
    $endpoint = $report->getRequest()->getUri()->__toString();
		    echo  "number $i<br>";
		    if(in_array($endpoint,$already_done))
		    {
			    echo "verwijeren! met $endpoint";
//			    $this->removeEndpoint($endpoint);
			    continue;
		    }
		    $already_done[]	=	$endpoint;
    if ($report->isSuccess()) {
        echo "[v] Message sent successfully for subscription {$endpoint}.";
    } else {
	    echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
	    $this->removeEndpoint($endpoint);
    }
		    $i++;
}
    }
}
