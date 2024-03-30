function validation() {
    var nom = document.getElementById("nom").value;
    var email = document.getElementById("email").value;
    var mdps = document.getElementById("mdps").value;

    var action = document.getElementById("form_action").value;
    
    label_erreur_mdps.textContent = "";
    label_erreur_email.textContent = "";
    label_erreur_nom.textContent = "";
    var aide = true;
        if (nom == "") {
            label_erreur_nom.textContent = "Veuillez saisir un nom.";
            aide = false;
        }
        if (email == "") {
            label_erreur_email.textContent = "Veuillez saisir une adresse e-mail.";
            aide = false;
        } 
        if (mdps == "") {
            label_erreur_mdps.textContent = "Veuillez saisir un mot de passe.";
            aide = false;
        } 
    var id_manager = document.getElementById("id").value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'controller.php',false);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200 &&  xhr.responseText.trim() === "1") {
            label_erreur_email.textContent = "L'adresse e-mail saisie existe déjà.";
            aide = false;
        } 
    };
    if (action=='ajouter_manager') {
        xhr.send("Email_exist_ajout=" + email);
    }
    if (action=='modifier_manager') {
        xhr.send("Email_exist_modif=" + email + "&Id_manager=" + id_manager);
    }
  
    return aide;
}

function valide_connexion(){
    var email_con=document.getElementById("email_con").value;
    var mdps_con=document.getElementById("mdps_con").value;
    var label_err_email_con = document.getElementById("label_err_email_con");
    var label_err_mdps_con=document.getElementById("label_err_mdps_con");
    var aide=true;
    
    label_err_email_con.textContent="";
    label_err_mdps_con.textContent="";

    if(email_con==""){
        aide=false;
        label_err_email_con.textContent="Veuillez saisir votre adresse e-mail.";
    }
    if(mdps_con==""){
        aide=false;
        label_err_mdps_con.textContent="Veuillez saisir votre mot de passe.";
    }
    return aide;
}




function valider_profil(){
    var mdps_p=document.querySelector("#mdps_p").value;
    var mdps_p_c=document.querySelector("#mdps_p_c").value;
    var aide=true;
    if(mdps_p!=mdps_p_c){
        label_err_confirmer.textContent ="les mots de passe ne sont pas identiques";
        aide=false;
    }

    return aide;
}


function ajouterDiscipline() {
    var disciplinesDiv = document.getElementById('disciplines');
  
    // Récupérer le nombre de disciplines existantes
    var numDisciplines = disciplinesDiv.querySelectorAll('.input_label').length;
  
    // Calculer le numéro de la nouvelle discipline
    var numNouvelleDiscipline = numDisciplines + 1;
  
    // Créer un div avec la classe "input_label"
    var inputLabelDiv = document.createElement('div');
    inputLabelDiv.classList.add('input_label', 'mb-md-3');
  
    // Ajouter le label dans le div "input_label"
    var disciplineLabel = document.createElement('label');
    disciplineLabel.innerHTML = 'Discipline ' + numNouvelleDiscipline + ': ';
    disciplineLabel.classList.add('col-md-2', 'col-sm-12');
    disciplineLabel.setAttribute('for', 'discipline' + numNouvelleDiscipline);
    inputLabelDiv.appendChild(disciplineLabel);
  
    // Ajouter l'input dans le div "input_label"
    var disciplineInput = document.createElement('input');
    disciplineInput.type = 'text';
    disciplineInput.id = 'discipline' + numNouvelleDiscipline;
    disciplineInput.name = 'titre_discipline[]';
    disciplineInput.classList.add('col-md-5', 'col-sm-12');
    inputLabelDiv.appendChild(disciplineInput);
  
    // Ajouter le div "input_label" dans le div "disciplinesDiv"
    disciplinesDiv.appendChild(inputLabelDiv);
  }


  function confirmSupp(table) {
    switch (table) {
        case "projet":
            vText = "Voulez-vous vraiment supprimer cette mission définitivement ?";
            url = "./controller.php?action=supprimer_manager";
            break;
        case "compte":
            vText = "Voulez-vous vraiment supprimer ce collaborateur définitivement ?";
            url = "./controller.php?action=supprimer_manager";
            break;
        case "phase":
            vText = "Voulez-vous vraiment supprimer ce groupe définitivement ?";
            url = "./controller.php?supprimer_phase";
            break;
        case "discipline":
            vText = "Voulez-vous vraiment supprimer ce frais définitivement ?";
            url = "./controller.php?supprimer_discipline";
            break;
    }
    swal({
            title: "",
            text: vText,
            icon: "warning",
            buttons: [
                "Annuler",
                "Supprimer",
            ],
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                document.location.href = url;
            }
        });
}

function success(success) {
    swal({
        title: '',
        text: success,
        icon: 'success',
        button: false,
        timer: 3000,
    });
}

function erreur(erreur) {
    swal({
        title: '',
        text: erreur,
        icon: 'warning',
        button: false,
        timer: 3000,
    });
}