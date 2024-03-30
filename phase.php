<?php
include("./inc/sidebar.php");
if(!admin()){
    header("location:accueil.php");
}
?>
<div class="dash">
    <main class="dash">
        <div class="col-12 col-md-10 mx-auto container_discipline">
            <div class="ajouter_phase"  class='nav-item' role='presentation'>
                <button class="btn_ajouter_phase" data-bs-toggle="tab" data-bs-target="#ajouter_phase_tabpane">Ajouter phase</button>
            </div>
            <ul class="nav nav-tabs d-none d-md-flex " id="tab_discipline" role="tablist">
                <?php
                    $rslt2 = $pdo->query("SELECT * FROM phase ORDER BY numero_phase");
                    $row2 = $rslt2->fetchAll();
                    foreach ($row2 as $row2) {
                        echo "<li class='nav-item' role='presentation'>
                                <button class='nav-link btn_phase' id='phasee$row2[numero_phase]-tab' data-bs-toggle='tab' data-bs-target='#phasee$row2[numero_phase]-tab-pane' type='button' role='tab' aria-controls='phase1-tab-pane' aria-selected='false'>Phase $row2[numero_phase]</button>
                              </li>";
                    }
                ?>
                 <li class="nav-item" role="presentation" style="display: none;">
                    <a class="nav-link" id="ajouter_phase_tab" data-bs-toggle="tab" href="#ajouter_phase_tabpane" role="tab" aria-controls="ajouter_phase_tabpane" aria-selected="false">Ajouter phase</a>
                </li>
            </ul>
            
                    
            <div class="tab-content  col-sm-12" id="tab_disciplineContent">

                <?php
                    $rslt2 = $pdo->query("SELECT * FROM phase ORDER BY numero_phase");
                    $row2 = $rslt2->fetchAll();

                    foreach ($row2 as $row2) {
                        echo "<div class='tab-pane fade' id='phasee$row2[numero_phase]-tab-pane' role='tabpanel' aria-labelledby='phasee$row2[numero_phase]-tab' tabindex='0'>
                                <div class='div_action_phase'>
                                    <div class='action_phase'>
                                        <h2>$row2[Titre_phase]</h2> 
                                        <button class='btn_modif_phase' data-id-phase='".$row2['Id_phase']."'><i class='fa-solid fa-pencil'></i></button>
                                        <form action='controller.php' method='post'>
                                            <input type='hidden' name='jeton' value='". $_SESSION['jeton']."'>
                                            <input type='hidden' name='id_phase' value='". $row2['Id_phase'] ."'>
                                            <input type='hidden' name='action' value='supprimer_phase'>
                                            <button type='submit' class='btn_supprimer_phase' onclick=\"return confirm('Etes-vous sûr de vouloir supprimer cette phase ?');\"><i class='fa-solid fa-trash-can'></i></button>
                                        </form>
                                    </div>
                                    <div class='div_ajouter_discipline'>
                                        <button class='btn_ajouter_discipline'>Ajouter discipline</button>
                                    </div>
                                </div>
                                <div class='tablee'>
                                    <table class='table' id='table_phase'>
                                        <thead>
                                            <tr>
                                                <th class='col-4' scope='col'>Discipline</th>
                                                <th class='col-5'  scope='col'>Numéro de discipline</th>
                                                <th class='col-3'  scope='col'>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class='tbody_table_phase'>";
                                        $rslt3 = $pdo->query("SELECT * FROM discipline WHERE Id_phase=$row2[Id_phase] order by numero_discipline");
                                        $row3 = $rslt3->fetchAll();
                                        foreach ($row3 as $row3) {
                                            echo "<tr>
                                                    <td>$row3[Titre_discipline]</td>
                                                    <td>$row3[numero_discipline]</td>
                                                    <td class='action'>
                                                        <button class='btn_modif_discipline center' data-id-discipline='$row3[Id_discipline]' data-titre-discipline='$row3[Titre_discipline]' data-numero-discipline='$row3[numero_discipline]'>
                                                            <i class='fa-solid fa-pencil'></i>
                                                        </button>
                                                        </form>
                                                        <form action='controller.php' method='post'>
                                                            <input type='hidden' name='jeton' value='".$_SESSION['jeton']."'>
                                                            <input type='hidden' name='action' value='supprimer_discipline'>
                                                            <input type='hidden' name='Id_phase' value='" . htmlspecialchars($row3['Id_phase']) . "'>
                                                            <input type='hidden' name='numero_discipline' value='" . htmlspecialchars($row3['numero_discipline']) . "'>
                                                            <input type='hidden' name='Id_discipline' value='" . htmlspecialchars($row3['Id_discipline']) . "'>
                                                            <button type='submit' class='btn_suprm_discipline center' onclick=\"return confirm('Etes-vous sûr de vouloir supprimer cette discipline ?');\">
                                                                <i class='fa-solid fa-trash-can'></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                  </tr>
                                                  ";
                                        }                                    
                        echo "
                        <tr class='tr_form_discipline cacher'> 
                        <td colspan='3'>
                            <form class='form_discipline d-flex' method='POST' action='controller.php'>
                                <input type='hidden' name='jeton' value='". $_SESSION['jeton']."'>
                                <input type='hidden' name='Id_phase' value='". $row2['Id_phase'] ."'>
                                <input type='hidden' name='action' value='ajouter_discipline'>
                                <input type='hidden' name='id_discipline' value=''>
                                <input type='hidden' name='ancien_numero' value=''>
                                    <div class='td col-4'>
                                        <input type='text' name='titre_discipline' id=''>
                                    </div>
                                    <div class='td col-5'>
                                        <input type='number' name='numero_discipline' id=''>
                                    </div>
                                    <div class='td col-3'>
                                        <button class='reset_discipline' type='reset'>    
                                            <i class='fa-solid fa-xmark'></i>
                                        </button> 
                                        <button class='valider_discipline' type='submit'>
                                            <i class='fa-solid fa-check'></i>
                                        </button>
                                    </div>
                                </form>
                            </td> 
                        </tr>
                        
                        </tbody></table>
                                        
                        
                        

                        
                        </div>
                        
                        
                        </div>
                      
                        ";
                    }
                
                ?>
                <div class="tab-pane fade" id="ajouter_phase_tabpane" role="tabpanel" aria-labelledby="ajouter_phase_tab" tabindex="0">
                    <form id="form_phase" method="post" action="controller.php">
                        <input type='hidden' name='jeton' value="<?php  echo"$_SESSION[jeton]"?>">
                        <input type='hidden' name='action' value='ajouter_phase'>
                        <div class="input_label mb-md-3">
                            <label for="titre_phase" class="col-md-2 col-sm-12">Titre phase:</label>
                            <input type="text" class="col-md-5 col-sm-12" id="titre_phase" name="titre_phase">
                        </div>

                        <div class="input_label mb-md-3">
                            <label for='num-phase' class="col-md-2 col-sm-12">Numéro phase:</label>
                            <input type='number' class="col-md-5 col-sm-12" id='num-phase' name='numero_phase'>
                        </div>

                        <div class="btn_afficher_input_disc">
                            <h5>Ajouter des disciplines </h5><hr>
                            <button type='button' onclick='ajouterDiscipline()'><i class="fa-solid fa-plus"></i></button>

                        </div>
                        <div id='disciplines'></div>
                        <div class="soum_phase">
                            <button type="reset" class="btn btn-secondary" id="btn_annuler">Annuler</button>
                            <input type="submit" value="Ajouter" id="btn_soum_phase" class="btn btn-primary">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </main>
</div>


<?php 
include("./inc/bas.inc.html");
?>