<?php
session_start();
require('connectionPDO.php');

if (isset($_POST['btnConnexion'])) {
    echo '1<br>';
    $email              =@htmlentities($_POST["email"]);
    $motdepasse         =@htmlentities($_POST["motdepasse"]);
    
if($email){
    /*****************************/
        $motdepasse2=sha1($motdepasse);
       
        $sql= $bdd->prepare("select * from `aaa-utilisateur` where email=:e and motdepasse=:m ");
	    $sql->execute(['e'=>$email, 'm'=>$motdepasse2]);
echo '2<br>';
       
        
        if($tab=$sql->fetch(PDO::FETCH_ASSOC)){
            echo '3<br>';
              $_SESSION['iduserBack']   = $tab["idutilisateur"];
              $_SESSION['prenom']   = $tab["prenom"];
              $_SESSION['nomU']     = $tab["nom"];
              $_SESSION['adresseU'] = $tab["adresse"];
              $_SESSION['email']    = $tab["email"];
              $_SESSION['telPortable']= $tab["telPortable"];
              $_SESSION['telFixe']    = $tab["telFixe"];
              $_SESSION["profil"]   = $tab["profil"];
              $_SESSION['back']   = $tab["back"];
              $_SESSION['creationB'] =$tab["creationB"];
              $_SESSION['activer'] =$tab["activer"];
              $_SESSION['matricule'] =$tab["matricule"];
              $_SESSION['password'] =$motdepasse2;
              
              //echo'<br/>';
              //echo $_SESSION["profil"];
              
    
             if(($_SESSION['back']==1)&&($_SESSION['activer']==1)){
                echo '4<br>';
                        if(($_SESSION['profil']=="SuperAdmin")||($_SESSION['profil']=="Admin")){
                            header("Location:admin-map.php");
                        }else if($_SESSION['profil']=="Accompagnateur"){
                            header("Location:user-map.php");
                        }else if($_SESSION['profil']=="Assistant"){
                            header("Location:accueil-editeur.php");
                        }/*else if($_SESSION['profil']=="Editeur catalogue"){
                            
                            header("accueil-editeur.php");
                        }*/
            }else{
                    $message="Désole vous n'étes pas autorisé à accéder à l'espace";
            }
        }
        
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>JCAISSE-BACK END</title>
 
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <!--Stylesheet-->
    <style media="screen">
      *,
        *:before,
        *:after{
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        body{
            /* background-color: #080710; */
            background-color: #c0c0c0;
        }
        .background{
            width: 430px;
            height: 520px;
            position: absolute;
            transform: translate(-50%,-50%);
            left: 50%;
            top: 50%;
        }
        .background .shape{
            height: 200px;
            width: 200px;
            position: absolute;
            border-radius: 50%;
        }
        .shape:first-child{
            background: linear-gradient(
                #1845ad,
                #23a2f6
            );
            left: -80px;
            top: -80px;
        }
        .shape:last-child{
            /* background: linear-gradient(
                to right,
                #ff512f,
                #f09819
            ); */
            background: linear-gradient(
                to right,
                rgb(62, 202, 6),
                rgb(133, 247, 88)
            );
            right: -30px;
            bottom: -80px;
        }
        form{
            height: 520px;
            width: 400px;
            /* background-color: rgba(255,255,255,0.13); */
            background-color: rgba(255,255,255,0.78);
            position: absolute;
            transform: translate(-50%,-50%);
            top: 50%;
            left: 50%;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.1); /* Me */
            box-shadow: 0 0 40px rgba(8,7,16,0.6);
            padding: 50px 35px;
        }
        form *{
            font-family: 'Poppins',sans-serif;
            color: #ffffff;
            letter-spacing: 0.5px;
            outline: none;
            border: none;
        }
        form h3{
            font-size: 32px;
            font-weight: 500;
            line-height: 42px;
            text-align: center;
            color: blue;
        }

        label{
            display: block;
            margin-top: 30px;
            font-size: 16px;
            font-weight: 500;
            color: black;
        }
        input{
            display: block;
            height: 50px;
            width: 100%;
            /* background-color: rgba(255, 255, 255, 0.5); */
            color:black;
            border-radius: 3px;
            padding: 0 10px;
            margin-top: 8px;
            font-size: 14px;
            font-weight: 300;
            border: 2px solid rgb(107, 230, 107);
            text:red;
        }
        i
        ::placeholder{
            color:rgb(5, 117, 48);
        }
        .button{
            margin-top: 50px;
            width: 100%;
            height: 70px;
            /* background-color: #ffffff;
            color: #080710; */
            background-color: black;
            color: rgb(62, 202, 6);
            padding: 15px 0;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
        }
        .button:hover{
            background-color: rgb(62, 202, 6);
            color: black;
            font-size: 20;
            font-weight: -00;
            border-radius: 5px;
            cursor: pointer;
        }
        .social{
        margin-top: 30px;
        display: flex;
        
        }
        .social div{
        background: red;
        width: 150px;
        border-radius: 3px;
        padding: 5px 10px 10px 5px;
        background-color: rgba(255,255,255,0.27);
        color: #eaf0fb;
        text-align: center;
        }
        .social div:hover{
        background-color: rgba(10, 10, 10, 0.99);
        color: red;
        }
        .social .fb:hover{
            background-color: rgba(145, 171, 192, 0.99);
            color: blue;
        }
        .social .fb{
        margin-left: 25px;
        }
        .social .yo{
        margin-left: 20px;
        background-color: rgba(231, 55, 55, 0.99);
        
        }
        .social .fb{
        margin-left: 20px;
        background-color: rgba(24, 119, 242, 0.99);
        
        }
        .social i{
        margin-right: 4px;
        }
        .yo>a{
            text-decoration: none;
        }
        footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            color: white;
            text-align: center;
            font-size: 5px;
            }

    </style>
</head>
<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <?php
        if (isset($message)) {
            echo $message;
        } 
    ?>
    <form method="post" >
        <h3>BACK-END JCAISSE</h3>

        <label for="username">Login</label>
        <input type="email" placeholder="Votre login" id="username" name="email">

        <label for="password">Mot de passe</label>
        <input type="password" placeholder="Votre mot de passe" id="password" name="motdepasse">
        
        <input class="button" type="submit" name="btnConnexion" value="Connexion ">

        <!-- <button >Connexion</button> -->
        <div class="social">
          <div class="yo"><a href="https://www.youtube.com/@JCAISSE-ZIG" target="_blank"><i class="fab fa-youtube"></i>  Youtube</a></div>
          <!-- <div class="yo"><i class="fab fa-youtube"></i>  Youtube</div> -->
          <div class="fb"><i class="fab fa-facebook"></i>  Facebook</div>
        </div>
    </form>
    <footer><span>Copyright (c) 2021 by Foolish Developer
    </span></footer>
</body>
</html>
