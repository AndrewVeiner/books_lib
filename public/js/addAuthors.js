var $addAuthorsLink = $('<a href="#" class="add_authors_link">Add a authors</a>');
var $newLinkLi = $('<li></li>').append($addAuthorsLink);

jQuery(document).ready(function() {
    var $collectionHolder = $('ul.authors');
    $collectionHolder.append($newLinkLi);
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addAuthorsLink.on('click', function(e) {
        e.preventDefault();
        addAuthorsForm($collectionHolder, $newLinkLi);
    });
});

function addAuthorsForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype.replace(/__name__/g, index);
    $collectionHolder.data('index', index + 1);
    var $newFormLi = $('<li></li>').append(newForm);
    $newFormLi.append('<a href="#" class="remove-authors">x</a>');
    $newLinkLi.before($newFormLi);
    $('.remove-authors').click(function(e) {
        e.preventDefault();
        $(this).parent().remove();
        return false;
    });
}