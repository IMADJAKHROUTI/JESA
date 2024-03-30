<?php
include("./inc/sidebar.php");
?>


<div class="dash">

    <main class="dash">
        <div class="col-md-8 mx-auto">
            <ul class="nav nav-tabs d-none d-md-flex" id="myTab" role="tablist">
                <!-- Onglets de phase pour les écrans de taille moyenne et grande -->
                <li class='nav-item' role='presentation'>
                    <button class='nav-link bt_phase active' id='details-tab' data-bs-toggle='tab' data-bs-target='#details-tab-pane' type='button' role='tab' aria-controls='details-tab-pane' aria-selected='true'>Details</button>
                </li>
                <?php $rslt = $pdo->query("select * from projet natural join phase where Id_projet=$_GET[Id_projet]");
                $row = $rslt->fetch();
                $rslt2 = $pdo->query("select * from phase order by numero_phase");
                $row2 = $rslt2->fetchAll();
                $num = $row['numero_phase'];
                foreach ($row2 as $row2) {
                    if ($num > $row2['numero_phase'] || $num == $row2['numero_phase']) {
                        echo "<li class='nav-item' role='presentation'> <button class='nav-link bt_phase' id='phase$row2[numero_phase]-tab' data-bs-toggle='tab' data-bs-target='#phase$row2[numero_phase]-tab-pane' type='button' role='tab' aria-controls='phase1-tab-pane' aria-selected='false'>Phase $row2[numero_phase]</button> </li>";
                    } else {
                        echo "<li class='nav-item' role='presentation'> <button class='nav-link bt_phase disabled' id='phase$row2[numero_phase]-tab' data-bs-toggle='tab' data-bs-target='#phase$row2[numero_phase]-tab-pane' type='button' role='tab' aria-controls='phase1-tab-pane' aria-selected='false'>Phase $row2[numero_phase]</button> </li>";
                    }
                } ?>
            </ul>
            <!-- Bouton de menu déroulant pour les écrans de petite taille -->
            <div class="d-md-none">
                <div class="btn-group">
                    <a href="#details-tab-pane" class="btn btn-secondary">Details</a>
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Sélectionnez une phase
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <?php
                        $rslt = $pdo->query("select * from projet natural join phase where Id_projet=$_GET[Id_projet]");
                        $row = $rslt->fetch();
                        $rslt2 = $pdo->query("select * from phase order by numero_phase");
                        $row2 = $rslt2->fetchAll();
                        $num = $row['numero_phase'];
                        foreach ($row2 as $row2) {
                            if ($num > $row2['numero_phase'] || $num == $row2['numero_phase']) {
                                echo "<li><a class='dropdown-item' href='#phase$row2[numero_phase]-tab-pane'>Phase $row2[numero_phase]</a></li>";
                            } else {
                                echo "<li><a class='dropdown-item disabled' href='#phase$row2[numero_phase]-tab-pane'>Phase $row2[numero_phase]</a></li>";
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade active show" id="details-tab-pane" role="tabpanel" aria-labelledby="details-tab" tabindex="0">
                    <form action="controller.php" method="post" class="modifier_projet">
                        <input type="hidden" name="jeton" value="<?= $_SESSION['jeton'] ?>">
                        <input type="hidden" name="action" value="modifier_projet">
                        <input type="hidden" name="id_projet" value="<?= $_GET['Id_projet'] ?>">
                        <?php
                        $id_projet = $_GET['Id_projet'];
                        $stmt = $pdo->prepare(
                            "SELECT p.* , ph.* , d.* , m.*
                                FROM projet p 
                                inner join manager m on p.Id_manager=m.Id_manager
                                left join phase ph on p.Id_phase=ph.Id_phase 
                                left JOIN discipline d ON p.Id_discipline= d.Id_discipline
                                where Id_projet=?"
                        );
                        $stmt->execute([$id_projet]);
                        $projet_row = $stmt->fetch(PDO::FETCH_ASSOC);

                        if (admin()) {
                            $stmt = $pdo->prepare("SELECT * FROM manager where Id_manager!=?");
                            $stmt->execute([$projet_row['Id_manager']]);
                            $managers = $stmt->fetchAll();
                            echo "
                                    <div class='div_input_pr col-md-6 col-sm-12'>
                                        <label for='manager_pr'  class='col-md-4 col-sm-4'>Manager</label>
                                        <select name='manager_pr' id='manager_pr'  class='col-md-8 col-sm-12' >
                                        <option value='" . htmlspecialchars($projet_row['Id_manager']) . "'> " . htmlspecialchars($projet_row['Nom_manager']) . "</option>";
                            foreach ($managers as $manager) {
                                echo "<option value='" . $manager['Id_manager'] . "'>" . $manager['Nom_manager'] . "</option>";
                            }
                            echo "
                                        </select>
                                    </div>";
                        }
                        if (manager()) {
                            echo " <input type='hidden' name='manager_pr' value='" . $_SESSION['manager']['Id_manager'] . "'>";
                        }
                        ?>
                        <div class="div_input_pr col-md-6 col-sm-12 ">
                            <label for="nom_pr" class="col-md-4 col-sm-4">Nom</label>
                            <input type="text" name="nom_pr" class="col-md-8 col-sm-12" id="nom_pr" value="<?php echo htmlspecialchars($projet_row['Nom_projet']); ?>">
                        </div>

                        <div class="div_input_pr col-md-6 col-sm-12">
                            <label for="code_pr" class="col-md-4 col-sm-4">Code</label>
                            <input type="text" name="code_pr" id="code_pr" class="col-md-8 col-sm-12" value="<?php echo htmlspecialchars($projet_row['Code_projet']); ?>">
                        </div>
                        <div class="div_input_pr col-md-6 col-sm-12">
                            <label for="date_pr" class="col-md-4 col-sm-4">Date</label>
                            <input type="date" name="date_pr" id="date_pr" class="col-md-8 col-sm-12" value="<?php echo htmlspecialchars($projet_row['Date_projet']); ?>">
                        </div>

                        <div class="div_input_pr col-md-6 col-sm-12">
                            <label for="phase_pr" class="col-md-4 col-sm-4">Phase</label>
                            <select name="phase_pr" id="phase_pr" class="col-md-8 col-sm-12">
                                <option value="<?php echo htmlspecialchars($projet_row['Id_phase']); ?>">
                                    <?php echo htmlspecialchars($projet_row['Titre_phase']); ?> </option>
                                <?php
                                $stmt = $pdo->prepare("SELECT * from phase where Id_phase!=? ");
                                $stmt->execute([$projet_row['Id_phase']]);
                                $phases = $stmt->fetchAll();
                                foreach ($phases as $phase) {
                                    echo "  <option value='" . $phase['Id_phase'] . "'>" . $phase['Titre_phase'] . "</option>";
                                }
                                ?>

                            </select>
                        </div>
                        <div class="div_input_pr col-md-6 col-sm-12">
                            <label for="discipline_pr" class="col-md-4 col-sm-4">Discipline</label>
                            <select name="discipline_pr" id="discipline_pr" class="col-md-8 col-sm-12">
                                <option value="<?php echo htmlspecialchars($projet_row['Id_discipline']); ?>">
                                    <?php echo htmlspecialchars($projet_row['Titre_discipline']); ?> </option>
                                <?php
                                $stmt = $pdo->prepare("SELECT * FROM discipline WHERE Id_discipline != ? AND Id_phase = ? ");
                                $stmt->execute([isset($projet_row['Id_discipline']) ? $projet_row['Id_discipline'] : null, $phase['Id_phase']]);
                                $disciplines = $stmt->fetchAll();
                                foreach ($disciplines as $discipline) {
                                    echo "<option value='" . $discipline['Id_discipline'] . "'>" . $discipline['Titre_discipline'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>



                        <div class="div_input_pr col-md-6 col-sm-12">
                            <label for="estimated_pr" class="col-md-4 col-sm-4">Estimated</label>
                            <input type="text" name="estimated_pr" id="estimated_pr" class="col-md-8 col-sm-12" value="<?php echo htmlspecialchars($projet_row['Estimated']); ?>">
                        </div>
                        <div class="div_input_pr col-md-6 col-sm-12">
                            <label for="burned_pr" class="col-md-4 col-sm-4">Burned</label>
                            <input type="text" name="burned_pr" id="burned_pr" class="col-md-8 col-sm-12" value="<?php echo htmlspecialchars($projet_row['Burned']); ?>">
                        </div>
                        <div class="submit_pr col-md-6 col-sm-12">
                            <input type="submit" value="modifier" class="btn_soums_modifier_projet">
                        </div>

                    </form>
                </div>
                <?php
                $rslt = $pdo->query("select * from projet natural join phase where Id_projet=$_GET[Id_projet]");
                $row = $rslt->fetch();
                $rslt1 = $pdo->query("select * from projet inner join discipline on projet.Id_discipline=discipline.Id_discipline where Id_projet=$_GET[Id_projet]");
                $row1 = $rslt1->fetch();
                $rslt2 = $pdo->query("select * from phase order by numero_phase");
                $row2 = $rslt2->fetchAll();
                $num = $row['numero_phase'];
                $numDisc = $row1['numero_discipline'];
                $outerIndex = 0;
                $totalOuterElements = count($row2);
                foreach ($row2 as $row2) {
                    $outerIndex++;
                    echo "
                            <div class='tab-pane fade' id='phase$row2[numero_phase]-tab-pane' role='tabpanel' aria-labelledby='phase$row2[numero_phase]-tab' tabindex='0'>
                            <h2>$row2[Titre_phase]</h2>
                                <div class='discipline-switches'>
                            ";
                    $rslt3 = $pdo->query("select * from discipline where Id_phase=$row2[Id_phase]");
                    $row3 = $rslt3->fetchAll();
                    $innerIndex = 0;
                    $totalInnerElements = count($row3);
                    foreach ($row3 as $row3) {
                        $innerIndex++;
                        if ($row3['numero_discipline'] == $numDisc && $row3['Id_phase'] == $row['Id_phase']) {
                            if ($outerIndex == $totalOuterElements && $innerIndex == $totalInnerElements) {
                                echo "
                                            <div class='form-check form-switch'>
                                                <input class='form-check-input' type='checkbox' id='flexSwitchCheckCheckedDisabled' checked disabled>
                                                <label class='form-check-label' for='flexSwitchCheckCheckedDisabled'>$row3[Titre_discipline]</label>
                                            </div>
                                        ";
                            } else {
                                echo "
                                            <div class='form-check form-switch'>
                                                <input class='form-check-input inptCheckDisc' data-id='$_GET[Id_projet]' data-discipline='$row3[Id_discipline]' data-phase='$row2[Id_phase]' type='checkbox' id='$row3[Titre_discipline]-switch'>
                                                <label class='form-check-label' for='$row3[Titre_discipline]-switch'>$row3[Titre_discipline]</label>
                                            </div>
                                        ";
                            }
                        } elseif ($row3['numero_discipline'] > $numDisc && $row3['Id_phase'] == $row['Id_phase']) {
                            echo "
                                    <div class='form-check form-switch'>
                                        <input class='form-check-input' type='checkbox' id='flexSwitchCheckDisabled' disabled>
                                        <label class='form-check-label' for='flexSwitchCheckDisabled'>$row3[Titre_discipline]</label>
                                    </div>
                                    
                                    ";
                        } else {
                            echo "
                                    <div class='form-check form-switch'>
                                        <input class='form-check-input' type='checkbox' id='flexSwitchCheckCheckedDisabled' checked disabled>
                                        <label class='form-check-label' for='flexSwitchCheckCheckedDisabled'>$row3[Titre_discipline]</label>
                                    </div>
                                ";
                        }
                    }
                    echo "</div></div>";
                }
                ?>
                <!-- Ajoutez les autres onglets de phase ici avec les commutateurs de discipline -->
            </div>

        </div>

    </main>
</div>




</div>
</div>

<?php
include("./inc/bas.inc.html");
?>