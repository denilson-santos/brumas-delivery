$(function () {    
    $('.filter-area').find('input, select').on('change', function () {
        $('.filter-area form').submit();
    }); 

    var filterType = $('.payment-type').select2({
        theme: 'bootstrap4'
    });

    $('#search').on('click', function (e) { 
       e.preventDefault();
       $('.filter-area form input[name="term"] ').html('');
       $('.filter-area form input[name="category"] ').html('');

       var searchTerm = $('input[name="term"]').val();
    
       console.log('term',searchTerm);

       $('.filter-area form input[name="term"] ').val(searchTerm);
       $(".filter-area form").attr("action", BASE_URL+"search");
       $('.filter-area form').submit();
   });

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
                    $('#modalNewsletter .modal-footer button').removeClass('btn-cc-red');
                    $('#modalNewsletter .modal-footer button').addClass('btn-cc-green');
                    $('#modalNewsletter .modal-footer button').text('Entendi');
                    $('#modalNewsletter').modal('toggle');

                } else if (data.status == 0){
                    $('#modalNewsletter .modal-body').html(`<i class="far fa-check-circle success"></i><p>${data.message}</p>`);
                    $('#modalNewsletter .modal-footer button').removeClass('btn-cc-red');
                    $('#modalNewsletter .modal-footer button').addClass('btn-cc-green');
                    $('#modalNewsletter .modal-footer button').text('Entendi');
                    $('#modalNewsletter').modal('toggle');
                } else {
                    $('#modalNewsletter .modal-body').html(`<i class="fas fa-exclamation-circle error"></i><p>${data.message}</p>`);
                    $('#modalNewsletter .modal-footer button').removeClass('btn-cc-green');
                    $('#modalNewsletter .modal-footer button').addClass('btn-cc-red');
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

    $(".rating-filter-page-home.rating").rateYo({
        rating: parseInt($('input.rating-page-home').val()),
        starWidth: '18px',
        ratedFill: '#ffc929',
        normalFill: '#ddd',
        fullStar: true,
        spacing: '3px',
        starSvg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>',
    }).on('rateyo.set', function (e, data) {
        $('input.rating-page-home').val(data.rating);
        $('.filter-area form').submit();
    });   

    $('.rating-restaurant-widget .id-restaurant').each((index, element) => { 
        var idRestaurant = element.value;
        var rating = parseFloat($(`span.restaurant-rating-${idRestaurant}`).html());
        
        $(`.rating-restaurant-widget-${idRestaurant} .rating-read-only`).rateYo({
            rating: rating,
            starWidth: '18px',
            readOnly: true,
            ratedFill: '#ffc929',
            normalFill: '#ddd',
            spacing: '3px',
            starSvg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>'
        });         
    
    });



    // for (var i = 1; i <= totalReestaurants; i++) {
        
    // }
    
    // if ($('select.payment-type option').hasClass('all-types')) { $('.payment-type').select2('data');
    //     console.log('true');
    //     filterType.val(null); 
    //     // $(this).removeClass('all-types');
    // } 

    // var filterPaymentType = $('.payment-type').select2('data');

    // filterPaymentType.forEach(element => {
        // console.log(element.id);
        // filterType.val(null).trigger('change'); 
    // });

    // $('.menu-category ul.dropdown-menu .dropdown-item').on('click', function () {
    //     $('a.category-nav').html('');
    // });

});


    
