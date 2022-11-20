<?php 




use Setting;

    class DbOperations{

        private $con; 

        function __construct(){
            require_once dirname(__FILE__) . '/DbConnect.php';
            $db = new DbConnect; 
            $this->con = $db->connect(); 
            $this->con1 = $db->connect();

        }

        public function LoginbyPhone($phone){
            if($this->isStaffIdExist($phone)){
                    return USER_AUTHENTICATED;
            }else{
                return USER_NOT_FOUND; 
            }
        }
//wsdmwdkmw
        public function getUserByPhone($phone){
             $stmt = $this->con->prepare("SELECT email  FROM users WHERE mobile = ?");
            $stmt->bind_param("s", $phone);
            $stmt->execute(); 
            $stmt->bind_result($email);
            $stmt->fetch();
            $user = array(); 
            $user['email']=$email; 
            return $user; 
        }

        private function isStaffIdExist($phone){
            $stmt = $this->con->prepare("SELECT mobile  FROM users WHERE mobile = ?");
            $stmt->bind_param("s", $phone);
            $stmt->execute(); 
            $stmt->store_result(); 
            return $stmt->num_rows > 0;  
        }

        public function DriverbyPhone($phone){

            if($this->isDriverIdexist($phone)){

                    return USER_AUTHENTICATED;

            }else{

                return USER_NOT_FOUND; 

            }

        }

        public function getDriverByphone($phone){

            $stmt = $this->con->prepare("SELECT email  FROM providers WHERE mobile = ?");

           $stmt->bind_param("s", $phone);

           $stmt->execute(); 

           $stmt->bind_result($email);

           $stmt->fetch();

           $user = array(); 

           $user['email']=$email; 

           return $user; 

       }

       private function isDriverIdexist($phone){

        $stmt = $this->con->prepare("SELECT mobile  FROM providers WHERE mobile = ?");

        $stmt->bind_param("s", $phone);

        $stmt->execute(); 

        $stmt->store_result(); 

        return $stmt->num_rows > 0;  

    }




     public function getcity(){
        $stmt = $this->con->prepare("SELECT name FROM cities");
        $stmt->execute(); 
        $stmt->bind_result($name);
        $users = array(); 
        while($stmt->fetch()){ 
            $user = array(); 
            $user['name'] = $name; 
            array_push($users, $user);
        } 
        return $users; 
       }

       public function getverification(){
            $stmt1 = $this->con1->prepare("SELECT value FROM `settings` WHERE `key` LIKE 'verification'");
            $stmt1->execute(); 
            $stmt1->bind_result($value);
            $stmt1->fetch();
            if($value == 1){
                return true; 
            }else{
                return false; 
            }
        
        }
        public function updateservice($sid, $pid){
           $stmt = $this->con->prepare("INSERT INTO provider_services (id, provider_id, service_type_id, status, service_number, service_model, created_at, updated_at) VALUES (NULL, ?, ?, 'active', '', '', '', '');");
           $stmt->bind_param("ss", $pid, $sid);
           $stmt->execute(); 
           return $user; 
       }
       public function deleteservice($sid, $pid){
        $stmt = $this->con->prepare("DELETE FROM provider_services WHERE provider_id = ? AND service_type_id = ?");
        $stmt->bind_param("ss", $pid, $sid);
        $stmt->execute(); 
        return $user; 
    }
       public function checkservicelist($sid, $pid){
        $stmt = $this->con->prepare("SELECT provider_id FROM provider_services WHERE service_type_id = ? AND provider_id = ?");
        $stmt->bind_param("ss", $sid, $pid);
        $stmt->execute(); 
        $stmt->bind_result($p_id);
        $stmt->fetch();
        $user = array(); 
        $user['pid']=''.$p_id; 
        return $user; 
       }

       public function getAllDoucments($ids){
        $stmt = $this->con->prepare("SELECT id, name, type from documents where id not in
         (SELECT document_id from provider_documents where provider_id='$ids')");
        $stmt->execute(); 
        $stmt->bind_result($id, $name, $type);
        $users = array(); 
        while($stmt->fetch()){ 
            $user = array(); 
            $user['did'] = $id; 
            $user['dname']=$name; 
            $user['dtype']=$type; 
            array_push($users, $user);
        }             
        return $users; 
    } 
    public function getchat($booking_id){
        $stmt1 = $this->con1->prepare("SELECT message, type FROM chats WHERE request_id = ? ORDER BY id ASC ");
        $stmt1->bind_param("s", $booking_id );
        $stmt1->execute();
        $stmt1->bind_result( $message, $type);
        $users = array(); 
        while($stmt1->fetch()){ 
            $user = array(); 
            $user['type']=$type; 
            $user['message']=$message; 
            array_push($users, $user);
        } 
     
        return $users; 
    } 

    public function addwithdraw($bid,$id,$amount){
        $stmt1 = $this->con1->prepare("INSERT INTO `withdrawal_moneys` (`id`, `bank_account_id`, `provider_id`, `amount`, `status`, `created_at`, `updated_at`) VALUES (NULL, ?, ?, ?, 'WAITING', '2020-03-08 17:33:05', NULL);");
        $stmt1->bind_param("sss", $bid,$id,$amount );
        if($stmt1->execute()){
            return false;
        }else{
            return true;
        }
    }

    public function getbankaccount($id){
        $stmt1 = $this->con1->prepare("SELECT id, account_name , type , bank_name , account_number, IFSC_code, MICR_code FROM bank_accounts WHERE 	provider_id = ?  ");
        $stmt1->bind_param("s", $id );
        $stmt1->execute();
        $stmt1->bind_result( $bid, $account_name, $type, $bank_name, $account_number, $IFSC_code, $MICR_code);
        $stmt1->fetch();
        $user = array(); 
        $user['bid']=$bid; 
        $user['account_name']=$account_name; 
        $user['type']=$type; 
        $user['bank_name']=$bank_name; 
        $user['account_number']=$account_number;
        $user['IFSC_code']=$IFSC_code; 
        $user['MICR_code']=$MICR_code;

        $stmt = $this->con->prepare("SELECT SUM(withdrawal_moneys.amount) as total ,(SELECT SUM(withdrawal_moneys.amount) as paid FROM `withdrawal_moneys` WHERE provider_id = ? AND STATUS = 'APPROVED') as paid FROM `withdrawal_moneys` WHERE provider_id = ? AND STATUS = 'WAITING'");
        $stmt->bind_param("ss", $id,$id);
        $stmt->execute();
        $stmt->bind_result($pending,$paid);
        $stmt->fetch();

        $user['pending']=$pending; 
        $user['paid']=$paid;
        $user['total']=$pending+$paid;
        return $user; 
    }

     public function addbankaccount($request_data,$name,$type,$bankname,$accountnumber,$ifsc,$micr,$id,$country,$currency){
        $stmt1 = $this->con1->prepare("INSERT INTO `bank_accounts` (`id`, `paypal_id`, `upi_id`, `account_name`, `type`, `bank_name`, `account_number`, `IFSC_code`, `MICR_code`, `routing_number`, `provider_id`, `status`, `country`, `currency`, `created_at`, `updated_at`) 
        VALUES (NULL, '', '', ?, ?, ?, ?, ?,?, '0', ?, 'APPROVED', ?, ?, '2020-03-06 00:00:00', '2020-03-06 12:18:25');");
        $stmt1->bind_param("sssssssss", $name,  $type, $bankname, $accountnumber,$ifsc,$micr,$id,$country,$currency);
        if($stmt1->execute()){
            return false; 
        }else{
            $stmt = $this->con->prepare("UPDATE `bank_accounts` SET `account_name` = ?, `type` = ?, `bank_name` = ?, `account_number` = ?, `IFSC_code` = ?, `MICR_code` = ?, `country` = ?, `currency` = ?
            WHERE `bank_accounts`.`provider_id` = ? ;");
            $stmt->bind_param("sssssssss", $name,  $type, $bankname, $accountnumber,$ifsc,$micr,$country,$currency,$id);
            if($stmt->execute()){
                return false;
            }else{
                return true; 
            }
        }
    
    } 
  
  public function addchat($booking_id,$uid,$pid,$message,$type){
    $stmt1 = $this->con1->prepare("INSERT INTO `chats` (`id`, `request_id`, `user_id`, `provider_id`, `message`, `type`, `delivered`, `created_at`, `updated_at`)
     VALUES (NULL, ?, ?, ?, ?, ?, '1', NULL, NULL)");
    $stmt1->bind_param("sssss", $booking_id ,$uid,$pid,$message,$type);
        if($stmt1->execute()){
            $stmt = $this->con->prepare("SELECT u.device_token as udt , p.token as pdt FROM users as u , provider_devices as p WHERE u.id = ? && p.provider_id = ?");
            $stmt->bind_param("ss", $uid ,$pid);
            $stmt->execute();
            $stmt->bind_result( $utokan, $ptoken);
            $stmt->fetch();
            if($type === up){
                $this->sendpushtoprovider($ptoken, $message);
            }else{
                $this->sendpushtouser($utokan, $message);
            }
            
            return false;
        }else{
            return true;
        }
    
 
     return $users; 
    } 

    public function sendpushtoprovider($tokan, $msg){

       
        $stmt1 = $this->con1->prepare("SELECT `value`  FROM `settings` WHERE `key` LIKE 'android_user_driver_key'");
        $stmt1->execute();
        $stmt1->bind_result($fcmkey);
        $stmt1->fetch();
        //$fcmUrl = new Curl\Curl();
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

  



        $notification = [
    
            'title' => 'Chat: Message From Rider',
    
              'body' =>  $msg,
    
            'sound' => true,
    
        ];
    
    
    
        $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];
    
    
    
        $fcmNotification = [
    
            //'registration_ids' => $tokenList, //multple token array
    
            'to'        =>$tokan, //single token
    
            'notification' => $notification,
    
            'data' => $notification
    
        ];
    
    
    
        $headers = [
    
            'Authorization: key='.$fcmkey,
    
            'Content-Type: application/json'
    
        ];
    
        
    
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
    
        curl_setopt($ch, CURLOPT_POST, true);
    
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
    
        $result = curl_exec($ch);
    
        curl_close($ch);
    }

    public function sendpushtouser($tokan, $msg){

        $stmt1 = $this->con1->prepare("SELECT `value`  FROM `settings` WHERE `key` LIKE 'android_user_fcm_key'");
        $stmt1->execute();
        $stmt1->bind_result($fcmkey);
        $stmt1->fetch();
       

        //$fcmUrl = new Curl\Curl();
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

  



        $notification = [
    
            'title' => 'Chat: Message From Driver',
    
              'body' =>  $msg,
    
            'sound' => true,
    
        ];
    
    
    
        $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];
    
    
    
        $fcmNotification = [
    
            //'registration_ids' => $tokenList, //multple token array
    
            'to'        =>$tokan, //single token
    
            'notification' => $notification,
    
            'data' => $notification
    
        ];
    
    
    
        $headers = [
    
            'Authorization: key='.$fcmkey,
    
            'Content-Type: application/json'
    
        ];
    
        
    
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
    
        curl_setopt($ch, CURLOPT_POST, true);
    
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
    
        $result = curl_exec($ch);
    
        curl_close($ch);
    }

       
       public function getservices($typ_e){
        $stmt = $this->con->prepare("SELECT id, name, image, type, capacity ,fixed ,price ,minute ,distance ,calculator FROM service_types WHERE type = ? ");
        $stmt->bind_param("s", $typ_e );
        $stmt->execute(); 
        $stmt->bind_result($id, $name, $image, $type,$capacity, $fixed, $price,$minute, $distance,$calculator);
        $users = array(); 
        while($stmt->fetch()){ 
            $user = array(); 
            $user['id'] = $id; 
            $user['name']=$name; 
            $user['image']=$image; 
            $user['type']=$type; 
            $user['capacity']=$capacity; 
            $user['fixed']=$fixed; 
            $user['price']=$price; 
            $user['minute']=$minute;
            $user['distance']=$distance;
            $user['calculator']=$calculator;
            array_push($users, $user);
        }             
        return $users; 
    } 

        public function getAllservices(){
            $stmt = $this->con->prepare("SELECT id, name FROM service_types");
            $stmt->execute(); 
            $stmt->bind_result($id, $name);
            $users = array(); 
            while($stmt->fetch()){ 
                $user = array(); 
                $user['id'] = $id; 
                $user['name']=$name; 
                array_push($users, $user);
            }             
            return $users; 
        } 
    }