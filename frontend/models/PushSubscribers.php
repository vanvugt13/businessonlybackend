<?php

namespace frontend\models;

use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "push_subscribers".
 *
 * @property int $id
 * @property resource|null $raw_data
 * @property string|null $endpoint
 * @property string|null $expirationDate
 * @property resource|null $keys
 * @property int|null $created_at
 * @property int|null $user_id
 * @property int|null $updated_at
 * @property string $uuid
 */
class PushSubscribers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

     const TYPE_CHAT = 10;
     const TYPE_EVENT = 20;
     const TYPE_POST = 30;
     const TYPE_NEWS = 40;
    public static function tableName()
    {
        return 'push_subscribers';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['raw_data', 'endpoint', 'expirationDate', 'keys','uuid'], 'string'],
            [['endpoint'],'unique','message'=>"Endpoint mag niet dubbel voorkomen"],
            [['created_at', 'updated_at','user_id'], 'integer'],
        ];
    }

    

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'raw_data' => 'Raw Data',
            'endpoint' => 'Endpoint',
            'expirationDate' => 'Expiration Date',
            'keys' => 'Keys',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    private function getAuth():array{
        return [
            'VAPID' => [
                'subject' => 'mailto:erik@familie-van-vugt.nl', // can be a mailto: or your website address
                'publicKey' => "BAiH9NsTi0Qh5FqWcUY7TiaWw1nPST63dvrvooj0LjXYBJxrJ8wEdqxNEiJUZnE7AzQ3qvopql08fRw5FvNFg_4", // (recommended) uncompressed public key P-256 encoded in Base64-URL
                'privateKey' => "BtQgFaO_aQhyphXQvEKzVIeZnmYs6nnfOKL1W0dcNtc", // (recommended) in fact the secret multiplier of the private key encoded in Base64-URL
            ],
        ];
    }

    public function sendNotification(array|int $users,string $title,string $message,int $type){
        $auth = $this->getAuth();
        $pushSubscribers = PushSubscribers::find();  
        $pushSubscribers->where(['user_id'=>$users]); 
        $pushSubscribers = $pushSubscribers->all();
       // $notifications_file = Yii::getAlias('@runtime') . '/notifications.json';
       // $array = json_decode(file_get_contents($notifications_file), true);
        $webPush = new WebPush($auth);
        $notifications = [];
        $tag = 'post';
        if($type == self::TYPE_POST){
            $page = '/news';
        }
        if($type == self::TYPE_EVENT){
            $tag = 'event';
            $page = '/calendar';
        }
        if($type == self::TYPE_NEWS){
            $tag = 'news';
            $page = '/news';
        }
        if($type == self::TYPE_CHAT){
            $page = '/chat';
            $tag = 'chat';
        }
        $url = Yii::$app->request->hostInfo.$page;
        foreach ($pushSubscribers as $item) {
            $payload = [
                'notification' => [
                    'title' => $title,
                    'tag'=>$tag,
                    'icon'=>"assets/icons/icon-144x144.png",
                    "data"=>[
                        "onActionClick"=>[
                          "default"=>["operation"=>"openWindow", "url"=> $url]
                        ]
                    ]
                ],
            ];
            $notification =         [
                'subscription' => Subscription::create(json_decode($item->raw_data,true)), 'payload' => json_encode($payload)
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
           // echo '<pre>';
            $endpoint = $report->getRequest()->getUri()->__toString();
            if (in_array($endpoint, $already_done)) {
                $this->removeEndpoint($endpoint);
                continue;
            }
            $already_done[]    =    $endpoint;
            if ($report->isSuccess()) {
            } else {
                $this->removeEndpoint($endpoint);
            }
            $i++;
        }

        
    }

    protected function removeEndpoint($endpoint){
        return PushSubscribers::deleteAll(['endpoint'=>$endpoint]);
    }
}

