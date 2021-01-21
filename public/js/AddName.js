let $colletionHolder;

let $addNameButton = $('<button type="button" class="add_name_link btn btn-primary">Ajouter un nom</button>');
let $newLinkLi = $('<li></li>').append($addNameButton);

$(document).ready(function () {

    $colletionHolder = $('ul.AddName');

    $colletionHolder.find('li').each(function () {
        addNameFormDeleteLink($(this));
    });

    $colletionHolder.append($newLinkLi);

    $colletionHolder.data('index', $colletionHolder.find('input').length);

    $addNameButton.on('click', function (e) {
        addNameForm($colletionHolder, $newLinkLi);
    });

    // Fonction qui créer le formulaire d'ajout
    function addNameForm($colletionHolder, $newLinkLi) {
        let prototype = $colletionHolder.data('prototype');

        let index = $colletionHolder.data('index');

        let newForm = prototype;

        newForm = newForm.replace(/__name__/g, index);

        $colletionHolder.data('index', index + 1);

        let $newFormLi = $('<li></li>').append(newForm);
        $newLinkLi.before($newFormLi);

        addNameFormDeleteLink($newFormLi);
    }

    // Formulaire de suppression
    function addNameFormDeleteLink($nameFormLi) {
        let $removeFormButton = $('<button type="button" class="btn btn-danger mb-2"><i class="fas fa-trash"></i></button>');
        $nameFormLi.append($removeFormButton);

        $removeFormButton.on('click', function (e) {
            $nameFormLi.remove();
        })
    }

});