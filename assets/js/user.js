require('../css/user.css');
require('../css/loading.css');

$(document).ready(function(){
    $('#messenger-wrapper').hide();

    $('#send-message').click(function () {
        $('#messenger-wrapper').show();

        var target = $(this).attr('itemid').toString();

        $.ajax({
            url : '/messages/sendTo/' + target,
            type : 'GET',
            dataType : 'html',
            success : function(code_html, statut){
                $(code_html).replaceAll('#messenger-wrapper');
                $('#ajax-loading').hide();
            },
            error : function(resultat, statut, erreur){
                console.log(resultat, statut, erreur)
            }
        });
    });
});