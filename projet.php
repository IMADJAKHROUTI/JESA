<?php
    include("./inc/sidebar.php");
?>


        
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header entete_form">
          <h4 class="modal-title">Ajouter project</h4>
          <button type="button" class="btn-close exit_form_action_manager" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body body_form">
          <form class="row g-3 form_ajout" action="controller.php" method="post">
            <input type="hidden" name="jeton" value="<?= $_SESSION['jeton'] ?>">
            <input type="hidden" name="action" value="ajouter_projet">
            
            <?php
                if(manager()){?>
                    <input type="hidden" name="Id_manager" value="<?= $_SESSION['manager']['Id_manager'] ?>">
            <?php } ?>
            <?php
                if(admin()){
                    $stmt=$pdo->prepare("SELECT * from manager");
                    $stmt->execute();
                    $managers=$stmt->fetchAll();
                   ?>
                    <div class='col-md-6 col-sm-12 form-group'>
                        <label for='' class='form-label label'>Manager </label>
                        <select name='Id_manager' id='' class='form-select col-12' >
                        <?php
                            foreach($managers as $manager){?>
                                <option value="<?php echo $manager['Id_manager']; ?>"> <?php echo $manager['Nom_manager']; ?></option>
                                <?php } ?>
                        </select>
                        <label for='' class='form-label err'></label>
                    </div>
                    
                <?php } ?>
        
            
            <div class="col-md-6 col-sm-12 form-group">
              <label for="cd_prjt" class="form-label label">Code </label>
              <input type="text" class="form-control col-12" name="Code_projet" id="cd_prjt">
              <label for="cd_prjt" class="form-label err"></label>
            </div>
            <div class="col-md-6 col-sm-12 form-group">
              <label for="nm_prjt" class="form-label label">Nom </label>
              <input type="text" class="form-control col-12" name="Nom_projet" id="nm_prjt">
              <label for="nm_prjt" class="form-label err"></label>
            </div>
            <div class="col-md-6 col-sm-12 form-group">
              <label for="dt_prjt" class="form-label label">Date </label>
              <input type="date" class="form-control col-12" name="Date_projet" id="dt_prjt">
              <label for="dt_prjt" class="form-label err"></label>
            </div>
            <div class="col-md-6 col-sm-12 form-group">
              <label for="es_prjt" class="form-label label">Estimated ratio </label>
              <input type="text" class="form-control col-12" name="Estimated_projet" id="es_prjt">
              <label for="es_prjt" class="form-label err"></label>
            </div>
            <div class="col-md-6 col-sm-12 form-group">
              <label for="br_prjt" class="form-label label">Burned ratio </label>
              <input type="text" class="form-control col-12" name="Burned_projet" id="br_prjt">
              <label for="br_prjt" class="form-label err"></label>
            </div>
            <div class="modal-footer bas_form">
                <button type="reset" class="btn btn-danger" data-bs-dismiss="">Effacer</button>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>

<div class="dash">
            <main class="dash">
                
                <div class="tablee table-responsive">
                    <div class="recherche">
                    <form id="recherche-form" class=" d-flex">
                        <div class="form-group col-md-3 col-sm-12">
                            <input type="text" class="form-control" id="recherche-nom" placeholder="Nom">
                        </div>
                        <div class="form-group col-md-3 col-sm-12">
                            <input type="text" class="form-control" id="recherche-code" placeholder="Code">
                        </div>
                        <div class="form-group col-md-3 col-sm-12">
                            <input type="text" class="form-control" id="recherche-phase" placeholder="Phase">
                        </div>
                        <div class="form-group col-md-3 col-sm-12">
                            <input type="text" class="form-control" id="recherche-discipline" placeholder="Discipline">
                        </div>
                    </form>
                </div>
                    <table id="mytab" class="table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Code</th>
                                <th>Nom</th>
                                <th>Date</th>
                                <th>Phase</th>
                                <th>Discipline</th>
                                <th>Estimated</th>
                                <th>Burned</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
    
                        <?php
                            if (admin()) {
                                $stmt=$pdo->prepare("SELECT p.* ,ph.* ,d.*
                                FROM projet p 
                                left join phase ph on p.Id_phase=ph.Id_phase 
                                LEFT JOIN discipline d ON p.Id_discipline= d.Id_discipline
                                group by p.Id_projet");
                                $stmt->execute();
                                while($projet_row=$stmt->fetch(pdo::FETCH_ASSOC)){
                                    echo "<tr>
                                    <td>" . htmlspecialchars($projet_row['Id_projet']) . "</td>
                                    <td>" . htmlspecialchars($projet_row['Code_projet']) . "</td>
                                    <td>" . htmlspecialchars($projet_row['Nom_projet']) . "</td>
                                    <td>" . htmlspecialchars($projet_row['Date_projet']) . "</td>
                                    <td>" . htmlspecialchars($projet_row['Titre_phase']) . "</td>
                                    <td>" . htmlspecialchars($projet_row['Titre_discipline']) . "</td>
                                    <td>" . htmlspecialchars($projet_row['Estimated']) . "</td>
                                    <td>" . htmlspecialchars($projet_row['Burned']) . "</td>
                                    <td class='action'>
                                        <button class='btn_voir_prjt center ' >
                                            <i class='fa-sharp fa-solid fa-eye'></i>
                                        </button>
                                        <div class='btn_modif_prjt center' onclick='document.location.href=\"modifier_projet.php?Id_projet=$projet_row[Id_projet]\"'>
                                            <i class='fa-solid fa-pencil' ></i>
                                        </div>
                                        <form action='controller.php' method='post'>
                                        <input type='hidden' name='jeton' value='".$_SESSION['jeton']."'>
                                        <input type='hidden' name='action' value='supprimer_projet'>
                                        <input type='hidden' name='Id_projet' value='" . htmlspecialchars($projet_row['Id_projet']) . "' >
                                          
                                            <button type='submit' class='btn_suprm_prjt center'  onclick=\"return confirm(''Etes-vous sûr de vouloir supprimer ce projet ?');\">
                                                <i class='fa-solid fa-trash-can'></i>
                                            </button>
                                    </form>
                                    </td>
                                </tr>"
                                ;
                                }
                              
                            }
                            else {
                                $Id_manager=$_SESSION['manager']['Id_manager'];
                                $stmt = $pdo->prepare("SELECT m.Id_manager, p.*, ph.* ,d.*
                                FROM manager m
                                INNER JOIN projet p ON m.Id_manager = p.Id_manager
                                LEFT JOIN phase ph ON p.Id_phase = ph.Id_phase
                                LEFT JOIN discipline d ON p.Id_discipline= d.Id_discipline
                                WHERE m.Id_manager = :Id_manager
                                GROUP BY p.Id_projet");
          
                                $stmt->bindParam(':Id_manager', $Id_manager);
                                $stmt->execute();
          
                                while($projet_row=$stmt->fetch(pdo::FETCH_ASSOC)){
                                    echo "<tr>
                                    <td>" . htmlspecialchars($projet_row['Id_projet']) . "</td>
                                    <td>" . htmlspecialchars($projet_row['Code_projet']) . "</td>
                                    <td>" . htmlspecialchars($projet_row['Nom_projet']) . "</td>
                                    <td>" . htmlspecialchars($projet_row['Date_projet']) . "</td>
                                    <td>" . htmlspecialchars($projet_row['Titre_phase']) . "</td>
                                    <td>" . htmlspecialchars($projet_row['Titre_discipline']) . "</td>
                                    <td>" . htmlspecialchars($projet_row['Estimated']) . "</td>
                                    <td>" . htmlspecialchars($projet_row['Burned']) . "</td>
                                    <td class='action'>
                                        <div class='btn_voir_prjt center'>
                                            <i class='fa-sharp fa-solid fa-eye'></i>
                                        </div>
                                        <div class='btn_modif_prjt center' onclick='document.location.href=\"modifier_projet.php?Id_projet=$projet_row[Id_projet]\"'>
                                            <i class='fa-solid fa-pencil'></i>
                                        </div>
                                        <form action='controller.php' method='post'>
                                            <input type='hidden' name='jeton' value='".$_SESSION['jeton']."'>
                                            <input type='hidden' name='action' value='supprimer_projet'>
                                            <input type='hidden' name='Id_projet' value='" . htmlspecialchars($projet_row['Id_projet']) . "' >
                                            
                                                <button type='submit' class='btn_suprm_prjt center'  onclick=\"return confirm(''Etes-vous sûr de vouloir supprimer ce projet');\">
                                                    <i class='fa-solid fa-trash-can'></i>
                                                </button>
                                        </form>
                                    </td>
                                </tr>"
                                ;
                                }
                              
                            }
                        
                        ?>
                            
                        </tbody>
                    </table>
                </div>
                <div class="modal fade" id="projetModal" tabindex="-1" aria-labelledby="projetModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="projetModalLabel">Détails du projet</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body body_details">
                                <!-- Project information will be displayed here -->

                                <div class="modal-footer bas_details">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        
                        </div>
                    </div>
                </div>
            </main>
        </div>



    
    </div>
</div>

<?php 
include("./inc/bas.inc.html");
?>