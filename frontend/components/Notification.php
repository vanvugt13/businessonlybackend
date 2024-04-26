<?php
namespace frontend\components;
use Yii;
Class Notification{

    public function sendNotification(array $notificationObjects){
        $notificationobject = $notificationObjects[0]??null;
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
                        'title'=>$notificationobject->title,
                        'image'=>"https://socialsapp.familie-van-vugt.nl/assets/images/telefoon.png",
                        'icon'=>"assets/images/profiel.png",
                         'badge'=>"/assets/images/chat.png",
                        // 'fcm_ot54534ptions'=>[
                        //     'body'=>$notificationobject->text,
                        //     'icon'=>"assets/images/profiel.png",
                        //     'badge'=>"/assets/images/chat.png",
                        //     'image'=>"/assets/images/chat.png",
                        //     'vibrate'=>[ 5000,50,1000],
                        //     "data"=>[
                        //         "dateOfArrival"=>time(),
                        //         'primaryKey'=>1,
                        //         'silent'=>false,
                        //         ],
                        //     ],
                        //'actions'=>[["action"=>$notificationobject->url,"title"=>"Bekijk de site (".$notificationobject->url.")"]],
                        'actions'=>[['action'=>'reply','type'=>'text','title'=>'Reply']]
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
                //    echo '<pre>';
                    $endpoint = $report->getRequest()->getUri()->__toString();
                   // echo  "number $i<br>";
                    if(in_array($endpoint,$already_done))
                    {
                  //      echo "verwijeren! met $endpoint";
        			    $this->removeEndpoint($endpoint);
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
}

?>