<?php 
include("./inc/init.inc.php");
include("./inc/head.inc.html");
?>
   <div class="container-fluid c">
        <div class="row d-flex">
            <div class="left col-md-8 col-sm-12  ">
                <div class="row head">
                    <P></P>
                </div>
                <div class="main row center">
                    <div class="contet col-md-7 col-sm-12 row">
                        <div class="bienvenue col-12">
                            <p>Bienvenue</p>
                        </div>
                        <form action='controller.php' class="col-12" method='post' name='login' onsubmit="return valide_connexion()" >
                            <div class="form-group 12">
                                <div class="inpt col-12">
                                    <input type='text' class="col-12" name='Email_con' id="email_con" value="<?php if(isset($_COOKIE['email'])) echo $_COOKIE['email'] ;?>" placeholder='JESA @gmail.com' autofocus>
                                </div>
                                <label for="email_con" id="label_err_email_con">
                                <?php
                                    if(isset($_SESSION['erreurEmail'])){
                                        echo $_SESSION['erreurEmail'];
                                        unset($_SESSION['erreurEmail']);
                                    }
                                ?>
                                </label>
                            </div>
                            <div class="form-group col-12">
                                <div class="inpt col-12">
                                    <input type="password" class="inpt_mdps col-12" name='Mdps_con' placeholder="Password" id="mdps_con">
                                    <i class="fa-solid fa-eye mdps"></i>
                               </div>
                               <label for="mdps_con" id="label_err_mdps_con">
                               <?php
                                    if(isset($_SESSION['erreurMdps'])){
                                         echo" $_SESSION[erreurMdps]";
                                         unset($_SESSION['erreurMdps']);
                                    }                                  
                                 ?>
                               </label>

                            </div>
                            <div class="form-group col-12">
                                <div class="submit col-12">
                                    <input type="submit" class="connecter col-5" name='Con' value="se connecter">
                               </div>
                               <span>
                               <?php
                                    if(isset($_SESSION['erreurlog'])){
                                        echo "$_SESSION[erreurlog]";
                                        unset($_SESSION['erreurlog']);
                                    }
                                 ?>
                               </span>

                            </div>
                           
                        </form>
                    </div>
                    
                </div>
            </div>
            <div class="right col-md-4 col-sm-12">
                <div class="row head">
                    <P>JESA</P>
                </div>
            </div>
        </div>



    </div>
  
   

<?php 
include("./inc/bas.inc.html");
?>