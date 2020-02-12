$('#add-image').click(function () {
    // Je récupère le numéro des nouveaux champs que je vais créer
    const index = +$('#widgets-conter').val();

    // Je récupère les prototypes d'entrées
    const tmpl = $('#ad_images').data('prototype').replace(/__name__/g,index);

    // J'injecte ce code au sein de la div
    $('#ad_images').append(tmpl);

    $('#widgets-conter').val(index + 1);

    // Je gère le bouton delete ici
    handleDeleteButtons();
});

function handleDeleteButtons(){
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        $(target).remove();
    });
}

function updateCounter(){
    const count = +$('#ad_images div.form-group').length;
    $('#widgets-conter').val(count);
}
updateCounter();
// Je l'appelle dès le début du chargement de la page
handleDeleteButtons();