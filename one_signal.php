<?php


function sendMessage(){
    $content = array(
        "en" => 'Testing Message'
    );

    $fields = array(
        'app_id' => "a437566a-4c8d-43c0-91a2-7fee06f5be15",
        'included_segments' => array('All'),
        'data' => array("foo" => "bar"),
        'contents' => $content
    );

    $fields = json_encode($fields);
    print("\nJSON sent:\n");
    print($fields);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
        'Authorization: Basic OTMxYmIyMjEtODMzMS00NmIyLTk3NWEtY2UzMTcxZjdiOWYx'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

$response = sendMessage();
$return["allresponses"] = $response;
$return = json_encode( $return);
print("\n\nJSON received:\n");
print($return);
print("\n");

?>


<!DOCTYPE html>
<html lang="en">
    <head>
    <title>Bootstrap Example</title>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

</head>
<body>

<div class="container">
    <h1>My First Push Notification Test</h1>

    <div>

        <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async></script>
        <script>
            var useragentid = null;
            var OneSignal = window.OneSignal || [];
            OneSignal.push(["init", {
                appId: "a437566a-4c8d-43c0-91a2-7fee06f5be15",
                autoRegister: false,
                notifyButton: {
                    enable: false
                },
                persistNotification: false
            }]);
            //Firstly this will check user id
            OneSignal.push(function() {
                OneSignal.getUserId().then(function(userId) {
                    if(userId == null){
                        document.getElementById('unsubscribe').style.display = 'none';
                    }
                    else{
                        useragentid = userId;
                        document.getElementById('unsubscribe').style.display = '';
                        OneSignal.push(["getNotificationPermission", function(permission){
                        }]);
                        OneSignal.isPushNotificationsEnabled(function(isEnabled) {
                            if (isEnabled){
                                document.getElementById('unsubscribe').style.display = '';
                                document.getElementById('subscribe').style.display = 'none';
                            }
                            else{
                                document.getElementById('unsubscribe').style.display = 'none';
                                document.getElementById('subscribe').style.display = '';
                            }
                        });
                    }
                });
            });
            //Secondly this will check when subscription changed
            OneSignal.push(function() {
                OneSignal.on('subscriptionChange', function (isSubscribed) {
                    if(isSubscribed==true){
                        OneSignal.getUserId().then(function(userId) {
                            useragentid = userId;
                        }).then(function(){
                            // this is custom function
                            // here you can send post request to php file as well.
                            OneSignalUserSubscription(useragentid);
                        });
                        document.getElementById('unsubscribe').style.display = '';
                        document.getElementById('subscribe').style.display = 'none';
                    }
                    else if(isSubscribed==false){
                        OneSignal.getUserId().then(function(userId) {
                            useragentid = userId;
                        });
                        document.getElementById('unsubscribe').style.display = 'none';
                        document.getElementById('subscribe').style.display = '';
                    }
                    else{
                        console.log('Unable to process the request');
                    }
                });
            });
            function subscribeOneSignal(){
                if(useragentid !=null){
                    OneSignal.setSubscription(true);
                }
                else{
                    OneSignal.registerForPushNotifications({
                        modalPrompt: true
                    });
                }
            }
            function unSubscribeOneSignal(){
                OneSignal.setSubscription(false);
            }
        </script>
        <div id="home-top" class="clearfix">
            <p>OneSingle Testing</p>
            <br>
            <button id="subscribe" class="button" onclick="subscribeOneSignal()">Subscribe </button>
            <button id="unsubscribe" class="button" onclick="unSubscribeOneSignal()">Unsubscribe </button>
        </div>
        <style>
            .button {
                background-color: #008CBA;border: none;color: white;padding: 15px 32px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;cursor: pointer;
            }
        </style>



    </div>
</div>


</body>
</html>
