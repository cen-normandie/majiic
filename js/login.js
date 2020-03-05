
function toTitleCase(str){return str.replace(/\w\S*/g,function(txt){return txt.charAt(0).toUpperCase()+txt.substr(1).toLowerCase()})}

function verifMail(champ){
    var regex=/^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
    if(!regex.test(champ.value)){
        if (champ.value != "") {
            /* mail non valid */
            alert('format du courriel non valide (xxx@xxx.xx)');
            $('#email').val('');
            return false
            }
        if (champ.value == "") {
            /* mail non valid */
            alert('courriel vide');
            $('#email').val('');
            return false
            }
        }
    else{
        //surligne_and_check(champ,false);
        return true
        }
    }

function verifPassword(champ){
    var regex=/[a-zA-Z0-9._-#!$%?,]+/;
    if(!regex.test(champ.value)){
        if (champ.value != "") {
            /* mail non valid */
            $('#email').val('');
            alert('format du mot de passe nom valide ([a-zA-Z0-9._-])');
            return false
            }
        if (champ.value == "") {
            return false
            }
        }
    else{
        return true
        }
    };


