

function yop(){
        $.ajax({
            url      : "php/ajax/infos.js.php",
            type     : "POST",
            data     : {email: $("#courriel").val(), pass:$("#password").val()},
            async    : true,
            dataType : "text",
            error    : function(request, error) { console.log("not ajax success ");},
            success  : function(data) {
                var nom         = data.split('|')[0];
                var prenom      = data.split('|')[1];
                var structure   = data.split('|')[2];
                var logo        = data.split('|')[3];
                
                $("#nom_prenom").val(nom+' '+prenom);
                $("#structure").val(structure);
                $("#logo").val(logo);
            }
        });// End ajax
};



function check_pwd(pwd_v){
    var regex=/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&_]{8,}$/;
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



$("#save_i_edit").on('click',function(e){
    var pwd  = $("#i_pwd").val();
    var nom  = $('#i_nom').val();
    var prenom  = $('#i_prenom').val();
    var structure  = $('#i_structure').val();
    
    
    var passwordOk = check_pwd(pwd);
    
    if (passwordOk) {
        $.ajax({
            type : 'POST',
            url: "php/ajax/save_form/save_i_edit.js.php",
            async    : false,
            data     : {pwd_:pwd,n_:nom,p_:prenom,str_:structure},
            error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
            dataType : "text",
            success: function( ) {
                //location.reload();
            }
        });
        
        
        if ( file_logo && (typeof fileType !== 'undefined')) {
            if (validImageTypes.includes(fileType)) {
                //Le fichier est de type image
                reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = function () {
                    $.ajax({
                        method   : "POST",
                        url: "php/ajax/save_form/save_logo.php",
                        dataType : "text",
                        data: {
                            base64_img : reader.result.split(',')[1],
                            filename : fileName
                        },
                        success: function( data ) {
                                //console.log(data);
                        }
                    });
                };
            }else {
                alert("selectionnez une image (jpeg,png,gif)");
            }
        }
        
        
        location.reload();
    } 
});


    var ints = [];
    var locs = [];
    var cars = [];
    var phos = [];
    $.ajax({
            url      : "php/ajax/graph_info_user.js.php",
            type     : "POST",
            data     : {},
            async    : false,
            dataType : "json",
            error    : function(request, error) { console.log("not ajax success ");},
            success  : function(data) {
                $.each(data, function(i,item) {
                    ints.push(data[i].month_+' - '+data[i].year_.substring(2,4));
                    locs.push(data[i].s_l);
                    cars.push(data[i].s_c);
                    phos.push(data[i].s_p);
                });
            }
        });


    //Highcharts
Highcharts.chart('evolution', {
  chart: {
    type: 'area'
  },
  title: {
    text: 'Évolution des saisies'
  },
  subtitle: {
    text: 'sur 12 derniers mois'
  },
   colors: ['#8d59a7','#bc4444','#4eb276'],
  xAxis: {
    categories: ints,
    tickmarkPlacement: 'on',
    title: {
      enabled: false
    }
  },
  yAxis: {
    title: {
      text: ''
    },
    labels: {
      formatter: function () {
        return this.value ;// /1000
      }
    }
  },
  tooltip: {
    split: true,
    valueSuffix: ''
  },
  plotOptions: {
    area: {
      stacking: 'normal',
      lineColor: '#666666',
      lineWidth: 1,
      marker: {
        lineWidth: 1,
        lineColor: '#666666'
      }
    }
  },
  credits: {
      enabled: false
  },
  series: [{
    name: 'Localisations',
    data: locs
  }, {
    name: 'Caractérisations',
    data: cars
  }, {
    name: 'Photos',
    data: phos
  }]
});


























