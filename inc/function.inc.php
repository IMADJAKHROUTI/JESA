<?php
function admin() {
    if(isset($_SESSION['admin'])){
        return true;
    }
    else{
        return false;
    }
}
function manager(){
    if(isset($_SESSION['manager'])){
        return true;
    }
    else{
        return false;
    }
}
function utilisateur_id(){
    if(admin()){
        return $_SESSION["admin"]['Id_admin'];
    }
    if(manager()){
        return $_SESSION['manager']['Id_manager'];
    }
}
// function isEmailUniqueForManager($email, $id_manager, $pdo) {
    
//     $stmt = $pdo->prepare("SELECT COUNT(*) FROM manager WHERE Email_manager = :email AND Id_manager != :id_manager");
//     $stmt->bindParam(':email', $email);
//     $stmt->bindParam(':id_manager', $id_manager);
//     $stmt->execute();
//     $count = $stmt->fetchColumn();
//     return $count == 0;
// }

?>