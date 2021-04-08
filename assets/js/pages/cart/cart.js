$(function () {
    $.ajax({
        type: 'post',
        url: '/cart',
        data: { cart_items: cart.items },
        dataType: 'json',
        beforeSend: function() {
            $('section.cart .spinner-container').removeClass('d-none');
        },
        success: function ({ cart_items, plates }) {
            var items = '';
            var listItems = '';

            cart_items.forEach((cartItem, index) => {
                items = '';

                if (cartItem.plate_complements) {
                    cartItem.plate_complements.forEach((complement, cIndex) => {
                        listItems = '';

                        cartItem.plate_items.forEach((item, iIndex) => {
                            if (complement == plates[index].items[iIndex].complement_id) {
                                listItems += `
                                    <li><i class="far fa-check-${plates[index].complements[cIndex].multiple ? 'square' : 'circle'} mr-1"></i>${plates[index].items[iIndex].name}</li>
                                `;
                            }
                        });

                        items += `
                            <div class="list col">
                                <strong>${plates[index].complements[cIndex].name}</strong>
                                <ul>
                                    ${listItems}
                                </ul>
                            </div>
                        `;
                    });

                } else {
                    items = '';
                }

                $(`
                    <div class="cart-item" cart-item="${cartItem.id}">
                        <div class="item-image">
                            <img src="${plates[index].image ?? '/media/categories/others/others-plates-img-default-150x150.jpg'}" alt="${plates[index].name}">
                        </div>
                        
                        <div class="item-content col">
                            <span data-toggle="modal" data-target="#removeCart" class="delete">
                                <i class="far fa-trash-alt"></i>
                            </span>
            
                            <div class="item-header">
                                <h4>${plates[index].name}</h4>
                                <span>R$ ${formatter.format(cartItem.plate_total_price/100)}</span>
                            </div>
                            
                            <div class="item-details">
                               ${items}
                            </div>
                        </div>
                    </div>  
                `).appendTo('.cart-items').on('click', 'span.delete', function() {
                    $(this).addClass('deleting');
                });
            });

            $('button.choose-payment').attr('restaurant-id', plates.length ? plates[0].restaurant_id : '');

            updateCart(cart_items);

            $('.cart-subtotal span.price').text(formatter.format(cart.total/100));
            $('.cart-total span.price').text(formatter.format(cart.total/100));
        },
        complete: function() {
            $('section.cart .spinner-container').addClass('d-none');
        },
    });

});