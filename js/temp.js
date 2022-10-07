//Charge les éléments d'un projet existant
function update_view_projet(projets_json) {
    //update_dtActions();

    //old content replace for datatable
    c_actions =0;
    for (const actions in actions_f) {
        //LOAD PROJET PROPERTIES

        const personnes_actions = actions_f[actions].personne_action.split('|');
        let badges_ = '';
        //Liste des badges personnes
        for (const pe in personnes_actions) {
            badges_ = badges_ + '<div id=""><span class="badge mt-1 bg-dark text-light">'+personnes_actions[pe]+'<i id="" class="ps-1 fas fa-window-close"></i></span></div>';
        }

        document.getElementById('list_actions').insertAdjacentHTML("beforeend", 
        `
        <div class="d-flex flex-column flex-grow-1 m-2 border border-dark rounded">
            <div class="d-flex gx-1 align-items-center justify-content-between d-flex flex-grow-1">
                <div class="col-xs-1 text-truncate">
                    <label id="id_action_${actions_f[actions].id_action}" id_action="${actions_f[actions].id_action}" class="p-1 col-form-label text-secondary">${actions_f[actions].id_action}</label>
                </div>
                <div class="col-xs-3 text-truncate">
                    <span type="text" id="nom_action_${actions_f[actions].id_action}" value="">${actions_f[actions].code_action}</span>
                </div>
                <div class="col-xs-3">
                    <span type="text"  id="financeurs_action_${actions_f[actions].id_action}" value="" >${actions_f[actions].financements}</span>
                </div>
                <div class="col-xs-2">
                    <span type="text"  id="site_action_${actions_f[actions].id_action}" value="" >${actions_f[actions].site}</span>
                </div>
                <div class="col-xs-1">
                    <span type="text"  id="heures_action_${actions_f[actions].id_action}" value="" disabled>${actions_f[actions].nb_h}</span>
                </div>
                <div class="col-xs-1">
                    <button id="add_p_action_${actions_f[actions].id_action}" id_action="${actions_f[actions].id_action}" class=" bg-light border-0 text-dark fs-6 m-1 px-1" ><i class="fas fa-user-plus"></i></button>
                    <button id="del_action_${actions_f[actions].id_action}" id_action="${actions_f[actions].id_action}" class=" bg-light border-0 text-danger fs-6 m-1 px-1" ><i class="fas fa-trash-alt"></i></button>
                </div>
            </div>
            <div class="d-flex flex-wrap justify-content-end align-items-center id="ist_personnes_action_${actions_f[actions].id_action}">
                ${badges_}
            </div>
        </div>`
        );
        add_events_actions ();
        c_actions++;
    }
}