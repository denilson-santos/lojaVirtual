$(function () {
    $('#slider-range').slider({
        range: true,
        min: 0,
        max: maxFilterPrice,
        values: [$('#range-price0').val(), $('#range-price1').val()],
        slide: function (event, ui) {
            $('#amount').val('R$' + ui.values[0] + ' - R$' + ui.values[1]);
        },
        change: function(event, ui) {
            $('#range-price'+ui.handleIndex).val(ui.value);  
            $('.filterArea form').submit(); 
        }
    });
  
    $('#amount').val('R$' + $('#slider-range').slider('values', 0) + ' - R$' + $('#slider-range').slider('values', 1));
    
    $('.filterArea').find('input').on('change', function () {
        $('.filterArea form').submit();
    }); 

   $('#search').on('click', function (e) { 
       e.preventDefault();
       $('.filterArea form input[name="term"] ').html('');
       $('.filterArea form input[name="category"] ').html('');

       var searchTerm = $('input[name="term"]').val();
       var category = $('select[name="category"]').val();
    
       console.log('term',searchTerm);
       console.log('category', category);

       $('.filterArea form input[name="term"] ').val(searchTerm);
       $('.filterArea form input[name="category"] ').val(category);
       $(".filterArea form").attr("action", BASE_URL+"search");
       $('.filterArea form').submit();
   });

//    $('#formNewsletter').submit(function (e) { 
//         e.preventDefault();
//         var dados = $(this).serialize();

//         $.ajax({
//             type: "POST",
//             url: "processa.php",
//             data: dados,
//             success: function( data )
//             {
//                 alert( data );
//             }
//         });
        
//         return false;
//    });

    $('#formNewsletter').on('submit', function (e) { 
        e.preventDefault();
        var email = $('#email').val();

        $("#subscribe").attr("disabled", true);
        
        $.ajax({
            type: 'POST',
            url: BASE_URL+'newsletter/subscribe',
            data: {email : email},
            dataType: 'json',
            success: function(data) {
                if (data.status == 1) {
                    $('#modalNewsletter .modal-body').html(`<i class="far fa-check-circle success"></i><p>${data.message}</p>`);
                    $('#modalNewsletter .modal-footer button').removeClass('btn-red');
                    $('#modalNewsletter .modal-footer button').addClass('btn-green');
                    $('#modalNewsletter .modal-footer button').text('Entendi');
                    $('#modalNewsletter').modal('toggle');

                } else if (data.status == 0){
                    $('#modalNewsletter .modal-body').html(`<i class="far fa-check-circle success"></i><p>${data.message}</p>`);
                    $('#modalNewsletter .modal-footer button').removeClass('btn-red');
                    $('#modalNewsletter .modal-footer button').addClass('btn-green');
                    $('#modalNewsletter .modal-footer button').text('Entendi');
                    $('#modalNewsletter').modal('toggle');
                } else {
                    $('#modalNewsletter .modal-body').html(`<i class="fas fa-exclamation-circle error"></i><p>${data.message}</p>`);
                    $('#modalNewsletter .modal-footer button').removeClass('btn-green');
                    $('#modalNewsletter .modal-footer button').addClass('btn-red');
                    $('#modalNewsletter .modal-footer button').text('Fechar');
                    $('#modalNewsletter').modal('toggle');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#modalNewsletter .modal-body').html(`<i class="fas fa-exclamation-circle btn-red"></i><p>${data.message}</p>`);
                $('#modalNewsletter .modal-footer button').removeClass('btn-green');
                $('#modalNewsletter .modal-footer button').addClass('btn-red');
                $('#modalNewsletter .modal-footer button').text('Fechar');
                $('#modalNewsletter').modal('toggle');
            },
            complete: function() {
                $("#subscribe").attr("disabled", false);
            }
        });
    });
});


    
