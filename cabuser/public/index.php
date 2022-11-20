<?php


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

require '../includes/DbOperations.php';

require  '../includes/FileHandler.php';



$app = new \Slim\App([
    'settings'=>[
        'displayErrorDetails'=>true
    ]
]);

$app->post('/uploaddoc', function(Request $request, Response $response, $args){
    $response_data = array();

     $upload = new FileHandler();
     $file = $_FILES['image']['tmp_name'];
     $pid = $_POST['pid'];
     $did = $_POST['did'];
   


     if ($upload->saveFile($file, getFileExtension($_FILES['image']['name']),$pid,$did)) {
         $response_data['error'] = false;
         $response_data['message'] = 'File Uploaded Successfullly';
     }

 $response->write(json_encode($response_data));

 return $response
 ->withHeader('Content-type', 'application/json')
 ->withStatus(200);  

});
function getFileExtension($file)
{
 $path_parts = pathinfo($file);
 return $path_parts['extension'];
}


$app->get('/getalldoc/{ids}', function(Request $request, Response $response, $args){
    $request_data = $request->getParsedBody();
    $ids = $args['ids'];
    $db = new DbOperations; 

    $users = $db->getAllDoucments($ids);

    $response_data = array();

    $response_data['error'] = false; 
    $response_data['documents'] = $users; 

    $response->write(json_encode($response_data));

    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
   
});

$app->get('/getcity', function(Request $request, Response $response, $args){
    $request_data = $request->getParsedBody();
    $db = new DbOperations; 

    $users = $db->getcity();
    $verification = $db->getverification();
    $response_data = array();

    $response_data['city'] = $users; 
    $response_data['verification'] = $verification; 

    $response->write(json_encode($response_data));

    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
   
});


$app->get('/getchat/{booking_id}', function(Request $request, Response $response, $args){
    $request_data = $request->getParsedBody();
    $booking_id = $args['booking_id'];
    $db = new DbOperations; 

    $users = $db->getchat($booking_id,$type,$uid);

    $response_data = array();

    $response_data['error'] = false; 
    $response_data['chat'] = $users; 

    $response->write(json_encode($response_data));

    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
   
});
$app->post('/addchat', function(Request $request, Response $response, $args){

    $request_data = $request->getParsedBody();
     $booking_id = $request_data['booking_id'];
     $uid = $request_data['uid'];
     $pid = $request_data['pid'];
     $message = $request_data['message'];
     $type = $request_data['type'];
    $db = new DbOperations; 

    $users = $db->addchat($booking_id,$uid,$pid,$message,$type);

    $response_data = array();

    $response_data['error'] = $users; 

    $response->write(json_encode($response_data));

    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
   
});



$app->get('/getbankaccount/{id}', function(Request $request, Response $response, $args){
    $request_data = $request->getParsedBody();
    $id = $args['id'];
    $db = new DbOperations; 

    $users = $db->getbankaccount($id);

    $response_data = array();

    $response_data['error'] = false; 
    $response_data['bankaccount'] = $users; 

    $response->write(json_encode($response_data));

    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
   
});
$app->post('/addwithdraw', function(Request $request, Response $response, $args){
    $request_data = $request->getParsedBody();
     $bid = $request_data['bid'];
     $id = $request_data['id'];
     $amount = $request_data['amount'];
    $db = new DbOperations; 

    $users = $db->addwithdraw($bid,$id,$amount);

    $response_data = array();

    $response_data['error'] = $users; 

    $response->write(json_encode($response_data));

    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
   
});

$app->post('/addbankaccount', function(Request $request, Response $response, $args){
    $request_data = $request->getParsedBody();
     $name = $request_data['name'];
     $type = $request_data['type'];
     $bankname = $request_data['bankname'];
     $accountnumber = $request_data['accountnumber'];
     $ifsc = $request_data['ifsc'];
     $micr = $request_data['micr'];
     $id = $request_data['id'];
     $country = $request_data['country'];
     $currency = $request_data['currency'];
    $db = new DbOperations; 

    $users = $db->addbankaccount($request_data,$name,$type,$bankname,$accountnumber,$ifsc,$micr,$id,$country,$currency);

    $response_data = array();

    $response_data['error'] = $users; 

    $response->write(json_encode($response_data));

    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
   
});


$app->post('/driverloginbyphone', function(Request $request, Response $response){



    if(!haveEmptyParameters(array('phone'), $request, $response)){

        $request_data = $request->getParsedBody(); 



        $phone = $request_data['phone'];

        

        $db = new DbOperations; 



        $result = $db->DriverbyPhone($phone);



        if($result == USER_AUTHENTICATED){

            

            $user = $db->getDriverByphone($phone);

            $response_data = array();



            $response_data['error']=false; 

            $response_data['message'] = 'Login Successful';

            $response_data['user']=$user; 



            $response->write(json_encode($response_data));



            return $response

                ->withHeader('Content-type', 'application/json')

                ->withStatus(200);    



        }else if($result == USER_NOT_FOUND){

            $response_data = array();



            $response_data['error']=true; 

            $response_data['message'] = 'User not exist';



            $response->write(json_encode($response_data));



            return $response

                ->withHeader('Content-type', 'application/json')

                ->withStatus(200);    



        }else if($result == USER_PASSWORD_DO_NOT_MATCH){

            $response_data = array();



            $response_data['error']=true; 

            $response_data['message'] = 'Invalid credential';



            $response->write(json_encode($response_data));



            return $response

                ->withHeader('Content-type', 'application/json')

                ->withStatus(200);  

        }

    }



    return $response

        ->withHeader('Content-type', 'application/json')

        ->withStatus(422);    

});








$app->post('/staffloginbyphone', function(Request $request, Response $response){

    if(!haveEmptyParameters(array('phone'), $request, $response)){
        $request_data = $request->getParsedBody(); 

        $phone = $request_data['phone'];
        
        $db = new DbOperations; 

        $result = $db->LoginbyPhone($phone);

        if($result == USER_AUTHENTICATED){
            
            $user = $db->getUserByPhone($phone);
            $response_data = array();

            $response_data['error']=false; 
            $response_data['message'] = 'Login Successful';
            $response_data['user']=$user; 

            $response->write(json_encode($response_data));

            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);    

        }else if($result == USER_NOT_FOUND){
            $response_data = array();

            $response_data['error']=true; 
            $response_data['message'] = 'User not exist';

            $response->write(json_encode($response_data));

            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);    

        }else if($result == USER_PASSWORD_DO_NOT_MATCH){
            $response_data = array();

            $response_data['error']=true; 
            $response_data['message'] = 'Invalid credential';

            $response->write(json_encode($response_data));

            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);  
        }
    }

    return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(422);    
});
//kmndfd
$app->post('/updateservice', function(Request $request, Response $response, $args){
    $request_data = $request->getParsedBody();
    $service_id = $request_data['service_id'];
    $provider_id = $request_data['provider_id'];
   
    $db = new DbOperations; 

    $users = $db->updateservice($service_id,$provider_id);

    $response_data = array();

    $response_data['error'] = false; 

    $response->write(json_encode($response_data));

    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
   
});
$app->post('/deleteservice', function(Request $request, Response $response, $args){
    $request_data = $request->getParsedBody();
    $service_id = $request_data['service_id'];
    $provider_id = $request_data['provider_id'];
   
    $db = new DbOperations; 

    $users = $db->deleteservice($service_id,$provider_id);

    $response_data = array();

    $response_data['error'] = false; 

    $response->write(json_encode($response_data));

    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
   
});

$app->get('/checkservicelist/{service_id}/{provider_id}', function(Request $request, Response $response, $args){
    $request_data = $request->getParsedBody();
  
    $service_id =  $args['service_id'];;
    $provider_id =  $args['provider_id'];;
   
    $db = new DbOperations; 

    $users = $db->checkservicelist($service_id,$provider_id);

    $response_data = array();

    $response_data['error'] = false; 
    $response_data['listcheck'] = $users;

    $response->write(json_encode($response_data));

    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
   
});

$app->get('/getservices/{type}', function(Request $request, Response $response, $args){
    $request_data = $request->getParsedBody();
  
    $type =  $args['type'];;
   
    $db = new DbOperations; 

    $users = $db->getservices($type);

    $response_data = array();

    $response_data['error'] = false; 
    $response_data['services'] = $users;

    $response->write(json_encode($response_data));

    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
   
});

$app->get('/getallservices', function(Request $request, Response $response, $args){
    $request_data = $request->getParsedBody();
    $db = new DbOperations; 

    $users = $db->getAllservices();

    $response_data = array();

    $response_data['error'] = false; 
    $response_data['serviceslist'] = $users; 

    $response->write(json_encode($response_data));

    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  
   
});






function haveEmptyParameters($required_params, $request, $response){
    $error = false; 
    $error_params = '';
    $request_params = $request->getParsedBody(); 

    foreach($required_params as $param){
        if(!isset($request_params[$param]) || strlen($request_params[$param])<=0){
            $error = true; 
            $error_params .= $param . ', ';
        }
    }

    if($error){
        $error_detail = array();
        $error_detail['error'] = true; 
        $error_detail['message'] = 'Required parameters ' . substr($error_params, 0, -2) . ' are missing or empty';
        $response->write(json_encode($error_detail));
    }
    return $error; 
}

$app->run();

