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
use DateTime;
use frontend\models\Chat;
use frontend\models\EventSponsor;
use frontend\models\Post;
use frontend\models\PostSeen;
use frontend\models\PushSubscribers;
use frontend\models\Subscribe;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use yii\db\Expression;
use yii\db\Query;

/**
 * Site controller
 */
class ApiController extends Controller
{

    public $enableCsrfValidation = false;
    public $user;
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        $getHeaders = Yii::$app->request->headers;
        $bearer_token = trim(str_replace('Bearer', '', $getHeaders->get('Authorization')));
       
        $this->user     =       User::find()->where(['token' => $bearer_token])->one();

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
    public function actionLogin2()
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
    // public function actionContact()
    // {
    //     $model = new ContactForm();
    //     if ($model->load(Yii::$app->request->post()) && $model->validate()) {
    //         if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
    //             Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
    //         } else {
    //             Yii::$app->session->setFlash('error', 'There was an error sending your message.');
    //         }

    //         return $this->refresh();
    //     }

    //     return $this->render('contact', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    // public function actionAbout()
    // {
    //     return $this->render('about');
    // }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    // public function actionSignup()
    // {
    //     $model = new SignupForm();
    //     if ($model->load(Yii::$app->request->post()) && $model->signup()) {
    //         Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
    //         return $this->goHome();
    //     }

    //     return $this->render('signup', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    // public function actionRequestPasswordReset()
    // {
    //     $model = new PasswordResetRequestForm();
    //     if ($model->load(Yii::$app->request->post()) && $model->validate()) {
    //         if ($model->sendEmail()) {
    //             Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

    //             return $this->goHome();
    //         }

    //         Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
    //     }

    //     return $this->render('requestPasswordResetToken', [
    //         'model' => $model,
    //     ]);
    // }

    public function actionDeleteConversation(int $partner_id){
            if(!$this->checkAccess()){
                    return json_encode(['error'=>'not authorized']);
            }
            $deleted = 0;
            $deleted += Chat::deleteAll(['source_user_id'=>$this->user->id,'destination_user_id'=>$partner_id]);
            $deleted += Chat::deleteAll(['source_user_id'=>$partner_id,'destination_user_id'=>$this->user->id]);
            
            return json_encode($deleted?['success'=>'total '.$deleted.' chats verwijderd']:['error'=>'not authorized']);
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
    // public function actionVerifyEmail($token)
    // {
    //     try {
    //         $model = new VerifyEmailForm($token);
    //     } catch (InvalidArgumentException $e) {
    //         throw new BadRequestHttpException($e->getMessage());
    //     }
    //     if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
    //         Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
    //         return $this->goHome();
    //     }

    //     Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
    //     return $this->goHome();
    // }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    // public function actionResendVerificationEmail()
    // {
    //     $model = new ResendVerificationEmailForm();
    //     if ($model->load(Yii::$app->request->post()) && $model->validate()) {
    //         if ($model->sendEmail()) {
    //             Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
    //             return $this->goHome();
    //         }
    //         Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
    //     }

    //     return $this->render('resendVerificationEmail', [
    //         'model' => $model
    //     ]);
    // }

    public function actionNotifications($code = null)
    {
        $this->enableCsrfValidation = false;
        if (Yii::$app->request->isPost) {

            // $notifications_file = Yii::getAlias('@runtime') . '/notifications.json';
            // $array = [];
            // if (file_exists($notifications_file)) {
            //     $array = json_decode(file_get_contents($notifications_file), true);
            //     if (!is_array($array)) {
            //         $array = [];
            //     }
            // }

            $rawdata = file_get_contents("php://input");
            $post_value = (array)json_decode($rawdata);
            $array[] = $post_value;
            $pushSubscriber = new PushSubscribers();
            $pushSubscriber->endpoint = $post_value["endpoint"]??null;
            $pushSubscriber->keys     = serialize($post_value["keys"]??null);
            $pushSubscriber->expirationDate = $post_value["expirationDate"]??null;
            $pushSubscriber->raw_data   =   $rawdata;
            $pushSubscriber->save();
            // file_put_contents($notifications_file, json_encode($array));
            return json_encode('Gelukt!');
        }

        //return json_encode('KLaar!!!');
    }

    // public function actionAllnews($value = 'all')
    // {
    //     $all_items    =    [];
    //     $news1    = [
    //         'header' => 'item1',
    //         'body' => 'body1',
    //         'footer' => 'Footer1',
    //         'summary' => 'Summary1',
    //         'id' => 1,
    //     ];

    //     $news2    = [
    //         'header' => 'item2',
    //         'body' => 'body2',
    //         'footer' => 'Footer2',
    //         'summary' => 'Summary2',
    //         'id' => 2,
    //     ];
    //     $news3    = [
    //         'header' => 'item3',
    //         'body' => 'body3',
    //         'footer' => 'Footer3',
    //         'summary' => 'Summary3',
    //         'id' => 3,
    //     ];

    //     $all_items[]    =    $news1;
    //     $all_items[]    =    $news2;
    //     if ($value != 'all')
    //         $all_items[]    =    $news3;
    //     return json_encode($all_items);
    // }

    public function actionCheckLogin()
    {

        return json_encode(['success' => $this->user ? true : false]);
    }

    public function actionResetPasswordRequest()
    {
        $rawdata = file_get_contents("php://input");
        $post_value = (array)json_decode($rawdata);
        if(isset($post_value[0])){
            $email = $post_value[0];
            $user = User::find()->where(['email'=>$email])->one();
            if($user != null)  $user->sendResetPasswordLink();

        }
        
       
        return json_encode(['success' => true]);
    }

    public function actionBcNewsItem($id){
        if(empty($this->user->id))
        {
            return json_encode(['error'=>'not authorized']);
        }
        $post  =   Post::find()
        ->joinWith('user')
        ->select([
            'post.id',
            'post.user_id',
            'post.title',
            'post.description',
          //  'post.image',
         //   'user.image as profile_image',
            'post.subscribe_news',
            'post.created_at',
            new Expression('ifnull((select user_id from post_seen where post_id=post.id and user_id='.$this->user->id.'),0) as have_seen')
        ])
        ->andWhere(['like','category',Post::CATEGORY_NEWS])
        ->andWhere(['post.id'=>$id]);

        $query = new Query();
        $query->select(['*'])->from($post)->orderBy('have_seen');
            $post = (array)$query->one();

            //$subscribed_users =[];
            $subscribed_users = Subscribe::find()
                ->select(['user_id'])
                ->where(['news_id'=>$post["id"]])
                ->asArray()
                ->all();
            
                $postmodel = Post::find()->with('user')->select(['id','user_id','title','description','category','image', 'unique_id'])->where(['id'=>$post["id"]])->one();
                $afbeelding =$postmodel->getFilename();
                if(empty($afbeelding)){
                    $afbeelding = $postmodel->user->company->getFilename();
                }
            $array = [
                'id'=>$post["id"],
                'user_id'=>$post["user_id"],
                'have_seen'=>$post["have_seen"]?1:0,
            //    'profile_image'=>$post['profile_image'],
                'user_image'=>$postmodel->user->getFilename(),
                'titel'=>$post['title'],
                'intro'=>$post['title'],
                'datum'=>date("Y-m-d H:i:s",$post["created_at"]),
                'tekst'=>$post["description"],
                'category'=>1,
                'afbeelding'=>$afbeelding,
                'url'=>User::baseUrl().'/api/bc-news-item?id='.$post["id"],
                'subscribe_news'=>$post["subscribe_news"],
                'subscibed_users'=>$subscribed_users,
            ];

        return json_encode($array);
    }

    public function actionSubscribedNews($id){
        if(empty($this->user->id))
        {
            return json_encode(['error'=>'not authorized']);
        }
        $subscribes = Subscribe::find()->where(['news_id'=>$id])->all();
        $array= [];
        foreach($subscribes as $subscribe){
            $array[] = [
                'id'=>$subscribe->id,
             //   'profile_image'=>$subscribe->user->image,
                'user_image'=>$subscribe->user->getFilename(),
                'user_id'=>$subscribe->user_id,
            ];
        }
        return json_encode($array);
    }
    public function actionSubscribeNews($id){
        if(empty($this->user->id))
        {
            return json_encode(['error'=>'not authorized']);
        }
        if(Subscribe::add(user_id:$this->user->id,news_id:$id)){
            return $this->actionSubscribedNews($id);
        }
    }

    public function actionGetAllEvents(){
        if(empty($this->user->id))
        {
            return json_encode(['error'=>'not authorized']);
        }
        $events = Post::find()
        ->andWhere(['like','category',Post::CATEGORY_EVENTS])
        ->all();

        $result = [];
        foreach($events as $event){
            $result[] = [
                'id'=>$event->id,
                'starttijd'=>date(DateTime::ATOM,$event->event_date),
                'titel'=>$event->title,
                'omschrijving'=>$event->description,
                'type'=>10,
                'thuisClub'=>'',
                'uitClub'=>'',
                'thuisLogo'=>'',
                'uitLogo'=>'',
                'afbeelding'=>$event->getFilename(),
            ];
        }
        return json_encode($result);
    }

    private function checkAccess(){
        if(empty($this->user->id))  {
            return false;
        }
        return true;
    }
    public function actionBcNews(){
        if(!$this->checkAccess()){
                return json_encode(['error'=>'not authorized']);
        }
        $posts  =   Post::find()
        ->joinWith('user')
        ->select([
            'post.id',
            'post.user_id',
            'post.title',
            'post.description',
            'post.unique_id',
           // 'post.image',
         //   'user.image as profile_image',
            'post.created_at',
            new Expression('ifnull((select user_id from post_seen where post_id=post.id and user_id='.$this->user->id.'),0) as have_seen')
        ])
        ->andWhere(['like','category',Post::CATEGORY_NEWS]);

        $query = new Query();
        $query->select(['*'])->from($posts)->orderBy('created_at desc');
            $posts = $query->all();
        $array  = [];
        foreach($posts as $post){
            $postmodel = Post::findOne($post["id"]);
            $array[] = [
                'id'=>$post["id"],
                'user_id'=>$post["user_id"],
                'have_seen'=>$post["have_seen"]?1:0,
            //    'profile_image'=>$post['profile_image'],
                'user_image'=>$postmodel->user->getFilename(),
                'titel'=>$post['title'],
                'intro'=>$post['title'],
                'datum'=>date("Y-m-d H:i:s",$post["created_at"]),
                'tekst'=>$post["description"],
                'category'=>1,
                'afbeelding'=>$postmodel->getFilename()??$postmodel->user->company->getFilename(),
                'url'=>User::baseUrl().'/api/bc-news-item?id='.$post["id"],
            ];

        }
        return json_encode($array);
    }

    public function actionActivePostUsers(){

        if(empty($this->user->id))
        {
            return json_encode(['error'=>'not authorized']);
        }
        $posts  =   Post::find()
        ->joinWith('user')
        ->select([
            'post.id',
            'post.user_id',
           // 'user.image',
            'post.created_at as created_at',
            new Expression('ifnull((select user_id from post_seen where post_id=post.id and user_id='.$this->user->id.'),0) as have_seen')
        ])
        ->andWhere(['like','category',Post::CATEGORY_POST])
        ->andWhere(['>=','visible_till',time()]);

        $query = new Query();
        $query->select(['*'])->from($posts)->orderBy('have_seen,created_at desc');
            $posts = $query->all();
        $array  = [];
        foreach($posts as $post){
            $postmodel = Post::find()->with('user')->select(['id','user_id','unique_id','title','description','image'])->where(['id'=>$post["id"]])->one();
            $array[] = [
                'id'=>$post["id"],
                'user_id'=>$post["user_id"],
                'have_seen'=>$post["have_seen"]?1:0,
             //   'profile_image'=>$post['image'],
                'user_image'=>$postmodel->user->getFilename(),
            ];
        }
        return json_encode($array);
    }
    public function actionPost($id){
        if(empty($this->user->id))
        {
            return json_encode(['error'=>'not authorized']);
        }
        $post  =   Post::find()
            ->select([
                'id',
                'user_id',
              //  'have_seen',
                'category',
                'image',
                'title',
                'description',
                'created_at',
                'unique_id',
                
                new Expression('ifnull((select user_id from post_seen where user_id='.$this->user->id.' and post_id=post.id),0) as have_seen')
            ])
            ->where(['id'=>$id])
            ->with('user','user.company')
            ->one();
        $array = [
            'id'=>$post->id,
            'user_id'=>$post->user_id,
            'have_seen'=>$post->have_seen?1:0,
            'afbeelding'=>$post->getFilename()??$post->user->company->getFilename(),
            'tekst'=>$post->description,
            'titel'=>$post->title,
            'datum'=>date("d-m-Y",$post->created_at),
            'contactperson'=>$post->user->contactperson,
            'company_name'=>$post->user->company->name,
            

        ];
        PostSeen::addHaveSeen($id,$this->user->id);
        return json_encode($array);
    }

    public function actionAddSponsor(){
        $rawdata = file_get_contents("php://input");
        $data = json_decode($rawdata);

        if($this->request->isPost){

            $eventsponsor = EventSponsor::find()
                ->where(['user_id'=>$this->user->id,'event_id'=>$data->eventid??null,'sponsor_type'=>$data->type??null])
                ->one()?? new EventSponsor();
            $eventsponsor->user_id = $this->user->id;
            $eventsponsor->event_id = $data->eventid??null;
            $eventsponsor->sponsor_type = $data->type??null;
            $eventsponsor->save();
                
            
        }
    }

    public function actionGetSponsorEvents($events_encoded){
        if(empty($this->user?->id))
        {
            return json_encode(['error'=>'not authorized']);
        }
        $event_ids = json_decode($events_encoded);
        $sponsorevents = EventSponsor::find()->where(['event_id'=>$event_ids[0]])->all();
        $event =[];
        foreach($sponsorevents as $sponsorevent){
            $event[]    =   [
                'user_id'=>$sponsorevent->user_id,
          //      'profile_image'=>$sponsorevent->user->image,
                'user_image'=>$sponsorevent->user->getFilename(),
                'sponsor_type'=>$sponsorevent->sponsor_type
            ];
        }
        return json_encode($event);
    }
    public function actionAddPost(){

        if($this->request->isPost){
            $rawdata = file_get_contents("php://input");
            $titel = Yii::$app->request->post('titel');
            $omschrijving = Yii::$app->request->post('omschrijving');
            $duur = Yii::$app->request->post('duur');
           $image = \yii\web\UploadedFile::getInstanceByName('image');
   
            if($image){
                //echo "tempname is ".$image->tempName.' met '.$titel;
            }
            
                $post = new Post();
                $post->user_id = $this->user->id;
                $post->title = $titel;
                $post->imageApp = $image;
                $post->description = $omschrijving;
                
                if($duur == 'dag'){
                    $post->visible_till = date("Y-m-d",strtotime("+ 1 day"));
                }
                if($duur == 'week'){
                    $post->visible_till = date("Y-m-d",strtotime("+ 1 week"));
                }
                if($duur == 'maand'){
                    $post->visible_till = date("Y-m-d",strtotime("+ 1 month"));
                }
                

                
                $post->category = [Post::CATEGORY_POST];
                if($post->save()){
                    return json_encode(['success'=>'gelukt']);
                }
                return json_encode(['error'=>'niet gelukt']);
        }
    }

    public function actionPostSeen($post_id,$user_id){
        if((PostSeen::find()->where(['post_id'=>$post_id,'user_id'=>$user_id])->one()) !== null){
            return json_encode(['success'=>'already exist']);
        }
        $post_seenmodel = new  PostSeen();
        $post_seenmodel->user_id = $user_id;
        $post_seenmodel->post_id = $post_id;
        if($post_seenmodel->save()){
            return json_encode(['success'=>'gelukt']);
        }
         return json_encode(['error'=>'niet gelukt']);
    }

    public function actionLogin()
    {
        $rawdata = file_get_contents("php://input");
        $post_value = (array)json_decode($rawdata);
        $loginmodel = new LoginForm();

        $loginmodel->username   =   $post_value["username"] ?? null;
        $loginmodel->password   =   $post_value["password"] ?? null;

        if ($loginmodel->login()) {
            $usermodel = User::find()->where(['username' => $loginmodel->username])->one();
            $token = md5(time() . rand(4, 7));

            $usermodel->token = $token;
            $usermodel->save();
           

            return json_encode([
                'result' => 'success', 'token' => $token, 'id' => $usermodel->id, 'username' => $loginmodel->username
            ]);
        }
        return json_encode(['result' => 'error', 'message' => 'Username and/or password invalid']);

        //             $array[] = $post_value;
        // //print_R($array);



    }
    public function actionMessage($message, $destination_user_id)
    {
        if (empty($this->user->id)) {
            return json_encode(['error' => 'not authorized']);
        }
        $chat = new \frontend\models\Chat();
        $chat->source_user_id = $this->user->id;
        $chat->destination_user_id = $destination_user_id;
        $chat->message = $message;
        $chat->created_at = time();
        $chat->save();
        return json_encode(['gelukt']);
    }
    public function removeEndpoint($endpoint)
    {

        $notifications_file = Yii::getAlias('@runtime') . '/notifications.json';
        $array = json_decode(file_get_contents($notifications_file), true);
        foreach ($array as $key => $item) {
            if ($item['endpoint'] == $endpoint) {
                unset($array[$key]);
            }
        }
        file_put_contents($notifications_file, json_encode($array));
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
        $notifications_file = Yii::getAlias('@runtime') . '/notifications.json';
        $array = json_decode(file_get_contents($notifications_file), true);
        $webPush = new WebPush($auth);
        $notifications = [];
        foreach ($array as $item) {
            $payload = [
                'notification' => [
                    'title' => 'hello',
                    'options' => [
                        'body' => 'berichtje',
                        'icon' => "assets/main-page-logo-small-hat.png",
                        'badge' => "assets/main-page-logo-small-hat.png",
                        'vibrate' => [100, 50, 100],
                        "data" => [
                            "dateOfArrival" => time(),
                            'primaryKey' => 1,
                            'silent' => false,
                        ],
                    ],
                    'actions' => [["action" => "https://nu.nl", "title" => "go to site"]],
                ]
            ];
            $notification =         [
                'subscription' => Subscription::create($item), 'payload' => json_encode($payload)
            ];
            $notifications[]    =    $notification;
        }
        // send multiple notifications with payload
        foreach ($notifications as $notification) {
            $webPush->queueNotification(
                $notification['subscription'],
                $notification['payload'] // optional (defaults null)
            );
        }
        $i = 0;
        $already_done = [];
        foreach ($webPush->flush() as $report) {
            echo '<pre>';
            $endpoint = $report->getRequest()->getUri()->__toString();
            echo  "number $i<br>";
            if (in_array($endpoint, $already_done)) {
                echo "verwijeren! met $endpoint";
                $this->removeEndpoint($endpoint);
                continue;
            }
            $already_done[]    =    $endpoint;
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
