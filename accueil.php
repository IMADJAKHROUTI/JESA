<?php
    include("./inc/sidebar.php");
?>
    
    <div class="dash">
       
         <main class="dash">

         <div class="statis row">


         <?php
         // Se connecter à la base de données avec PDO

          if(admin()){
            // Récupérer l'Id de la dernière phase
            $sql_derniere_phase = "SELECT MAX(Id_phase) as derniere_phase FROM phase";
            $stmt_derniere_phase = $pdo->prepare($sql_derniere_phase);
            $stmt_derniere_phase->execute();
            $result_derniere_phase = $stmt_derniere_phase->fetch(PDO::FETCH_ASSOC);
            $derniere_phase_id = $result_derniere_phase['derniere_phase'];

            // Récupérer l'Id_discipline de la dernière discipline dans la dernière phase
            $sql_derniere_discipline = "SELECT Id_discipline FROM discipline WHERE Id_phase = :derniere_phase_id ORDER BY numero_discipline DESC LIMIT 1";
            $stmt_derniere_discipline = $pdo->prepare($sql_derniere_discipline);
            $stmt_derniere_discipline->bindParam(':derniere_phase_id', $derniere_phase_id);
            $stmt_derniere_discipline->execute();
            $result_derniere_discipline = $stmt_derniere_discipline->fetch(PDO::FETCH_ASSOC);
            $derniere_discipline_id = $result_derniere_discipline['Id_discipline'];

            // Récupérer le nombre de projets terminés
            $sql_nombre_projet_termine = "SELECT COUNT(*) as nombre_projet_termine FROM projet p JOIN discipline d ON p.Id_discipline = d.Id_discipline WHERE p.Id_phase = :derniere_phase_id AND p.Id_discipline = :derniere_discipline_id ";
            $stmt_nombre_projet_termine = $pdo->prepare($sql_nombre_projet_termine);
            $stmt_nombre_projet_termine->bindParam(':derniere_phase_id', $derniere_phase_id);
            $stmt_nombre_projet_termine->bindParam(':derniere_discipline_id', $derniere_discipline_id);
            $stmt_nombre_projet_termine->execute();
            $result_nombre_projet_termine = $stmt_nombre_projet_termine->fetch(PDO::FETCH_ASSOC);
            $nombre_projet_termine = $result_nombre_projet_termine['nombre_projet_termine'];


            // Récupérer le nombre de projets en cours
            $sql_nombre_projet_encours = "SELECT COUNT(*) as nombre_projet_encours FROM projet WHERE Id_discipline != :derniere_discipline_id ";
            $stmt_nombre_projet_encours = $pdo->prepare($sql_nombre_projet_encours);
            $stmt_nombre_projet_encours->bindParam(':derniere_discipline_id', $derniere_discipline_id);
            $stmt_nombre_projet_encours->execute();
            $result_nombre_projet_encours = $stmt_nombre_projet_encours->fetch(PDO::FETCH_ASSOC);
            $nombre_projet_encours = $result_nombre_projet_encours['nombre_projet_encours'];


            // Calculer le pourcentage de projets en cours et de projets terminés
            $total_projet = $nombre_projet_encours + $nombre_projet_termine;
            $pourcentage_projet_encours = round(($nombre_projet_encours / $total_projet) * 100);
            $pourcentage_projet_termine = round(($nombre_projet_termine / $total_projet) * 100);


          }
          else{

            $id_manager=$_SESSION['manager']['Id_manager'];

            $sql_derniere_phase = "SELECT MAX(Id_phase) as derniere_phase FROM phase";
            $stmt_derniere_phase = $pdo->prepare($sql_derniere_phase);
            $stmt_derniere_phase->execute();
            $result_derniere_phase = $stmt_derniere_phase->fetch(PDO::FETCH_ASSOC);
            $derniere_phase_id = $result_derniere_phase['derniere_phase'];

            // Récupérer l'Id_discipline de la dernière discipline dans la dernière phase
            $sql_derniere_discipline = "SELECT Id_discipline FROM discipline WHERE Id_phase = :derniere_phase_id ORDER BY numero_discipline DESC LIMIT 1";
            $stmt_derniere_discipline = $pdo->prepare($sql_derniere_discipline);
            $stmt_derniere_discipline->bindParam(':derniere_phase_id', $derniere_phase_id);
            $stmt_derniere_discipline->execute();
            $result_derniere_discipline = $stmt_derniere_discipline->fetch(PDO::FETCH_ASSOC);
            $derniere_discipline_id = $result_derniere_discipline['Id_discipline'];

            // Récupérer le nombre de projets terminés
            $sql_nombre_projet_termine = "SELECT COUNT(*) as nombre_projet_termine FROM projet p JOIN discipline d ON p.Id_discipline = d.Id_discipline join manager m on m.Id_manager=p.Id_manager WHERE p.Id_phase = :derniere_phase_id AND p.Id_discipline = :derniere_discipline_id and p.Id_manager=$id_manager";
            $stmt_nombre_projet_termine = $pdo->prepare($sql_nombre_projet_termine);
            $stmt_nombre_projet_termine->bindParam(':derniere_phase_id', $derniere_phase_id);
            $stmt_nombre_projet_termine->bindParam(':derniere_discipline_id', $derniere_discipline_id);
            $stmt_nombre_projet_termine->execute();
            $result_nombre_projet_termine = $stmt_nombre_projet_termine->fetch(PDO::FETCH_ASSOC);
            $nombre_projet_termine = $result_nombre_projet_termine['nombre_projet_termine'];


            // Récupérer le nombre de projets en cours
            $sql_nombre_projet_encours = "SELECT COUNT(*) as nombre_projet_encours FROM projet p join manager m on p.Id_manager=m.Id_manager WHERE Id_discipline != :derniere_discipline_id and p.Id_manager=$id_manager";
            $stmt_nombre_projet_encours = $pdo->prepare($sql_nombre_projet_encours);
            $stmt_nombre_projet_encours->bindParam(':derniere_discipline_id', $derniere_discipline_id);
            $stmt_nombre_projet_encours->execute();
            $result_nombre_projet_encours = $stmt_nombre_projet_encours->fetch(PDO::FETCH_ASSOC);
            $nombre_projet_encours = $result_nombre_projet_encours['nombre_projet_encours'];


            // Calculer le pourcentage de projets en cours et de projets terminés
            $total_projet = $nombre_projet_encours + $nombre_projet_termine;
            $pourcentage_projet_encours = round(($nombre_projet_encours / $total_projet) * 100);
            $pourcentage_projet_termine = round(($nombre_projet_termine / $total_projet) * 100);


          }

          // Nombre total de gestionnaires
          $stmt_nombre_manager_total = "SELECT COUNT(*) as nombre_manager_total FROM manager";
          $stmt_nombre_manager_total = $pdo->prepare($stmt_nombre_manager_total);
          $stmt_nombre_manager_total->execute();
          $result_nombre_manager_total = $stmt_nombre_manager_total->fetch(PDO::FETCH_ASSOC);
          $nombre_manager_total = $result_nombre_manager_total['nombre_manager_total'];

          // Nombre de gestionnaires avec au moins un projet
          $sql_nombre_manager = "SELECT COUNT(DISTINCT Id_manager) as nombre_manager FROM projet";
          $stmt_nombre_manager = $pdo->prepare($sql_nombre_manager);
          $stmt_nombre_manager->execute();
          $result_nombre_manager = $stmt_nombre_manager->fetch(PDO::FETCH_ASSOC);
          $nombre_manager = $result_nombre_manager['nombre_manager'];

          // Pourcentage de gestionnaires avec au moins un projet
          $pourcentage_nombre_manager = round(($nombre_manager / $nombre_manager_total) * 100);

         ?>


  <div class="col-sm-8 rec_statis">
    <h4>Projets en cours</h4><div class="progress-text"><?php echo $pourcentage_projet_encours; ?>%</div>
    <div class="rectangle encours">
      <div class="progress-bar" style="width: <?php echo $pourcentage_projet_encours; ?>%;"></div><br>
    </div>
    <p>Nombre de projets en cours : <?php echo $nombre_projet_encours; ?></p>
  </div>
  <div class="col-sm-8 rec_statis">
    <h4>Projets terminés</h4><div class="progress-text"><?php echo $pourcentage_projet_termine; ?>%</div>
    <div class="rectangle termine">
      <div class="progress-bar" style="width: <?php echo $pourcentage_projet_termine; ?>%;"></div> <br>
      
    </div>
    <p>Nombre de projets terminés : <?php echo $nombre_projet_termine; ?></p>
  </div>
  <div class="col-sm-8 rec_statis">
    <h4>Managers actifs</h4><div class="progress-text"><?php echo $pourcentage_nombre_manager; ?>%</div>
    <div class="rectangle termine">
      <div class="progress-bar" style="width: <?php echo $pourcentage_nombre_manager; ?>%;"></div><br>
    </div>
    <p>Nombre de manager : <?php echo $nombre_manager; ?></p>
  </div>
  <div class="col-sm-8 rec_statis">
    <h4>Total managers</h4> <div class="progress-text"></div>
    <div class="rectangle termine">
      <div class="progress-bar" style="width:100%"></div><br>
    </div>
    <p>Nombre total des manager  : <?php echo $nombre_manager_total; ?></p>
  </div>
  


          
         </div>

         <div class="graph row">
            <div class="graph_left col-md-8">
              <canvas id="myChart"></canvas>
            </div>
            <div class="graph_right col-md-4">
  <canvas id="projectPerMonthChart"></canvas>
</div>

<?php
// Connect to the database
// Retrieve the number of projects created per month
if(admin()){
  $stmt = $pdo->query("SELECT DATE_FORMAT(Date_projet, '%Y-%m') AS month, COUNT(*) AS count FROM projet GROUP BY month ORDER BY month ASC");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

}
else{
  $stmt = $pdo->query("SELECT DATE_FORMAT(Date_projet, '%Y-%m') AS month, COUNT(*) AS count FROM projet p join manager m on p.Id_manager=m.Id_manager where  p.Id_manager=$id_manager GROUP BY month ORDER BY month ASC");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

}
// Create the data arrays for the chart
$labels = array();
$values = array();
foreach ($data as $row) {
  $labels[] = $row['month'];
  $values[] = $row['count'];
}
$data = array('labels' => $labels, 'values' => $values);
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-piechart-datalabels"></script>

<script>
  var ctx = document.getElementById('projectPerMonthChart').getContext('2d');
  var data = {
    labels: <?php echo json_encode($data['labels']); ?>,
    datasets: [{
      label: 'Nombre de projets créés',
      data: <?php echo json_encode($data['values']); ?>,
      backgroundColor: ['#48e3ff', '#0bffdb', '#3240ff', '#3c91ff', '#989fff', '#ff00bf', '#00ffff', '#ff8000', '#0080ff', '#ff0080', '#00ff80', '#ffbf80'],
      borderWidth: 0,
      borderRadius: {
        topLeft: 10,
        topRight: 10,
        bottomLeft: 0,
        bottomRight: 0
      }
    }]
  };

  var options = {
    plugins: {
      legend: {
        display: true,
        position: 'bottom',
        labels: {
          color: 'black'
        }
      },
      title: {
        display: true,
        text: 'nombre de projets crée par moi',
        font: {
          family: 'Kumbh Sans',
          size: 18,
          weight: 400
        },
        padding: {
          top: 10,
          bottom: 40
        },
        color: 'black',
        align: 'center'
      },
      datalabels: {
        formatter: function(value, context) {
          var label = context.chart.data.labels[context.dataIndex];
          var projects = context.chart.data.datasets[0].data[context.dataIndex];
          return projects + ' projet' + (projects !== 1 ? 's' : '') + ' (' + label + ')';
        },
        color: 'white',
        font: {
          family: 'Kumbh Sans',
          size: 14,
          weight: 400
        },
        anchor: 'end',
        align: 'start',
        offset: 10,
        padding: 6,
        borderRadius: 4,
        backgroundColor: 'rgba(0, 0, 0, 0.7)'
      }
    },
    cutoutPercentage: 50
  };

  var myChart = new Chart(ctx, {
    type: 'doughnut',
    data: data,
    options: options
  });

  // Add the CSS class to set the background to white
  ctx.canvas.classList.add("white-background");
  // Add margin to the title
  var chartTitle = document.querySelector('.chartjs-title');
  chartTitle.parentNode.style.paddingLeft = '40px';
</script>  
          
              
  
         </div>
            
         <?php
            // Connexion à la base de données
            // Récupération des données
            if(admin()){
              $stmt = $pdo->query("SELECT phase.Titre_phase, COUNT(projet.Id_projet) AS nombre_projet FROM phase LEFT JOIN projet ON phase.Id_phase = projet.Id_phase GROUP BY phase.Id_phase");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            }
            else{
              $stmt = $pdo->query("SELECT phase.Titre_phase, COUNT(projet.Id_projet) AS nombre_projet FROM phase LEFT JOIN projet  ON phase.Id_phase = projet.Id_phase join manager on projet.Id_manager=manager.Id_manager where  projet.Id_manager=$id_manager GROUP BY phase.Id_phase");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            }
            // Création du tableau de données
            $labels = array();
            $values = array();
            foreach ($data as $row) {
              $labels[] = $row['Titre_phase'];
              $values[] = $row['nombre_projet'];
            }
            $data = array('labels' => $labels, 'values' => $values);

            // Affichage du graphique en barres
        ?>

  
        <script>
            var ctx = document.getElementById('myChart').getContext('2d');
            var data = {
              labels: <?php echo json_encode($data['labels']); ?>,
              datasets: [{
                label: 'Nombre de projets',
                data: <?php echo json_encode($data['values']); ?>,
                backgroundColor: '#3240ff',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 0,
                borderRadius: {
                  topLeft: 10,
                  topRight: 10,
                  bottomLeft: 0,
                  bottomRight: 0
                }
              }]
            };

            var options = {
              scales: {
                x: {
                  ticks: {
                    display: true,
                    color: 'black'
                  },
                  grid: {
                    display: false
                  }
                },
                y: {
                  ticks: {
                    display: true,
                    color: 'black'
                  },
                  grid: {
                    display: false
                  }
                }
              },
              plugins: {
                legend: {
                  display: true,
                  position: 'bottom',
                  labels: {
                    color: 'black'
                  }
                },
                title: {
                  display: true,
                  text: 'Nombre de projets par phase',

                  font: {
                    family: 'Kumbh Sans',
                    size: 18,
                    weight: 400
                  },
                  padding: {
                    top: 10,
                    bottom: 40
                  },
                  color: 'black',
                  align: 'start'
                }
              },
              barPercentage: 0.5
            };

            var myChart = new Chart(ctx, {
              type: 'bar',
              data: data,
              options: options
            });
            
            // Ajout de la classe CSS sur le canvas pour définir le fond blanc
            ctx.canvas.classList.add("white-background");
                        // Ajout de la marge au titre
                        var chartTitle = document.querySelector('.chartjs-title');
            chartTitle.parentNode.style.paddingLeft = '40px'; // ajustez cette valeur en fonction de vos besoins
        </script>

         </main>
    </div>

<?php 
include("./inc/bas.inc.html");
?>