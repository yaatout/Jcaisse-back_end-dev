
<?php
// 
// require('../../connection.php');
// require('../../connectionVitrine.php');
//
// require('../../declarationVariables.php');
// var_dump('cnx');
    if ((!$cnx_ftp) || (!$cnx_ftp_auth)) {
        // echo "";
        }
        else
        {
            // ftp_put($cnx_ftp,$uploadPath , $fileNameNew, FTP_BINARY);
            // echo " ";
            if (ftp_put($cnx_ftp,$remotePath.$fileNameNew, $uploadPath.$fileNameNew, FTP_BINARY)) {
            if($_POST["image"]!=''){
            unlink($uploadPath.$_POST['image']);
            ftp_delete ($cnx_ftp,$remotePath.$_POST['image'] ) ;
            //echo "".$remotePath.$_POST['image'];
        }
        } else{
            //var_dump($remotePath.$fileNameNew);
            //var_dump($localPath.$fileNameNew);
            // echo "echec J";
        }
        ftp_quit($cnx_ftp);
    }
?>
