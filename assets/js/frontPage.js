$(document).ready(function(){
    $('#ajax-loading').hide();


    $('.topThreeAssets').click(function () {
        if($('.topThreeAssets').hasClass('selected')){
            $('.topThreeAssets').removeClass('selected')
        }
        $(this).addClass('selected');
        $('#bestList').children().hide();
        $('#ajax-loading').show();

        var id = $(this).attr('name').toString();

        $.ajax({
            url : '/topThree/' + id,
            type : 'GET',
            dataType : 'html',
            success : function(code_html, statut){
                $(code_html).replaceAll('#bestList');
                $('#ajax-loading').hide();
            },
            error : function(resultat, statut, erreur){
                console.log(resultat, statut, erreur)
            }
        });
    });
});

require('../css/loading.css');
require('../css/frontPage.css');
require('../css/buildList.css');

