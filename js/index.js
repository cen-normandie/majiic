
function toTitleCase(str){return str.replace(/\w\S*/g,function(txt){return txt.charAt(0).toUpperCase()+txt.substr(1).toLowerCase()})}

function check_mail(mail_v){
    var regex=/^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
    if(!regex.test(mail_v)){
        if (mail_v != "") {
            alert('format du courriel non valide (xxx@xxx.xx)');
            $('#courriel').val('');
            $("#inscription_mail").val('');
            //$("courriel").addClass("error_field");
            //$("#inscription_mail").addClass("error_field");
            return false
            }
        if (mail_v == "") {
            alert('courriel vide');
            $('#courriel').val('');
            $("#inscription_mail").val('');
            return false
            }
        }
    else{
        //$("courriel").removeClass("error_field");
        //$("#inscription_mail").removeClass("error_field");
        return true
        }
    }

function check_pwd(pwd_v){
    var regex=/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&_])[A-Za-z\d@$!#?&_]{8,}$/;
    if(!regex.test(pwd_v)){
        if (pwd_v != "") {
            //$('#pwd').val('');
            alert('format du mot de passe non valide :\n- 8 caractères\n- 1 caractère spécial\n- 1 numéro');
            $("pwd").val('');
            $("#inscription_pwd").val('');
            return false
            }
        if (pwd_v == "") {
            alert('mot de passe vide');
            return false
            }
        }
    else{
        //$("pwd").removeClass("error_field");
        //$("#inscription_pwd").removeClass("error_field");
        return true
        }
    };

$('#inscription_nom').on('input',function(e){
    $('#report_nom').text(toTitleCase($('#inscription_nom').val()));
});
$('#inscription_prenom').on('input',function(e){
    $('#report_prenom').text(toTitleCase($('#inscription_prenom').val()));
});

function check_nom(nom_v){
    if (nom_v == "") {
        alert('Nom vide');
        return false
        }
    else{
        return true
        }
    };

function check_prenom(prenom_v){
    if (prenom_v == "") {
        alert('Prenom vide');
        return false
        }
    else{
        return true
        }
    };

$("#signin").click( function () {
    {
        $.ajax({
            url      : "php/login.php",
            type     : "POST",
            data     : {email: $("#courriel").val(), password:$("#pwd").val()},
            async    : false,
            dataType : "text",
            error    : function(request, error) { console.log("not ajax success ");},
            success  : function(data) {
                if (data == "Success")
                {   
                    console.log("Error1");
                    $.ajax({
                        url      : "php/ajax/logs.js.php",
                        type     : "POST",
                        data     : {email: $("#courriel").val(), },
                        async    : false,
                        dataType : "text",
                        error    : function(request, error) { console.log("not ajax success ");},
                        success  : function(data) {
                            console.log("success");
                            window.location.href = 'analytique.php';
                        }
                    });// End ajax
                } else if (data == "CGU") {
                    alert("Vous n'avez pas accepter les CGU --> Contactez votre service SIG");
                }
                else /*(data == "Failed")*/
                {
                    alert('Connexion impossible... Vérifiez votre identifiant et votre mot de passe');
                }
            }
        });// End ajax
    }
});

$("#save_update_pwd_mail").on('click',function(e){
    var mail = $("#update_pwd_mail").val();
    var mailOk = check_mail(mail);
    if (mailOk) {
        $.ajax({
        type : 'POST',
        crossDomain: true,
        url: "/php/sent_mail_update_pwd.php",
        async    : false,
        data     : {courriel : mail},
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        dataType : "text",
        success: function(data) {
                    if (data=="send") {
                        alert("Un mail vient d'être envoyé à "+mail+",\n Vous pouvez dès à présent vous connecter à l'application");
                        $('#ModalDelete').modal('hide');
                    } else {
                        alert("La réinitialisation du mot de passe à échouée :( \n l'utilisateur·rice n'existe pas.");
                    }
                }
        });
    } else {
        console.log("error");
    }

});

$("#save_create_account").on('click',function(e){
    var mail = $("#inscription_mail").val();
    var pwd  = $("#inscription_pwd").val();
    var nom  = $("#inscription_nom").val();
    var prenom  = $("#inscription_prenom").val();
    var cgu  = $('#cgu_c').is(':checked');
    var cgu_content = $('#cgu_content').text();
    var c_c  = false;
    
    
    var mailOk = check_mail(mail);
    var passwordOk = check_pwd(pwd);
    var nomOk = check_nom(nom);
    var prenomOk = check_prenom(prenom);
    
    if (passwordOk && mailOk && nomOk && prenomOk && cgu) {
        $.ajax({
        type : 'POST',
        url: "php/ajax/is_valid_c.php",
        async    : false,
        data     : {vara : $("#verif").val().toUpperCase()},
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        dataType : "text",
        success: function( out) {
                        if (out==="true") {
                            c_c = true;
                            
                        }
                        if (c_c === true) {
                            $.ajax({
                            type : 'POST',
                            crossDomain: true,
                            //http://cen-normandie.com/majiic/php/sent_mail.php
                            url: "/php/sent_mail.php",
                            async    : false,
                            data     : {courriel : mail, dwp : pwd, nom_ : nom, prenom_ : prenom, cgu_ : true, cgu_content},
                            error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
                            dataType : "text",
                            success: function(data) {
                                        if (data=="send") {
                                            alert("Un mail vient d'être envoyé à "+mail+",\n Vous pouvez dès à présent vous connecter à l'application");
                                            $('#ModalLogin').modal('hide');
                                        } else {
                                            alert("Un compte existe déjà pour cette adresse");
                                        }
                                    }
                            });
                        } else {
                            alert("Le mot 'captcha' n'est pas valide\n Etes-vous un robot ? ");
                            sessionStorage.setItem('trying', 'account');
                            location.reload();
                        }
                    }
        });
    } else {
        alert("Veuillez accepter les Conditions Générales d'Utilisation");
        sessionStorage.setItem('trying', 'account');
        location.reload();
    }  
});

