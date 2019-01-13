require('../css/messenger.css');
require('../css/loading.css');

$(document).ready(function(){
    $('#ajax-loading').hide();


    $('.mess-onglet').click(function () {
        if($('.mess-onglet').hasClass('selected')){
            $('.mess-onglet').removeClass('selected')
        }
        $(this).addClass('selected');
        $('#mess-box').children().hide();
        $('#ajax-loading').show();

        var target = $(this).attr('id').toString();

        $.ajax({
            url : '/messages/' + target,
            type : 'GET',
            dataType : 'html',
            success : function(code_html, statut){
                $(code_html).replaceAll('#mess-box');
                $('#ajax-loading').hide();
            },
            error : function(resultat, statut, erreur){
                console.log(resultat, statut, erreur)
            }
        });
    });
});

