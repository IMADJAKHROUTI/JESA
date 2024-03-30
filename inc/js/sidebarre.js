$('.btn_modif_discipline').click(function() {
    var id_discipline = $(this).attr('data-id-discipline');
    $.ajax({
        url: './controller.php',
        type: 'GET',
        data: { id_discipline_recu: id_discipline },
        dataType: 'JSON',
        success: function(response) {
            $('.form_discipline').find('[name="action"]').val('modifier_discipline');
            $('.form_discipline').find('[name="id_discipline"]').val(response.Id_discipline);
            $('.form_discipline').find('[name="titre_discipline"]').val(response.Titre_discipline);
            $('.form_discipline').find('[name="numero_discipline"]').val(response.numero_discipline);
            $('.form_discipline').find('[name="ancien_numero"]').val(response.numero_discipline)
            // $('.form_discipline').find('#btn_submit').val('Modifier');
            $('.tr_form_discipline').removeClass('cacher');
            // $('#tbody_table_phase').addClass('cacher');
        },
        error: function(xhr, status, error) {
            console.log("Erreur AJAX : " + status + " " + error);
            console.log(xhr.responseText);
        }
    });
});

$('.reset_discipline').click(function() {
    $('.form_discipline')[0].reset();
    $('.tr_form_discipline').addClass('cacher');
});



var btn_ajouter_discipline = document.querySelectorAll(".btn_ajouter_discipline");
var tr_form_discipline = document.querySelectorAll(".tr_form_discipline");
var form_discipline = document.querySelectorAll(".form_discipline");

if (btn_ajouter_discipline.length > 0) {
    btn_ajouter_discipline.forEach((btn, index) => {
        btn.addEventListener("click", () => {
            // Vider les champs du formulaire
            form_discipline[index].reset();

            // Modifier la valeur de l'attribut "action" du formulaire pour ajouter une discipline
            form_discipline[index].querySelector('[name="action"]').value = "ajouter_discipline";

            tr_form_discipline[index].classList.remove("cacher");
        });
    });
}

var btn_ajouter_phase = document.querySelector(".btn_ajouter_phase");
var a_ajouter_phase = document.querySelector("#ajouter_phase_tab");
var form_phase = document.querySelector("#form_phase");
var disciplines=document.querySelector("#disciplines");
var formPhase = document.getElementById('form_phase');

if(btn_ajouter_phase){
    
btn_ajouter_phase.addEventListener("click", function() {
    a_ajouter_phase.click();
    form_phase.reset();
    if (formPhase.querySelector('input[name="action"]')) {
        formPhase.querySelector('input[name="action"]').value = 'ajouter_phase';
      }
      if (formPhase.querySelector('input[type="submit"]')) {
        formPhase.querySelector('input[type="submit"]').value = 'Ajouter';
      }
    disciplines.innerHTML='';
  });
  
}



// Récupérer le bouton "Modifier" de la phase sélectionnée
var btnsModifPhase = document.querySelectorAll('.btn_modif_phase');

// Ajouter un écouteur d'événement sur chaque bouton "modifier"
btnsModifPhase.forEach(function(btnModifPhase) {
  btnModifPhase.addEventListener('click', function() {
    // Récupérer l'ID de la phase sélectionnée
    var idPhase = this.getAttribute('data-id-phase');
    // ...
    // Envoyer une requête AJAX pour récupérer les données de la phase
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
            // console.log(xhr.responseText);
          // Si la requête est réussie, afficher le formulaire avec les données de la phase
          var formPhase = document.getElementById('form_phase');
          var phaseData = JSON.parse(xhr.responseText);
          if (formPhase.querySelector('input[type="submit"]')) {
            formPhase.querySelector('input[type="submit"]').value = 'Modifier';
          }
          if (formPhase.querySelector('input[name="action"]')) {
            formPhase.querySelector('input[name="action"]').value = 'modifier_phase';
          }
          if (formPhase.querySelector('input[name="id_phase"]')) {
            formPhase.querySelector('input[name="id_phase"]').value = phaseData.Id_phase;
          }
          if (formPhase.querySelector('input[name="titre_phase"]')) {
            formPhase.querySelector('input[name="titre_phase"]').value = phaseData.Titre_phase;
          }
          if (formPhase.querySelector('input[name="numero_phase"]')) {
            formPhase.querySelector('input[name="numero_phase"]').value = phaseData.numero_phase;
          }
          // Remplir la liste des disciplines avec les données de la phase
          var disciplinesDiv = document.getElementById('disciplines');
          disciplinesDiv.innerHTML = '';
            var idPhaseInput = document.createElement('input');
            idPhaseInput.type = 'hidden';
            idPhaseInput.name = 'id_phase';
            idPhaseInput.value = idPhase;
            formPhase.appendChild(idPhaseInput);
            var ancien_input = document.createElement('input');
            ancien_input.type = 'hidden';
            ancien_input.name = 'ancien_numero';
            ancien_input.value = phaseData.numero_phase;
            formPhase.appendChild(ancien_input);

            

          for (var i = 0; i < phaseData.disciplines.length; i++) {
            var discipline = phaseData.disciplines[i];
            var inputLabelDiv = document.createElement('div');
            inputLabelDiv.classList.add('input_label', 'mb-md-3');
            var disciplineLabel = document.createElement('label');
            disciplineLabel.innerHTML = 'Discipline ' + (i+1) + ': ';
            disciplineLabel.classList.add('col-md-2', 'col-sm-12');
            disciplineLabel.setAttribute('for', 'discipline' + (i+1));
            inputLabelDiv.appendChild(disciplineLabel);
            var disciplineInput = document.createElement('input');
            disciplineInput.type = 'text';
            disciplineInput.id = 'discipline' + (i+1);
            disciplineInput.name = 'titre_discipline[]';
            disciplineInput.value = discipline.Titre_discipline;
            disciplineInput.classList.add('col-md-5', 'col-sm-12');
            inputLabelDiv.appendChild(disciplineInput);
            // Ajouter un champ caché pour l'ID de la discipline
            var disciplineIdInput = document.createElement('input');
            disciplineIdInput.type = 'hidden';
            disciplineIdInput.name = 'id_discipline[]';
            disciplineIdInput.value = discipline.Id_discipline;
            inputLabelDiv.appendChild(disciplineIdInput);
            disciplinesDiv.appendChild(inputLabelDiv);
        }
          // Afficher le formulaire
          a_ajouter_phase.click();
        } else {
          // Si la requête échoue, afficher une erreur
          alert('Une erreur est survenue lors de la récupération des données de la phase.');
        }
      }
    };
    xhr.open('POST', 'controller.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('recu_form_modif_phase'+'&id=' + idPhase);
  });
});



$(document).ready(function() {
    $('.inptCheckDisc').change(function() {
        var Id_discipline = $(this).data('discipline');
        var Id_phase = $(this).data('phase');
        var Id_projet = $(this).data('id');
        $.ajax({
            url: './controller.php',
            type: 'POST',
            data: { Id_projet: Id_projet, Id_discipline: Id_discipline, Id_phase: Id_phase, updateDiscipline: true },
            dataType: 'json',
            success: function(response) {
                location.reload(true);
            },
            error: function() {
                alert('Une erreur s\'est produite');
            }
        });
    });
});
$(document).ready(function() {
    var table = $('#mytab').DataTable({
        searching: true,
        paging: true,
        pageLength: 7,
        responsive: true,
        language: {
            search: "",
            lengthMenu: "afficher _MENU_ ",
            zeroRecords: "Aucun résultat",
            info: "<span class='gras'> _START_ </span>&nbsp; à<span class='gras'>&nbsp; _END_</span>&nbsp; sur <span class='gras'>&nbsp; _TOTAL_ </span>&nbsp;",
            infoEmpty: "Aucune donnée disponible",
            infoFiltered: "",
            recordsTotal: "Nombre total d'entrées : _TOTAL_",
            recordsFiltered: "Nombre total d'entrées correspondantes : _MAX_",
            paginate: {
                previous: '<i class="fa-solid fa-chevron-left"></i>',
                next: '<i class="fa-solid fa-chevron-right"></i>'
            }
        }
    });
    
    $('#mytab').removeAttr('style');
    $('#mytab_filter label').prepend('<i class="fa-solid fa-magnifying-glass"></i>');
    $('#mytab_filter label input').attr('placeholder', 'Search for a project...');
    $('#mytab_wrapper').prepend('<div class="ajouter_projet"><button class="btn_ajt_prjt"  data-bs-toggle="modal" data-bs-target="#myModal">Ajouter projet</button></div>');
    
    $('#recherche-nom, #recherche-code, #recherche-phase, #recherche-discipline').on('input', function() {
        var nom = $('#recherche-nom').val();
        var code = $('#recherche-code').val();
        var phase = $('#recherche-phase').val();
        var discipline = $('#recherche-discipline').val();
        
        table.search('').columns().search('').draw();
        
        if (nom) {
            table.columns(2).search(nom).draw();
        }
        if (code) {
            table.columns(1).search(code).draw();
        }
        if (phase) {
            table.columns(4).search(phase).draw();
        }
        if (discipline) {
            table.columns(5).search(discipline).draw();
        }
    });
});

var btn_ajouter_phase = document.querySelector(".btn_ajouter_phase");
var a_ajouter_phase = document.querySelector("#ajouter_phase_tab");

if(btn_ajouter_phase){
    
btn_ajouter_phase.addEventListener("click", function() {
    a_ajouter_phase.click();
  });
  
}
// $(document).ready(function() {
//     $('.btn_ajouter_phase').click(function() {
//         $.ajax({
//             url: 'controller.php',
//             type: 'GET',
//             data: { recu_form_phase: 1 },
//             success: function(data) {
//                 $('#tab_disciplineContent').html(data);
//                 $('#form_ajout_phase').submit(function(event) {
//                     event.preventDefault(); // empêche le comportement par défaut du formulaire (rechargement de la page)
//                     $.ajax({
//                         url: 'controller.php', // chemin vers le script PHP qui traite la requête
//                         type: 'POST',
//                         data: $(this).serialize() + '&action=ajouter_phase&jeton=' + $('#jeton').val(),
//                         success: function() {
//                             location.reload(); // recharge la page pour afficher la nouvelle phase ajoutée
//                         }
//                     });
//                 });
//                 $('#btn_annuler').click(function() {
//                     $('#form_ajout_phase')[0].reset(); // efface les champs du formulaire
//                     $('#form_ajout_phase').hide(); // masque le formulaire
//                 });
//             }
//         });
//     });
// });



var label_erreur_email = document.getElementById("label_erreur_email");
var label_erreur_nom = document.getElementById("label_erreur_nom");
var label_erreur_mdps = document.getElementById("label_erreur_mdps");



$(document).ready(function() {
    $('#mytab_compte').DataTable({
        searching: true,
        paging: true,
        pageLength: 4,
        responsive: true,
        language: {
            search: "",
            lengthMenu: "afficher _MENU_ ",
            zeroRecords: "Aucun résultat",
            info: "<span class='gras'> _START_ </span>&nbsp; à<span class='gras'>&nbsp; _END_</span>&nbsp; sur <span class='gras'>&nbsp; _TOTAL_ </span>&nbsp;",
            infoEmpty: "Aucune donnée disponible",
            infoFiltered: "",
            recordsTotal: "Nombre total d'entrées : _TOTAL_",
            recordsFiltered: "Nombre total d'entrées correspondantes : _MAX_",
            paginate: {
                previous: '<i class="fa-solid fa-chevron-left"></i>',
                next: '<i class="fa-solid fa-chevron-right"></i>'
            }
        }
    });
    $('#mytab_compte_filter label').prepend('<i class="fa-solid fa-magnifying-glass"></i>');
    $('#mytab_compte_filter label input').attr('placeholder', 'Search for a project...');
    $('#mytab_compte_wrapper').prepend('<div class="ajouter_manager"><button type="button" class="btn btn-primary btn_ajt_mngr" data-bs-toggle="modal" data-bs-target="#exampleModal"  >Ajouter manager</button></div>');
    $('.btn_ajt_mngr').on('click', function() {
        $('#form_manager').trigger('reset');
        $('#form_manager').find('#btn_submit').val('Ajouter');
        $('#form_manager').find('#form_action').val('ajouter_manager');
        label_erreur_mdps.textContent = "";
        label_erreur_email.textContent = "";
        label_erreur_nom.textContent = "";
    });
});





// // Vérifier si la phase actuelle est stockée dans le local storage
// if(localStorage.getItem('currentPhase')){
//     // Récupérer la phase actuelle depuis le local storage
//        var currentPhase = localStorage.getItem('currentPhase');
//        // Supprimer la classe 'active' de l'onglet par défaut
//        if (currentPhase != '#details') {
//             $('#details-tab-pane').removeClass('active show');
//             $('#details-tab').removeClass('active');
//         }
        
//        // Activer l'onglet de la phase actuelle
//        $('#phase' + currentPhase + '-tab').addClass('active');
//        // Afficher le contenu de la phase actuelle
//        $('#phase' + currentPhase + '-tab-pane').addClass('show active');
// }

// // Enregistrerla phase actuelle dans le local storage lorsque l'utilisateur change de phase
// $('.nav-link').on('click', function(){
//     // Récupérer l'identifiant de la phase actuelle
//     var currentPhase = $(this).attr('data-bs-target').replace('#phase', '').replace('-tab-pane', '');
//     // Supprimer la phase précédemment stockée dans le local storage et enregistrer la nouvelle phase
//     var previousPhase = localStorage.getItem('currentPhase');
//     if(previousPhase){
//         localStorage.removeItem('phase' + previousPhase);
//     }
//     localStorage.setItem('currentPhase', currentPhase);
//     localStorage.setItem('phase' + currentPhase, true);
    
//     // Supprimer la classe 'active' de l'onglet par défaut
//     if ($(this).attr('data-bs-target') !== '#details-tab_pane') {
//         $('#details-tab-pane').removeClass('show active');
//         $('#details-tab').removeClass('active');
//     }
//     // Mettre à jour les classes 'active' des onglets de phase
//     $('.nav-link, .tab-pane').removeClass('show active');
//     $(this).addClass('active');
//     $($(this).attr('data-bs-target')).addClass('show active');
// });













// Vérifier si la phase actuelle est stockée dans le local storage
if(localStorage.getItem('phase_currentPhasee')){
    // Récupérer la phase actuelle depuis le local storage
       var currentPhase = localStorage.getItem('phase_currentPhasee');
       // Supprimer la classe 'active' de l'onglet par défaut
       if (currentPhase != '#details') {
            $('#details-tab-pane').removeClass('active show');
            $('#details-tab').removeClass('active');
        }
        
       // Activer l'onglet de la phase actuelle
       $('#phase' + currentPhase + '-tab').addClass('active');
       // Afficher le contenu de la phase actuelle
       $('#phase' + currentPhase + '-tab-pane').addClass('show active');
}

// Enregistrerla phase actuelle dans le local storage lorsque l'utilisateur change de phase
$('.bt_phase').on('click', function(){
    // Récupérer l'identifiant de la phase actuelle
    var currentPhase = $(this).attr('data-bs-target').replace('#phase', '').replace('-tab-pane', '');
    // Supprimer la phase précédemment stockée dans le local storage et enregistrer la nouvelle phase
    var previousPhase = localStorage.getItem('phase_currentPhasee');
    if(previousPhase){
        localStorage.removeItem('phase_' + previousPhase);
    }
    localStorage.setItem('phase_currentPhasee', currentPhase);
    localStorage.setItem('phase_' + currentPhase, true);
    
    // Supprimer la classe 'active' de l'onglet par défaut
    if ($(this).attr('data-bs-target') !== '#details-tab_pane') {
        $('#details-tab-pane').removeClass('show active');
        $('#details-tab').removeClass('active');
    }
    // Mettre à jour les classes 'active' des onglets de phase
    $('.bt_phase, .tab-pane').removeClass('show active');
    $(this).addClass('active');
    $($(this).attr('data-bs-target')).addClass('show active');
});







// Vérifier si la phase actuelle est stockée dans le local storage
if(localStorage.getItem('phase_currentPhase')){
    // Récupérer la phase actuelle depuis le local storage
    var currentPhase = localStorage.getItem('phase_currentPhase');
    // Activer l'onglet de la phase actuelle
    $('#phasee' + currentPhase + '-tab').addClass('active');
    // Afficher le contenu de la phase actuelle
    $('#phasee' + currentPhase + '-tab-pane').addClass('show active');
}




// Enregistrer la phase actuelle dans le local storage lorsque l'utilisateur change de phase
$('.btn_phase').on('click', function(){
    // Récupérer l'identifiant de la phase actuelle
    var currentPhase = $(this).attr('data-bs-target').replace('#phasee', '').replace('-tab-pane', '');
    // Supprimer la phase précédemment stockée dans le local storage et enregistrer la nouvelle phase
    var previousPhase = localStorage.getItem('phase_currentPhase');
    if(previousPhase){
        localStorage.removeItem('phase_' + previousPhase);
    }
    localStorage.setItem('phase_currentPhase', currentPhase);
    localStorage.setItem('phase_' + currentPhase, true);

    // Mettre à jour les classes 'active' des onglets de phase
    $('.btn_phase, .tab-pane').removeClass('show active');
    $(this).addClass('active');
    $($(this).attr('data-bs-target')).addClass('show active');
});


$('.btn_ajouter_phase').on('click', function(){
    // Supprimer la phase précédemment stockée dans le local storage et enregistrer la nouvelle phase
    var previousPhase = localStorage.getItem('ajout_phase_currentPhase');
    if(previousPhase){
        localStorage.removeItem('ajout_phase_' + previousPhase);
    }
    localStorage.setItem('ajout_phase_currentPhase', '#ajouter_phase_tabpane');
    localStorage.setItem('ajout_phase_#ajouter_phase_tabpane', true);
    
    // Supprimer la classe 'active' de l'onglet par défaut
    $('.btn_phase, .tab-pane').removeClass('show active');
    $('#ajouter_phase_tab').addClass('active');
    $('#ajouter_phase_tabpane').addClass('show active');
});












$(document).ready(function() {
    // Click event handler for the "view-projet" button
    $('.btn_voir_prjt').click(function() {
      var projetId = $(this).closest('tr').find('td:first').text();
  
      // Make an AJAX call to retrieve the project information
      $.ajax({
        url: 'controller.php',
        type: 'POST',
        data: {id_projet_voir: projetId},
        success: function(response) {
          // Display the project information in the modal
          $('#projetModal .modal-body').html(response);
  
          // Show the modal
          $('#projetModal').modal('show');
        },
        error: function() {
          alert('Error retrieving project information.');
        }
      });
    });
  });


$('.btn_modif_mngr').click(function() {
    var id_manager = $(this).closest('tr').find('.id').text();

    $.ajax({
        url: './controller.php',
        type: 'GET',
        data: { id_recu: id_manager },
        dataType: 'JSON',
        success: function(responce) {

            $('#id').val(responce.Id_manager);
            $('#nom').val(responce.Nom_manager);
            $('#email').val(responce.Email_manager);
            $('#mdps').val(responce.Mdps_manager);
            $('#form_manager').find('#btn_submit').val('Modifier');
            $('#form_manager').find('#form_action').val('modifier_manager');
            label_erreur_mdps.textContent = "";
            label_erreur_email.textContent = "";
            label_erreur_nom.textContent = "";
        },
        error: function(xhr, status, error) {
            console.log("Erreur AJAX : " + status + " " + error);
            console.log(xhr.responseText);
        }
    });
});




let iconEye = document.querySelectorAll('.fa-solid.fa-eye.mdps');
let inptMdps = document.querySelectorAll('.inpt_mdps');

iconEye.forEach(icon => {
    icon.addEventListener('click', function() {
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
        inptMdps.forEach(input => {
            input.type = (input.type === 'password') ? 'text' : 'password';
        });
    });
});




var changer = document.querySelector(".changer");
var  body = document.querySelector("#body");
var  sidebar = document.querySelector(".sidebar");
var  header = document.querySelector(".fix");
changer.addEventListener("click", () => {
    changer.classList.toggle("fa-xmark");
    body.classList.toggle("body");
    sidebar.classList.toggle("voir");
    header.classList.toggle("body");
});


var inputs_profile = document.querySelectorAll(".div_input input");
var modifier_p = document.querySelector(".modifier_p");
var anuller_p = document.querySelector(".anuller_p");
var soum = document.querySelector(".soum");
var enregistrer_p = document.querySelector(".enregistrer_p");
var form_p = document.querySelector("#form_p");
var label_err_confirmer=  document.getElementById("label_err_confirmer") ;
var confirmer = document.querySelector(".confirmer");
if(modifier_p){
    modifier_p.addEventListener("click", () => {
        soum.style.display="block";
        modifier_p.style.display='none';
        confirmer.style.display='block';
        inputs_profile.forEach((input) => {
            input.classList.add("not_readonly");
            input.removeAttribute("readonly");
            
        });
    });
}

if(anuller_p){
anuller_p.addEventListener("click", () => {
    soum.style.display="none";
    modifier_p.style.display='block';
    confirmer.style.display='none';
    label_err_confirmer.textContent="";
    inputs_profile.forEach((input) => {
        input.classList.remove("not_readonly");
        input.setAttribute("readonly",true);
    });
});

}



