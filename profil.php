<?php
    include("./inc/sidebar.php");
?>
    
    <div class="dash">
       
        <main class="dash">
           <div class="col-10 mx-auto profil">
                <div class="titre_p">
                    <h4>Profil </h4> <hr>
                </div>
            
                <div class="row info d-flex">
                    <div class="col-md-3 left_p  col-sm-12 center ">
                        <div class="img">
                            <img src="./inc/img/profile-pic.png" alt="">
                        </div>
                        <p>
                        <?php if(admin()) echo $_SESSION['admin']['Nom_admin'];
                            if(manager()) echo $_SESSION['manager']['Nom_manager'];
                        ?>
                        </p>
                        <button class="modifier_p">Modifier</button>
                        <div class="soum">
                            <button class="anuller_p">Anuller</button>
                            <button class="enregistrer_p" onclick="if (valider_profil()) { document.getElementById('form_p').submit(); }">Envoyer</button>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-12 right_p">
                        
                       <form action="controller.php" method="post" class="col-12 h-100" id="form_p" onsubmit="return valider_profil()">
                            <input type="hidden" name="jeton" value="<?= $_SESSION['jeton'] ?>">
                            <input type="hidden" name="action" value="modifier_profil">
                           <?php
                                if(manager()){
                                    echo " <input type='hidden' name='Id_manager' value='".$_SESSION['manager']['Id_manager'] ."'>";
                                    $id_manager=$_SESSION['manager']['Id_manager'];
                                    $stmt=$pdo->prepare("SELECT * FROM manager where Id_manager=?");
                                    $stmt->execute([$id_manager]);
                                    $manager=$stmt->fetch();
                                    $nom_p = $manager['Nom_manager'];
                                    $date_nc_p =$manager['Date_manager'];
                                    $email_p = $manager['Email_manager'];
                                    $tel_p = $manager['Tele_manager'];
                                    $mdps_p = $manager['Mdps_manager'];
                                }
                                else{
                                    $id_admin=$_SESSION['admin']['Id_admin'];
                                    $stmt=$pdo->prepare("SELECT * FROM admin where Id_admin=?");
                                    $stmt->execute([$id_admin]);
                                    $admin=$stmt->fetch();
                                    $nom_p = $admin['Nom_admin'];
                                    $date_nc_p =$admin['Date_admin'];
                                    $email_p = $admin['Email_admin'];
                                    $tel_p = $admin['Tele_admin'];
                                    $mdps_p = $admin['Mdps_admin'];
                                }
                           ?>

                            <div class="row rew">
                                    <div class="div_input col-md-6 col-sm-12">
                                        <label for="nom_p" class="col-3">Nom :</label>
                                        <input type="text" id="nom_p" class="col-12" name="nom_p" value="<?php echo $nom_p; ?>" readonly>
                                    </div>
                                    <div class="div_input col-md-6 col-sm-12  ">
                                        <label for="date_nc_p" class="col-3">Naissance :</label>
                                        <input type="date" id="date_nc_p" class="col-12" name="date_nc_p" value="<?php echo $date_nc_p; ?>" readonly>
                                    </div>
                            </div>
                            <div class="row rew">
                                <div class="div_input col-md-6 col-sm-12 ">
                                        <label for="email_p" class="col-3">Email :</label>
                                        <input type="text" id="email_p" class="col-12" name="email_p" value="<?php echo $email_p; ?>" readonly>
                                    </div>
                                    <div class="div_input col-md-6 col-sm-12 ">
                                        <label for="tel_p" class="col-3">Telephone :</label>
                                        <input type="text" id="tel_p" class="col-12" name="tel_p" value="<?php echo $tel_p; ?>" readonly>
                                    </div>
                            </div>
                            <div class="row rew f">
                                    <div class="div_input col-md-6 col-sm-12 ">
                                        <label for="mdps_p" class="col-3">Password :</label>
                                        <input type="password" id="mdps_p" class="col-12" name="mdps_p" value="<?php echo $mdps_p; ?>" readonly>
                                    </div>
                                    <div class="div_input col-md-6 col-sm-12 confirmer">
                                        <label for="mdps_p_c" class="col-3">Confirmer :</label>
                                        <input type="password" id="mdps_p_c" class="col-12" value="" readonly>
                                        <label for="mdps_p_c" id="label_err_confirmer" class="label_err"></label>
                                    </div>
                            </div>
                       </form>
                    </div>
                </div>
           </div>
           <div class="historique col-md-10 mx-auto">
    <div class="titre_p">
        <h4>Historique </h4> <hr>
    </div>
    <div class="historique col-md-10 mx-auto accordion" id="accordionExample">
    <?php
    if (admin()) {
        $stmt = $pdo->prepare("SELECT a.Nom_admin, h.Action_his, h.Details_his, h.Date_his 
                                FROM historique h
                                JOIN admin a ON h.Id_user = a.Id_admin
                                where Type_user='admin'
                                ORDER BY h.Date_his DESC
                               ");
        $stmt->execute();
        $i = 1;
        $previous_date = "";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            setlocale(LC_TIME, 'fr_FR.utf8'); // Set the locale to French
            $date = strftime("%Y-%m-%d", strtotime($row["Date_his"]));
            $today = strftime("%Y-%m-%d");
            if ($date == $today) {
                $day_title = "Aujourd'hui";
            } else if ($date == strftime('%Y-%m-%d',strtotime("-1 days"))) {
                $day_title = "Hier";
            } else {
                $day_title = ucfirst(strftime("%A", strtotime($row["Date_his"]))) . ' - ' . strftime("%d/%m/%Y", strtotime($row["Date_his"]));
            }
            if ($date != $previous_date) {
                if ($previous_date != "") {
                    echo '</div>'; // Close the previous accordion-item
                }
                echo '<h3>' . $day_title . '</h3>';
                echo '<div class="card accordion-item">';
            }
            echo '<div class="card-header" id="heading' . $i . '">';
            echo '<h2 class="mb-0">';
            echo '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' . $i . '" aria-expanded="false" aria-controls="collapse' . $i . '">';
            echo $row["Action_his"] . ' - ' . strftime("%H:%M:%S", strtotime($row["Date_his"]));
            echo '</button>';
            echo '</h2>';
            echo '</div>';
            echo '<div id="collapse' . $i . '" class="accordion-collapse collapse" aria-labelledby="heading' . $i . '" data-bs-parent="#accordionExample">';
            echo '<div class="card-body">';
            echo '<p><strong>Utilisateur : </strong>' . $row["Nom_admin"] . '</p>';
            echo '<p><strong>Détails : </strong>' . $row["Details_his"] . '</p>';
            echo '</div>';
            echo '</div>';
            $previous_date = $date;
            $i++;
        }
        if ($previous_date != "") {
            echo '</div>'; // Close the last accordion-item
        }
    } else {
        $user_id = $_SESSION['manager']['Id_manager'];
        $stmt = $pdo->prepare("SELECT m.Nom_manager, h.Action_his, h.Details_his, h.Date_his
                                FROM historique h
                                JOIN manager m ON h.Id_user =m.Id_manager
                                WHERE h.Id_user = ?
                                and  Type_user='manager'
                                ORDER BY h.Date_his DESC
                                ");
        $stmt->execute([$user_id]);
        $i = 1;
        $previous_date = "";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            setlocale(LC_TIME, 'fr_FR.utf8'); // Set the locale to French
            $date = strftime("%Y-%m-%d", strtotime($row["Date_his"]));
            $today = strftime("%Y-%m-%d");
            if ($date == $today) {
                $day_title = "Aujourd'hui";
            } else if ($date == strftime('%Y-%m-%d',strtotime("-1 days"))) {
                $day_title = "Hier";
            } else {
                $day_title = ucfirst(strftime("%A", strtotime($row["Date_his"]))) . ' - ' . strftime("%d/%m/%Y", strtotime($row["Date_his"]));
            }
            if ($date != $previous_date) {
                if ($previous_date != "") {
                    echo '</div>'; // Close the previous accordion-item
                }
                echo '<h3>' . $day_title . '</h3>';
                echo '<div class="card accordion-item">';
            }
            echo '<div class="card-header" id="heading' . $i . '">';
            echo '<h2 class="mb-0">';
            echo '<button class="accordion-button collapsed" type-de="button" data-bs-toggle="collapse" data-bs-target="#collapse' . $i . '" aria-expanded="false" aria-controls="collapse' . $i . '">';
            echo $row["Action_his"] . ' - ' . strftime("%H:%M:%S", strtotime($row["Date_his"]));
            echo '</button>';
            echo '</h2>';
            echo '</div>';
            echo'<div id="collapse' . $i . '" class="accordion-collapse collapse" aria-labelledby="heading' . $i . '" data-bs-parent="#accordionExample">';
            echo '<div class="card-body">';
            echo '<p><strong>Utilisateur : </strong>' . $row["Nom_manager"] . '</p>';
            echo '<p><strong>Détails : </strong>' . $row["Details_his"] . '</p>';
            echo '</div>';
            echo '</div>';
            $previous_date = $date;
            $i++;
        }
        if ($previous_date != "") {
            echo '</div>'; // Close the last accordion-item
        }
    }
    ?>
</div>
</div>
         </main>
    </div>






<?php 
include("./inc/bas.inc.html");
?>