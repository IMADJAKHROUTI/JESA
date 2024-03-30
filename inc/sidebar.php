<?php
    include("./inc/init.inc.php");
    if(!admin() && !manager()){
        header("location:index.php");
    }
    include("./inc/head.inc.html");
?>
<div class="sidebar position-fixed ">
            <nav class="d-flex  flex-column h-100 ">
                <div class="sidebar-sticky d-flex flex-column">
                    <a href="" class="logo_side d-flex col-12">
                        <img src="./inc/img/ocp.png" alt="" class="logo1">
                        <img src="./inc/img/white_logo.png" alt="" class="logo2">
                    </a>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link " href="accueil.php">
                                <i class='bx bx-home  icon'></i>
                                <span class="link_nom">Accueil</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="projet.php">
                                <i class='bx bxl-product-hunt icon'></i>
                                <span class="link_nom">Projets</span>
                            </a>
                        </li>
                        <?php
                            if (admin()) {
                                echo'<li class="nav-item">
                                <a class="nav-link" href="compte.php">
                                    <i class="bx bxs-user icon"></i>
                                    <span class="link_nom">Comptes</span>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link" href="phase.php">
                                <i class="fa-solid fa-square-poll-vertical icon"></i>
                                    <span class="link_nom">phase & discipline</span>
                                </a>
                            </li>';
                            }
                        ?>
                        
                    </ul>
                </div>
                <a class="nav-link" href="controller.php?déconnexion=true">
                    <i class='bx bx-log-out icon'></i>
                    <span class="link_nom">Déconnexion</span>
                </a>
               
            </nav>
        </div>
<div class="container-fluid min-vh-100" id="body" class="">
    <div class="row d-flex min-vh-100">
        
    <header class="fix">
        <a class="toggle_sidebar">
            <i class="fa-solid fa-bars changer"></i>
        </a>
        <div class="dropdown profil">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            Bonjour <span class="bonjour"><?php if(admin()) echo $_SESSION['admin']['Nom_admin'];
                if(manager()) echo $_SESSION['manager']['Nom_manager'];
                 ?></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="profil.php">Profil</a></li>
                <li><a class="dropdown-item" href="controller.php?déconnexion=true">Déconnexion</a></li>
            </ul>
        </div>
    </header>
        

   