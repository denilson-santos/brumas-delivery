$(function () {
    var plate = {};
    var itemsChecked = [];
    var totalPrice = 0;

    $('#showPlate').on('show.bs.modal', function() {
        $.ajax({
            type: 'POST',
            url: '/plate/show',
            data: { plate_id: $('.showing').attr('plate-id') },
            dataType: 'json',
            beforeSend: function (data) {
                $('#showPlate .spinner-container').removeClass('d-none');
            },
            success: function (response) {
                plate = response.plate;
                console.log(response);

                var complements = '';
                var complementsRequired = '';
                var complementContent = '';
                var items = '';

                $('#showPlate .modal-title').text(response.plate.name);
                $('#showPlate .modal-subtitle').text(response.plate.description);
                $('#showPlate .plate-image img').attr('src', response.plate.image ?? '/media/categories/others/others-plates-img-default.jpg').removeClass('d-none');
                
                if (response.plate.promo) {
                    $('#showPlate .plate-price span').text(formatter.format(response.plate.promo_price/100));
                } else {
                    $('#showPlate .plate-price span').text(formatter.format(response.plate.price/100));
                }

                if (!response.plate.complements.length) {
                    if (response.plate.promo) {
                        $('#showPlate button#addCart span').text(formatter.format(response.plate.promo_price/100));
                    } else {
                        $('#showPlate button#addCart span').text(formatter.format(response.plate.price/100));
                    }
                    
                    $('#showPlate button#addCart').attr('disabled', false);
                    return;
                }

                response.plate.complements.forEach((complement, complementIndex) => {
                    complementContent = `
                        <div class="complement">
                            <div class="content">
                                <div class="name">${complement.name}</div>
                                <div class="description">${complement.multiple ? 'Escolha até ' + complement.itens.length + ' opções.' : 'Escolha 1 opção.'}</div>
                            </div>

                            <div class="options my-auto">
                                ${complement.required ? '<span class="badge badge-primary p-1">OBRIGÁTORIO</span>' : ''}
                            </div>
                        </div>
                    `;
                    
                    items = '';

                    complement.itens.forEach((item, itemIndex) => {
                        items += `
                            <div class="item ${complement.required ? 'required' : ''}" complement-id="${complement.id_complement}" item-id="${item.id_item}">
                                <div class="content">
                                    <div class="name">${item.name}</div>
                                    <div class="price">+ R$ <span>${formatter.format(item.price/100)}</span></div>
                                </div>
                                
                                ${complement.multiple ? `
                                    <div class="custom-control custom-checkbox my-auto">
                                        <input type="checkbox" class="custom-control-input" id="c${complementIndex}i${itemIndex}" name="c${complementIndex}i${itemIndex}">
                                        <label class="custom-control-label" for="c${complementIndex}i${itemIndex}">   
                                        </label>
                                    </div>  
                                ` : `
                                    <div class="custom-control custom-radio my-auto">
                                        <input type="radio" class="custom-control-input" id="c${complementIndex}i${itemIndex}" name="c${complementIndex}i" value="c${complementIndex}i${itemIndex}">
                                        <label class="custom-control-label" for="c${complementIndex}i${itemIndex}">   
                                        </label>
                                    </div>  
                                `}
                                
                            </div>

                            <hr class="m-0">
                        `;
                    });

                    if (complement.required == 1) {
                        complementsRequired += `
                            <div class="complement-container">
                                ${complementContent}
                                <div class="items">
                                    ${items}
                                </div>
                            </div>
                        `;
                    } else {
                        complements += `
                            <div class="complement-container">
                                ${complementContent}
                                <div class="items">
                                    ${items}
                                </div>
                            </div>
                        `;
                    }
                });
                
                $('#showPlate .complements').prepend(`
                    ${complementsRequired}
                    ${complements}
                `);
            },
            complete: function () {
                $('#showPlate .complements').append(`
                    <div class="comments">
                        <label for="comments">Observações</label>
                        <div class="mb-2">Digite abaixo as suas observações pra esse prato</div>
                        <textarea name="comments" id="comments" class="form-control" rows="4" placeholder="Digite suas observações aqui"></textarea>
                    </div>
                `);

                $('#showPlate .spinner-container').addClass('d-none');
            }
        });
    });
    
    $('#showPlate').on('change', 'input', function() {
        if ($(this).is(':checked') && $(this).attr('type') === 'checkbox') {
            $(this).closest('.item').addClass('checked');

        } else if ($(this).is(':checked') && $(this).attr('type') === 'radio') {
            $(`input[name="${$(this).attr('name')}"]`).closest('.item').removeClass('checked');
            $(`input[name="${$(this).attr('name')}"]`).closest('.item').removeClass('required');
            $(this).closest('.item').addClass('checked');
        
        } else {
            $(this).closest('.item').removeClass('checked');
        }

        complementsChecked = [];
        itemsChecked = [];
        
        $('.checked').each(function (index, element) {
            itemsChecked.push(parseInt($(element).attr('item-id')));

            if (!complementsChecked.includes(parseInt($(element).attr('complement-id')))) complementsChecked.push(parseInt($(element).attr('complement-id')));
        });
        
        console.log(complementsChecked);

        totalPrice = 0;
        
        plate.complements.forEach(complement => {
            complement.itens.forEach(item => {
                if (itemsChecked.indexOf(item.id_item) >= 0) totalPrice += item.price;
            });
        });

        $('#showPlate button#addCart span').text(formatter.format(totalPrice/100));
        
        if (totalPrice && !$('.complements .required').length) {
            $('#showPlate button#addCart').attr('disabled', false);
        } else {
            $('#showPlate button#addCart').attr('disabled', true);
        }
    });

    $('#showPlate button#addCart').on('click', function() {
        var comments = '';

        comments = $('#showPlate textarea[name="comments"]').val();

        addCart(plate.id_plate, comments, plate.complements.length ? complementsChecked : [], plate.complements.length ? itemsChecked : [], plate.complements.length ? totalPrice : plate.price);
        
        iziToast.success({
            title: 'Sucesso!',
            message: 'Prato adicionado ao carrinho!',
            position: 'topRight',
            timeout: 2000,
        });
    });

    $('#showPlate').on('hidden.bs.modal', function() {
        cart = getCart();

        console.log(cart);

        $('.cart-quantity').text(cart.quantity);
        $('.cart-price').text(formatter.format(cart.total/100));

        $('#showPlate .add-to-cart input').remove();
        
        $('.plate-content').removeClass('showing');

        $('#showPlate button#addCart span').text('0,00');

        $('#showPlate .complements').html('');

        $('#showPlate button#addCart').attr('disabled', true);
    });
}); 