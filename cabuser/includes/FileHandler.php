<?php





class FileHandler

{



    private $con;



    public function __construct()

    {

        require_once dirname(__FILE__) . '/DbConnect.php';



        $db = new DbConnect();

        $this->con = $db->connect();

    }

    // public function saveFile($file, $extension)

    // {

    //     $name = round(microtime(true) * 1000) . '.' . $extension;

    //     $filedest = dirname(__FILE__) . UPLOAD_PATH. $name;

    //     move_uploaded_file($file, $filedest);



    //     $url  =  UPLOAD_PATH . $name;



    //     $stmt = $this->con->prepare("INSERT INTO es_assignment (as_description) 

    //     VALUES ( ?);");

    //     $stmt->bind_param("s", $url);

    //     $stmt->execute();

    //     if ($stmt->execute())

    //         return true;

    //     return false;

    // }

     public function saveFile($file, $extension,$p_id,$d_id)

    {

        $name = round(microtime(true) * 1000) . '.' . $extension;

        $filedest = dirname(__FILE__) . UPLOAD_PATH. $name;

        move_uploaded_file($file, $filedest);

       
        $urls  = "https://".$_SERVER['HTTP_HOST']."/cabuser/includes". UPLOAD_PATH . $name;



        $stmt = $this->con->prepare("INSERT INTO provider_documents (provider_id, document_id, url)  VALUES (?,?,?)");

        $stmt->bind_param("sss", $p_id,$d_id,$urls);

        if ($stmt->execute())

            return true;

        return false;

    }



   



}