
<?php

// des fichiers d'initialisation
require_once './inc/init.inc.php';
// connexion
try {
 
    if(isset($_POST['Con'])) {
        $email = trim($_POST['Email_con']);
        $mdps = $_POST['Mdps_con'];
        setcookie('email',$email,time()+60);
        $rslt1 = $pdo->prepare("SELECT * FROM admin WHERE Email_admin = ?");
        $rslt1->execute([$email]);
        $row1=$rslt1->fetch();
        if(!empty($row1)) {
            $rslt1 = $pdo->prepare("SELECT * FROM admin WHERE Email_admin = ? and Mdps_admin = ?");
            $rslt1->execute([$email, $mdps]);
            $row2=$rslt1->fetch();
            if(!empty($row2)) {
                foreach($row2 as $indice => $element) {
                    if($indice != 'Mdps_admin') {
                        $_SESSION['admin'][$indice] = $element; 
                    }
                }
                
                $_COOKIE['email']=$_SESSION['admin']['Email_admin'];
                $_SESSION['jeton'] = bin2hex(random_bytes(32));

                $hostname = gethostname();
                if(!empty($_SESSION['admin'])) {
                    $id_user = $_SESSION['admin']['Id_admin'];
                    $action = "Connexion";
                    $type_user = "admin";
                }
                $details = "" . $email . " s'est connecté depuis l'appareil $hostname";
                $date = date('Y-m-d H:i:s');
                $rslt3 = $pdo->prepare("INSERT INTO historique (Id_user, Type_user, Action_his, Details_his, Date_his) VALUES (?, ?, ?, ?, ?)");
                $rslt3->execute([$id_user, $type_user, $action, $details, $date]);
                header("Location: accueil.php");
                exit;
            } else {
                $_SESSION['erreurMdps'] = "Mot de passe incorrect";
                header("Location: index.php");
                exit;
            }
        } else{
            $rslt2=$pdo->prepare("SELECT * FROM manager WHERE Email_manager=?");
            $rslt2->execute([$email]);
            $row1=$rslt2->fetch();
            if(!empty($row1)) {
                $rslt2=$pdo->prepare("SELECT * FROM manager WHERE Email_manager=? and Mdps_manager=?");
                $rslt2->execute([$email,$mdps]);
                $row2=$rslt2->fetch();
                if(!empty($row2)) {
                    foreach($row2 as $indice => $element){
                        if($indice != 'Mdps_manager'){
                            $_SESSION['manager'][$indice] = $element;
                        }
                    }
                    $_COOKIE['email']=$_SESSION['manager']['Email_manager'];
                    $_SESSION['jeton'] = bin2hex(random_bytes(32));
                    $hostname = gethostname();
                    if(!empty($_SESSION['manager'])) {
                        $id_user = $_SESSION['manager']['Id_manager'];
                        $action = "Connexion";
                        $type_user = "manager";
                    }
                    $details = "" . $email . " s'est connecté depuis l'appareil $hostname";
                    $date = date('Y-m-d H:i:s');
                    $rslt3 = $pdo->prepare("INSERT INTO historique (Id_user, Type_user, Action_his, Details_his, Date_his) VALUES (?, ?, ?, ?, ?)");
                    $rslt3->execute([$id_user, $type_user, $action, $details, $date]);
                    header("Location: accueil.php");
                    exit;
                }
                else{
                    $_SESSION['erreurMdps'] = "Mot de passe incorrecte";
                    header("location:index.php");
                    exit;
                }
            }
            else{
                setcookie('Email',$email,time()-1);
                $_SESSION['erreurEmail'] = "Email incorrecte";
                header("location:index.php");
                exit;
            }
        }
    }
} catch(PDOException $e) {
    $_SESSION['erreurlog'] = "Erreur lors de la connexion : " . $e->getMessage();
    header("Location: index.php");
    exit;
}
// déconnexion
if(isset($_GET['déconnexion'])){
    // Récupération du nom de l'appareil connecté
    $hostname = gethostname();

    // Insertion de l'action de déconnexion dans la table "historique"
    if(admin()) {
        $id_user = $_SESSION['admin']['Id_admin'];
        $action = "Déconnexion";
        $type_user = "admin";
        $email=$_SESSION['admin']['Email_admin'];
        
    }
    else {
        $id_user = $_SESSION['manager']['Id_manager'];
        $action = "Déconnexion";
        $type_user = "manager";
        $email=$_SESSION['manager']['Email_manager'];
    }
    $details = "" .$email ." s'est déconnecté depuis l'appareil " . $hostname;
    $date = date('Y-m-d H:i:s');
    $rslt3 = $pdo->prepare("INSERT INTO historique (Id_user, Type_user, Action_his, Details_his, Date_his) VALUES (?,?, ?, ?, ?)");
    $rslt3->execute([$id_user, $type_user, $action, $details, $date]);

    // Suppression des données de session et des cookies
    unset($_SESSION['jeton']);
    session_unset();
    $_SESSION = array();
    setcookie(session_name(), ' ', time()-1);
    session_destroy();
    header('Location:index.php');
    exit;
}
//suppression et modification de manager
if (isset($_POST['jeton']) && $_POST['jeton'] == $_SESSION['jeton']) {
    switch ($_POST['action']) {
        case 'supprimer_manager':
            $id_manager = $_POST['Id_manager'];
            $stmt = $pdo->prepare("DELETE FROM projet WHERE Id_manager = ?");
            $stmt->execute([$id_manager]);
            $stmt = $pdo->prepare("DELETE FROM manager WHERE Id_manager = ?");
            $stmt->execute([$id_manager]);
            
            $user_id=$_SESSION['admin']['Id_admin'];
            $user_type='admin';
            $action="Suppression manager";
            $details="manager avec l\'id $id_manager et le nom  ";
            $stmt = $pdo->prepare("INSERT INTO historique  VALUES (null,?, ?, ?, NOW(), ?)");
            $stmt->execute([$user_id, $user_type, $action, $details]);
            header("location: compte.php");
            exit;
            break;

        case 'modifier_manager':
            $id_manager = trim($_POST['Id_manager']);
            $nom_manager = trim($_POST['Nom_manager']);
            $email_manager = trim($_POST['Email_manager']);
            $mdps_manager = $_POST['Mdps_manager'];
            $stmt = $pdo->prepare("UPDATE manager SET Nom_manager = ?, Email_manager = ?, Mdps_manager = ? WHERE manager.Id_manager = ?");
            $stmt->execute([$nom_manager, $email_manager, $mdps_manager, $id_manager]);

            $user_id=$_SESSION['admin']['Id_admin'];
            $user_type='admin';
            $action="Modification manager";
            $details="manager avec l'id  $id_manager";
            $stmt = $pdo->prepare("INSERT INTO historique  VALUES (null,?, ?, ?, NOW(), ?)");
            $stmt->execute([$user_id, $user_type, $action, $details]);
            

            header("location: compte.php");
            exit;
            break;

        case 'ajouter_manager':
            $nom_manager = trim($_POST['Nom_manager']);
            $email_manager = trim($_POST['Email_manager']);
            $mdps_manager = $_POST['Mdps_manager'];
            $stmt=$pdo->prepare("INSERT INTO manager value (null,?,null,null,?,?)");
            $stmt->execute([$nom_manager,$email_manager,$mdps_manager]);
            
            $id_manager = $pdo->lastInsertId();
            $user_id=$_SESSION['admin']['Id_admin'];
            $user_type='admin';
            $action="Ajout manager";
            $details="manager avec l'id $id_manager";
            $stmt = $pdo->prepare("INSERT INTO historique  VALUES (null,?, ?, ?, NOW(), ?)");
            $stmt->execute([$user_id, $user_type, $action, $details]);
            
            header("location: compte.php");
            exit;
            break;
        
        case 'ajouter_projet':
            $id_manager=$_POST['Id_manager'];    
            $nom_projet=$_POST['Nom_projet'];  
            $code_projet=$_POST['Code_projet']; 
            $date_projet=$_POST['Date_projet']; 
            $estimated_projet=$_POST['Estimated_projet']; 
            $burned_projet=$_POST['Burned_projet']; 
            $stmt=$pdo->prepare("INSERT INTO projet VALUE(NULL,?,?,?,?,1,1,?,?)");
            $stmt->execute([$id_manager,$code_projet,$nom_projet,$date_projet,$estimated_projet,$burned_projet]);

            $id_projet = $pdo->lastInsertId();
            $action="Ajout projet";
            $user_id=admin() ? $_SESSION['admin']['Id_admin']:$_SESSION['manager']['Id_manager'];
            $user_type=admin() ? "admin":'manager';
            $details="Projet avec l'id  $id_projet";
            $stmt = $pdo->prepare("INSERT INTO historique  VALUES (null,?, ?, ?, NOW(), ?)");
            $stmt->execute([$user_id, $user_type, $action, $details]);
            
            header("location: projet.php");
                exit;
                break;

        case 'modifier_projet':
            $id_projet = $_POST['id_projet'];
            $id_manager=$_POST['manager_pr'];    
            $nom_projet=$_POST['nom_pr'];  
            $code_projet=$_POST['code_pr']; 
            $date_projet=$_POST['date_pr']; 
            $id_phase=$_POST['phase_pr'];
            $Id_discipline=$_POST['discipline_pr'];
            $estimated_projet=$_POST['estimated_pr']; 
            $burned_projet=$_POST['burned_pr']; 
            $stmt = $pdo->prepare("UPDATE projet SET Id_manager=?, Code_projet=?, Nom_projet=?, Date_projet=?, Id_phase=?, Id_discipline=?, Estimated=?, Burned=? WHERE Id_projet=?");
            $stmt->execute([$id_manager, $code_projet, $nom_projet, $date_projet, $id_phase, $Id_discipline, $estimated_projet, $burned_projet, $id_projet]);
            
            $action="Modification projet";
            $user_id=admin() ? $_SESSION['admin']['Id_admin']:$_SESSION['manager']['Id_manager'];
            $user_type=admin() ? "admin":'manager';
            $details="projet avec l'id $id_projet";
            $stmt = $pdo->prepare("INSERT INTO historique  VALUES (null,?, ?, ?, NOW(), ?)");
            $stmt->execute([$user_id, $user_type, $action, $details]);
            header("location: projet.php");
                exit;
                break;

        case 'supprimer_projet':
            $id_projet = $_POST['Id_projet'];
            $stmt = $pdo->prepare("DELETE FROM projet WHERE Id_projet = ?");
            $stmt->execute([$id_projet]);
            
            $action="Suppression projet";
            $user_id=admin() ? $_SESSION['admin']['Id_admin']:$_SESSION['manager']['Id_manager'];
            $user_type=admin() ? "admin":'manager';
            $details="projet avec l'id  $id_projet";
            $stmt = $pdo->prepare("INSERT INTO historique  VALUES (null,?, ?, ?, NOW(), ?)");
            $stmt->execute([$user_id, $user_type, $action, $details]);
            header("location: projet.php");
            exit;
            break;

        case 'modifier_profil':
            $id_manager=$_POST['Id_manager'];    
            $nom=$_POST['nom_p'];
            $date=$_POST['date_nc_p'];
            $email=$_POST['email_p'];
            $tel=$_POST['tel_p'];
            $mdps=$_POST['mdps_p'];
            if(admin()){
                $stmt = $pdo->prepare("UPDATE admin SET Nom_admin = ?, Date_admin = ?, Tele_admin = ?, Email_admin = ?, Mdps_admin = ?");
                $stmt->execute([$nom, $date, $tel, $email, $mdps, ]);

            }
            if(manager()){
                $stmt = $pdo->prepare("UPDATE manager SET Nom_manager = ?, Date_manager = ?, Tele_manager = ?, Email_manager = ?, Mdps_manager = ? WHERE Id_manager = ?");
                $stmt->execute([$nom, $date, $tel, $email, $mdps, $id_manager]);
            }
            header("location: profil.php");
                exit;
                break;
            

                case 'supprimer_discipline':
                    $id_discipline = $_POST['Id_discipline'];
                    $numero_discipline = $_POST['numero_discipline'];
                    $id_phase = $_POST['Id_phase'];
                    
                    // Mettre à jour les projets associés avec la discipline suivante ou la discipline précédente (si elles existent)
                    $stmt = $pdo->prepare("SELECT Id_discipline FROM discipline WHERE Id_phase = ? AND numero_discipline > ? ORDER BY numero_discipline ASC LIMIT 1");
                    $stmt->execute([$id_phase, $numero_discipline]);
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($row) {
                        // Il y a une discipline suivante, donc mettre à jour les projets associés avec la discipline suivante
                        $id_discipline_suivante = $row['Id_discipline'];
                        $stmt = $pdo->prepare("UPDATE projet SET Id_discipline = ? WHERE Id_discipline = ?");
                        $stmt->execute([$id_discipline_suivante, $id_discipline]);
                    } else {
                        // Il n'y a pas de discipline suivante, donc trouver la discipline précédente
                        $stmt = $pdo->prepare("SELECT Id_discipline FROM discipline WHERE Id_phase = ? AND numero_discipline < ? ORDER BY numero_discipline DESC LIMIT 1");
                        $stmt->execute([$id_phase, $numero_discipline]);
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($row) {
                            // Il y a une discipline précédente, donc mettre à jour les projets associés avec la discipline précédente
                            $id_discipline_precedente = $row['Id_discipline'];
                            $stmt = $pdo->prepare("UPDATE projet SET Id_discipline = ? WHERE Id_discipline = ?");
                            $stmt->execute([$id_discipline_precedente, $id_discipline]);
                        } else {
                            // Il n'y a pas de discipline suivante ni précédente, donc trouver la dernière discipline de la phase précédente
                            $stmt = $pdo->prepare("SELECT Id_discipline FROM discipline WHERE Id_phase < ? ORDER BY numero_phase DESC, numero_discipline DESC LIMIT 1");
                            $stmt->execute([$id_phase]);
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            if ($row) {
                                // Il y a une dernière discipline de la phase précédente, donc mettre à jour les projets associés avec cette discipline
                                $id_derniere_discipline = $row['Id_discipline'];
                                $stmt = $pdo->prepare("UPDATE projet SET Id_discipline = ? WHERE Id_discipline = ?");
                                $stmt->execute([$id_derniere_discipline, $id_discipline]);
                            }
                        }
                    }
                
                    $stmt = $pdo->prepare("DELETE FROM discipline WHERE Id_discipline = ?");
                    $stmt->execute([$id_discipline]);
                    // Mettre à jour les numéros de discipline des disciplines suivantes (si elles existent)
                    $stmt = $pdo->prepare("UPDATE discipline SET numero_discipline = numero_discipline - 1 WHERE Id_phase = ? AND numero_discipline > ?");
                    $stmt->execute([$id_phase, $numero_discipline]);
                    header("location: phase.php");
                    exit;
                    break;


 case 'ajouter_discipline':
                    $id_phase = $_POST['Id_phase'];
                    $titre_discipline = $_POST['titre_discipline'];
                    $numero_discipline = $_POST['numero_discipline'];
                
                    // Récupérer le dernier numéro de la discipline pour cette phase
                    $stmt = $pdo->prepare("SELECT MAX(numero_discipline) AS last_numero FROM discipline WHERE Id_phase = ?");
                    $stmt->execute([$id_phase]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $last_numero = $result['last_numero'];
                
                    // Vérifier si le numéro de la nouvelle discipline est correct
                    if ($numero_discipline <= 0) {
                        // Numéro invalide, retourner une erreur
                        header("location: phase.php");
                        exit;
                    } elseif ($numero_discipline <= $last_numero) {
                        // Mettre à jour les numéros de toutes les disciplines entre le dernier numéro et le nouveau numéro
                        $stmt = $pdo->prepare("UPDATE discipline SET numero_discipline = numero_discipline + 1 WHERE Id_phase = ? AND numero_discipline >= ?");
                        $stmt->execute([$id_phase,$numero_discipline]);
                    } else {
                        // Insérer la nouvelle discipline avec le dernier numéro + 1
                        $stmt = $pdo->prepare("INSERT INTO discipline (Id_phase, Titre_discipline, numero_discipline) VALUES (?, ?, ?)");
                        $stmt->execute([$id_phase, $titre_discipline, $last_numero + 1]);
                        header("location: phase.php");
                        exit;
                    }
                
                    // Insérer la nouvelle discipline dans la base de données avec le numéro donné
                    $stmt = $pdo->prepare("INSERT INTO discipline (Id_phase, Titre_discipline, numero_discipline) VALUES (?, ?, ?)");
                    $stmt->execute([$id_phase, $titre_discipline, $numero_discipline]);
                    header("location: phase.php");
                    exit;
                    break;
                
     case 'modifier_discipline':
                    $id_discipline = $_POST['id_discipline'];
                    $titre_discipline = $_POST['titre_discipline'];
                    $numero_discipline = $_POST['numero_discipline'];
                    $id_phase = $_POST['Id_phase'];
                    $ancien_numero= $_POST['ancien_numero'];                
                    // Récupérer le dernier numéro de la discipline pour cette phase
                    $stmt = $pdo->prepare("SELECT MAX(numero_discipline) AS last_numero FROM discipline WHERE Id_phase = ?");
                    $stmt->execute([$id_phase]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $last_numero = $result['last_numero'];
                
                    // Vérifier si le numéro de la nouvelle discipline est correct
                    if ($numero_discipline <= 0) {
                        // Numéro invalide, retourner une erreur
                        header("location: phase.php");
                        exit;
                    } elseif ($numero_discipline <= $last_numero) {
                        // Mettre à jour les numéros de toutes les disciplines entre l'ancien numéro et le nouveau numéro
                        if ($numero_discipline <  $ancien_numero) {
                            // ordre croissant
                            $stmt = $pdo->prepare("UPDATE discipline SET numero_discipline = numero_discipline + 1 WHERE Id_phase = ? AND numero_discipline >= ? AND numero_discipline < ? AND Id_discipline != ?");
                            $stmt->execute([$id_phase,$numero_discipline, $ancien_numero,$id_discipline]);
                        } elseif ($numero_discipline >  $ancien_numero) {
                            // ordre décroissant
                            $stmt = $pdo->prepare("UPDATE discipline SET numero_discipline = numero_discipline - 1 WHERE Id_phase = ? AND numero_discipline > ? AND numero_discipline <= ? AND Id_discipline != ?");
                            $stmt->execute([$id_phase, $ancien_numero,$numero_discipline,$id_discipline]);
                        }               
                    } else {
                        // Mettre à jour la discipline existante avec le dernier numéro + 1 si c'est la dernière discipline
                        if ( $ancien_numero == $last_numero) {
                            $numero_discipline=$last_numero;
                        }
                        else{
                            $stmt = $pdo->prepare("UPDATE discipline SET numero_discipline = numero_discipline -1 WHERE Id_phase = ? AND numero_discipline > ? AND Id_discipline != ?");
                            $stmt->execute([$id_phase, $ancien_numero,$id_discipline]);
                            $numero_discipline=$last_numero;
                        }
                    } 
                                
                    // Mettre à jour la discipline dans la base de données avec le numéro donné
                    $stmt = $pdo->prepare("UPDATE discipline SET Titre_discipline = ?, numero_discipline = ? WHERE Id_discipline = ?");
                    $stmt->execute([$titre_discipline, $numero_discipline, $id_discipline]);
                    header("location: phase.php");
                    exit;
                    break;

case 'ajouter_phase':

                    $titrePhase = $_POST['titre_phase'];
                    $numeroPhase = $_POST['numero_phase'];
                    $disciplines = $_POST['titre_discipline'];
                    // Vérifier si le numéro de la nouvelle phase est correct
                    if ($numeroPhase <= 0) {
                        // Numéro invalide, retourner une erreur
                        header("location: phase.php");
                        exit;
                    } else {
                        // Récupérer le dernier numéro de phase dans la base de données
                        $stmt = $pdo->prepare("SELECT MAX(numero_phase) AS last_numero FROM phase");
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        $last_numero = $result['last_numero'];

                        if ($numeroPhase <= $last_numero) {
                            // Mettre à jour les numéros de toutes les phases entre le dernier numéro et le nouveau numéro
                            $stmt = $pdo->prepare("UPDATE phase SET numero_phase = numero_phase + 1 WHERE numero_phase >= ?");
                            $stmt->execute([$numeroPhase]);
                        }
                        else{
                        $numeroPhase=$last_numero+1;
                        }
                    }
                    // Insérer la phase dans la base de données
                    $stmt = $pdo->prepare('INSERT INTO phase (Titre_phase, numero_phase) VALUES (?, ?)');
                    $stmt->execute([$titrePhase, $numeroPhase]);
                    $idPhase = $pdo->lastInsertId();

                    if($_POST['titre_discipline'] ){
                        $numeroDiscipline = 1;
                        foreach ($disciplines as $discipline) {
                            $titreDiscipline = htmlspecialchars($discipline, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                            if (!empty($titreDiscipline)) {
                                // Insérer la discipline dans la base de données
                                $stmt = $pdo->prepare('INSERT INTO discipline (Id_phase, Titre_discipline, numero_discipline) VALUES (?, ?, ?)');
                                $stmt->execute([$idPhase, $titreDiscipline, $numeroDiscipline]);
                                $numeroDiscipline++;
                            }
                        }
                    }
                   
                    header("location: phase.php");
                    exit;
                    break;

        case 'supprimer_phase':
                        $id_phase = $_POST['id_phase'];
                    
                        // Trouver le numéro de phase de la phase à supprimer
                        $stmt = $pdo->prepare("SELECT numero_phase FROM phase WHERE Id_phase = ?");
                        $stmt->execute([$id_phase]);
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $numero_phase = $row['numero_phase'];
                    
                        // Trouver la phase suivante en utilisant l'ordre des numéros de phase
                        $stmt = $pdo->prepare("SELECT Id_phase FROM phase WHERE numero_phase > ? ORDER BY numero_phase ASC LIMIT 1");
                        $stmt->execute([$numero_phase]);
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $id_phase_suivante = $row ? $row['Id_phase'] : null;
                    
                        // Trouver la phase précédente en utilisant l'ordre des numéros de phase
                        $stmt = $pdo->prepare("SELECT Id_phase FROM phase WHERE numero_phase < ? ORDER BY numero_phase DESC LIMIT 1");
                        $stmt->execute([$numero_phase]);
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $id_phase_precedente = $row ? $row['Id_phase'] : null;
                    
                        if ($id_phase_suivante !== null) {

                            $stmt = $pdo->prepare("SELECT Id_discipline FROM discipline WHERE Id_phase = ? ORDER BY numero_discipline ASC LIMIT 1");
                            $stmt->execute([$id_phase_suivante]);
                            $id_premiere_discipline_phase_suivante = $stmt->fetchColumn();
                        
                            $stmt = $pdo->prepare("UPDATE projet SET Id_discipline = ? WHERE Id_phase = ?");
                            $stmt->execute([$id_premiere_discipline_phase_suivante, $id_phase]);

                            $stmt = $pdo->prepare("UPDATE projet SET Id_phase = ? WHERE Id_phase = ?");
                            $stmt->execute([$id_phase_suivante, $id_phase]);
                        
                            
                        } elseif ($id_phase_precedente !== null) {
                           

                            $stmt = $pdo->prepare("SELECT Id_discipline FROM discipline WHERE Id_phase = ? ORDER BY numero_discipline DESC LIMIT 1");
                            $stmt->execute([$id_phase_precedente]);
                            $id_dernier_discipline_phase_precedente = $stmt->fetchColumn();
                        

                            $stmt = $pdo->prepare("UPDATE projet SET Id_discipline = ? WHERE Id_phase = ?");
                            $stmt->execute([$id_dernier_discipline_phase_precedente, $id_phase]);

                            $stmt = $pdo->prepare("UPDATE projet SET Id_phase = ? WHERE Id_phase = ?");
                            $stmt->execute([$id_phase_precedente, $id_phase]);
                        }
                        
                    
                        $stmt = $pdo->prepare("DELETE FROM discipline WHERE Id_phase = ?");
                        $stmt->execute([$id_phase]);
                        // Supprimer la phase elle-même
                        $stmt = $pdo->prepare("DELETE FROM phase WHERE Id_phase = ?");
                        // Mettre à jour les numéros de phase de toutes les phases suivantes
                        if($stmt->execute([$id_phase])){
                            $stmt = $pdo->prepare("UPDATE phase SET numero_phase = numero_phase - 1 WHERE numero_phase > ?");
                            $stmt->execute([$numero_phase]);
                        }
                    
                        // Rediriger vers la page de gestion des phases
                        header("location: phase.php");
                        exit;
                        break;

case 'modifier_phase':
                            // Vérifier que les données du formulaire ont été soumises
                            // Récupérer les données du formulaire
                            $idPhase = $_POST['id_phase'];
                            $titrePhase = $_POST['titre_phase'];
                            $numeroPhase = $_POST['numero_phase'];
                            $ancien_numero= $_POST['ancien_numero'];
                            // Vérifier si le numéro de la nouvelle phase est correct
                            if ($numeroPhase <= 0) {
                                // Numéro invalide, retourner une erreur
                                header("location: phase.php");
                                exit;
                            } else {
                                // Récupérer le dernier numéro de phase
                                
                                $stmt = $pdo->prepare("SELECT MAX(numero_phase) AS last_numero FROM phase");
                                $stmt->execute();
                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                $last_numero = $result['last_numero'];
                        
                                if ($numeroPhase <= $last_numero) {
                                    // Mettre à jour les numéros de toutes les phases entre l'ancien numéro et le nouveau numéro
    
                                    if ($numeroPhase < $ancien_numero) {
                                        // ordre croissant
                                        $stmt = $pdo->prepare("UPDATE phase SET numero_phase = numero_phase + 1 WHERE numero_phase >= ? AND numero_phase < ? AND Id_phase != ?");
                                        $stmt->execute([$numeroPhase, $ancien_numero, $idPhase]);
                                    } elseif ($numeroPhase > $ancien_numero) {
                                        // ordre décroissant
                                        $stmt = $pdo->prepare("UPDATE phase SET numero_phase = numero_phase - 1 WHERE numero_phase > ? AND numero_phase <= ? AND Id_phase != ?");
                                        $stmt->execute([$ancien_numero, $numeroPhase, $idPhase]);
                                    }
                        
                                } else  {
                                    // Mettre à jour la phase existante avec le dernier numéro + 1 si c'est la dernière phase
                                    if ($ancien_numero == $last_numero) {
                                        $numeroPhase=$last_numero;
                                    }
                                    else{
                                        $stmt = $pdo->prepare("UPDATE phase SET numero_phase = numero_phase - 1 WHERE numero_phase > ?  AND Id_phase != ?");
                                        $stmt->execute([$ancien_numero, $idPhase]);
                                        $numeroPhase=$last_numero;
                                    }
                                } 
                            }
                        
                            // Mettre à jour les données de la phase dans la base de données
                            $stmt = $pdo->prepare("UPDATE phase SET Titre_phase=?, numero_phase=? WHERE Id_phase=?");
                            $stmt->execute([$titrePhase, $numeroPhase, $idPhase]);
                        

                            if($_POST['titre_discipline']){
                                $titreDisciplines = $_POST['titre_discipline'];
                                $idDisciplines = $_POST['id_discipline'];
                                // Récupérer le dernier numéro de discipline pour la phase donnée
                                $stmt = $pdo->prepare("SELECT MAX(numero_discipline) AS last_numero FROM discipline WHERE Id_phase = ?");
                                $stmt->execute([$idPhase]);
                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                $last_numero = $result['last_numero'];
                            
                                // Mettre à jour ou ajouter les disciplines de la phase dans la base de données
                                $stmtUpdate = $pdo->prepare("UPDATE discipline SET Titre_discipline=? WHERE Id_discipline=? AND Id_phase=?");
                                $stmtInsert = $pdo->prepare("INSERT INTO discipline (Titre_discipline, Id_phase, numero_discipline) VALUES (?, ?, ?)");
                            
                                for ($i = 0; $i < count($titreDisciplines); $i++) {
                                    $titreDiscipline = $titreDisciplines[$i];
                                    $idDiscipline = $idDisciplines[$i];
                            
                                    if (!empty($titreDiscipline)) {
                                        if (!empty($idDiscipline)) {
                                            // Mettre à jour la discipline existante dans la base de données
                                            $stmtUpdate->execute([$titreDiscipline, $idDiscipline, $idPhase]);
                                        } else {
                                            // Ajouter une nouvelle discipline à la phase dans la base de données
                                            $last_numero++;
                                            $stmtInsert->execute([$titreDiscipline, $idPhase, $last_numero]);
                                        }
                                    }
                                }
                        
                            }
                            

                           
                            // Rediriger vers la page de gestion des phases
                            header("location: phase.php");
                            exit;
    }
}
// if(isset($_POST["action"])&&$_POST['action']=='ajouter_phase'){
//     $titrePhase = $_POST['titre_phase'];
//     $numeroPhase = $_POST['numero-phase'];
//     $disciplines = $_POST['discipline'];


//     // Vérifier si le numéro de la nouvelle phase est correct
//     if ($numeroPhase <= 0) {
//         // Numéro invalide, retourner une erreur
//         header("location: erreur.php?message=Le numéro de la phase est invalide");
//         exit;
//     } else {
//         // Récupérer le dernier numéro de phase dans la base de données
//         $stmt = $pdo->prepare("SELECT MAX(numero_phase) AS last_numero FROM phase");
//         $stmt->execute();
//         $result = $stmt->fetch(PDO::FETCH_ASSOC);
//         $last_numero = $result['last_numero'];

//         if ($numeroPhase <= $last_numero) {
//             // Mettre à jour les numéros de toutes les phases entre le dernier numéro et le nouveau numéro
//             $stmt = $pdo->prepare("UPDATE phase SET numero_phase = numero_phase + 1 WHERE numero_phase >= ?");
//             $stmt->execute([$numeroPhase]);
//         }
//         else{
//            $numeroPhase=$last_numero+1;
//         }
//     }

//     // Insérer la phase dans la base de données
//     $stmt = $pdo->prepare('INSERT INTO phase (Titre_phase, numero_phase) VALUES (?, ?)');
//     $stmt->execute([$titrePhase, $numeroPhase]);
//     $idPhase = $pdo->lastInsertId();

//     $numeroDiscipline = 1;
//     foreach ($disciplines as $discipline) {
//         $titreDiscipline = htmlspecialchars($discipline, ENT_QUOTES | ENT_HTML5, 'UTF-8');
//         if (!empty($titreDiscipline)) {
//             // Insérer la discipline dans la base de données
//             $stmt = $pdo->prepare('INSERT INTO discipline (Id_phase, Titre_discipline, numero_discipline) VALUES (?, ?, ?)');
//             $stmt->execute([$idPhase, $titreDiscipline, $numeroDiscipline]);
//             $numeroDiscipline++;
//         }
//     }
//     header("location: phase.php");
//         exit;
       
// }

if(isset($_GET['id_recu'])){
    $id_manager = $_GET['id_recu'];
    $stmt = $pdo->prepare("SELECT * FROM manager WHERE Id_manager = ?");
    $stmt->execute([$id_manager]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    header('Content-Type: application/json'); // Ajouter cette ligne pour spécifier que la réponse est en JSON
    echo json_encode($row);
}
if (isset($_GET['id_discipline_recu'])) {
    $id_discipline = $_GET['id_discipline_recu'];
    $stmt = $pdo->prepare("SELECT * FROM discipline WHERE  Id_discipline=?");
    $stmt->execute([$id_discipline]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Renvoyer une réponse JSON avec les données de la discipline
        header('Content-Type: application/json');
        echo json_encode($row);
    } else {
        // Renvoyer une réponse JSON vide avec un message d'erreur
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Aucune discipline trouvée pour l\'ID de la discipline.']);
    }
}

if(isset($_POST['recu_form_modif_phase'])){
    $phaseId = $_POST['id'];

    // Récupérer les données de la phase depuis la base de données
    $stmt = $pdo->prepare('SELECT * FROM phase WHERE Id_phase = :id');
    $stmt->execute(['id' => $phaseId]);
    $phaseData = $stmt->fetch();
  
    // Récupérer les données des disciplines associées à la phase
    $stmt = $pdo->prepare('SELECT * FROM discipline WHERE Id_phase = :id ORDER BY numero_discipline');
    $stmt->execute(['id' => $phaseId]);
    $disciplinesData = $stmt->fetchAll();
  
    // Renvoyer les données de la phase et des disciplines sous forme de JSON
    $data = array('Id_phase' => $phaseData['Id_phase'], 'Titre_phase' => $phaseData['Titre_phase'], 'numero_phase' => $phaseData['numero_phase'], 'disciplines' => $disciplinesData);
    echo json_encode($data);
}


if(isset($_POST['id_projet_voir'])){
    $id_projet=$_POST['id_projet_voir'];
    $stmt=$pdo->prepare("SELECT p.* , ph.* , d.* , m.*
    FROM projet p 
    inner join manager m on p.Id_manager=m.Id_manager
    left join phase ph on p.Id_phase=ph.Id_phase 
    left JOIN discipline d ON p.Id_discipline= d.Id_discipline
    where Id_projet=?");
    $stmt->execute([$id_projet]);
    $projet_row = $stmt->fetch(PDO::FETCH_ASSOC);

// Generate HTML code for the project information
if(admin()){
    $html = "<div><strong>Manager:</strong> " . htmlspecialchars($projet_row['Nom_manager']) . "</div>";
    $html .= "<div><strong>Code projet:</strong> " . htmlspecialchars($projet_row['Code_projet']) . "</div>"
      . "<div><strong>Nom projet:</strong> " . htmlspecialchars($projet_row['Nom_projet']) . "</div>"
      . "<div><strong>Date projet:</strong> " . htmlspecialchars($projet_row['Date_projet']) . "</div>"
      . "<div><strong>Phase:</strong> " . htmlspecialchars($projet_row['Titre_phase']) . "</div>"
      . "<div><strong>Discipline:</strong> " . htmlspecialchars($projet_row['Titre_discipline']) . "</div>"
      . "<div><strong>Estimated:</strong> " . htmlspecialchars($projet_row['Estimated']) . "</div>"
      . "<div><strong>Burned:</strong> " . htmlspecialchars($projet_row['Burned']) . "</div>";

}
if(manager()){
    $html = "<div><strong>Code projet:</strong> " . htmlspecialchars($projet_row['Code_projet']) . "</div>"
      . "<div><strong>Nom projet:</strong> " . htmlspecialchars($projet_row['Nom_projet']) . "</div>"
      . "<div><strong>Date projet:</strong> " . htmlspecialchars($projet_row['Date_projet']) . "</div>"
      . "<div><strong>Phase:</strong> " . htmlspecialchars($projet_row['Titre_phase']) . "</div>"
      . "<div><strong>Discipline:</strong> " . htmlspecialchars($projet_row['Titre_discipline']) . "</div>"
      . "<div><strong>Estimated:</strong> " . htmlspecialchars($projet_row['Estimated']) . "</div>"
      . "<div><strong>Burned:</strong> " . htmlspecialchars($projet_row['Burned']) . "</div>";

}
// Return the HTML code
echo $html;
}



if (isset($_POST['Email_exist_modif'])) {
    $email = trim($_POST['Email_exist_modif']);
    $id_manager = trim($_POST['Id_manager']);
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM manager WHERE Email_manager = :email AND Id_manager != :id_manager");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id_manager', $id_manager);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    if ($count == 0) {
        echo "0"; // l'email n'existe pas dans la base de données
    } else {
        echo "1"; // l'email existe déjà dans la base de données
    }
}
if (isset($_POST['Email_exist_ajout'])) {
    $email = trim($_POST['Email_exist_ajout']);
    // $id_manager = trim($_POST['Id_manager']);
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM manager WHERE Email_manager = :email ");
    $stmt->bindParam(':email', $email);
    // $stmt->bindParam(':id_manager', $id_manager);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    if ($count == 0) {
        echo "0"; // l'email n'existe pas dans la base de données
    } else {
        echo "1"; // l'email existe déjà dans la base de données
    }
}

if (isset($_POST['updateDiscipline'])) {
    $Id_discipline = $_POST['Id_discipline'];
    $Id_phase = $_POST['Id_phase'];
    $Id_projet = $_POST['Id_projet'];

    // Chercher la discipline actuelle
    $rslt1 = $pdo->query("SELECT * FROM discipline WHERE Id_discipline = '$Id_discipline'");
    $row1 = $rslt1->fetch();

    // Chercher la phase actuelle
    $rslt3 = $pdo->query("SELECT * FROM phase WHERE Id_phase = '$Id_phase'");
    $row3 = $rslt3->fetch();

    // Trouver le numéro de la prochaine discipline
    $num = ++$row1['numero_discipline'];

    // Chercher la prochaine discipline dans la même phase
    $rslt2 = $pdo->query("SELECT * FROM discipline WHERE numero_discipline = '$num' AND Id_phase = '$Id_phase'");
    $row2 = $rslt2->fetch();

    if ($row2) {
        // Mettre à jour le projet avec la prochaine discipline
        $rslt = $pdo->exec("UPDATE projet SET Id_discipline = '$row2[Id_discipline]', Id_phase = '$Id_phase' WHERE Id_projet = '$Id_projet'");

    } else {
        // Si la prochaine discipline n'existe pas dans la même phase, chercher la prochaine phase
        $phase = ++$row3['numero_phase'];
        $rslt4 = $pdo->query("SELECT * FROM phase WHERE numero_phase = '$phase'");
        $row4 = $rslt4->fetch();

        if($row4){
            // Chercher la première discipline de la prochaine phase
            $rslt5 = $pdo->query("SELECT * FROM discipline WHERE numero_discipline = '1' AND Id_phase = '$row4[Id_phase]'");
            $row5 = $rslt5->fetch();
            $rslt = $pdo->exec("UPDATE projet SET Id_discipline = '$row5[Id_discipline]', Id_phase = '$row5[Id_phase]' WHERE Id_projet = '$Id_projet'");
        }
        else{
            // Si la prochaine phase n'existe pas, mettre à jour le projet avec la discipline et la phase actuelles
            $rslt = $pdo->exec("UPDATE projet SET Id_discipline = '$_POST[Id_discipline]', Id_phase = '$_POST[Id_phase]' WHERE Id_projet = '$Id_projet'");

            // Vérifier si la dernière discipline de la dernière phase est sélectionnée
            $rslt_last_discipline = $pdo->query("SELECT * FROM discipline WHERE Id_phase = (SELECT MAX(Id_phase) FROM phase) ORDER BY numero_discipline DESC LIMIT 1");
            $last_discipline = $rslt_last_discipline->fetch();
            if ($last_discipline['Id_discipline'] == $_POST['Id_discipline']) {
                // Désactiver et cocher la dernière discipline
                echo "<script>$('#last_discipline').prop('disabled', true); $('#last_discipline').prop('checked', true);</script>";
            }
        }
    }
    // Retourner le résultat de la mise à jour
    echo $rslt;
}
?>