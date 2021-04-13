$(function () {
    $('#choosePayment').on('shown.bs.modal', function() {
        $.ajax({
            type: 'post',
            url: '/cart/choose-payment',
            data: { 
                restaurant_id: $('button.choose-payment').attr('restaurant-id')
            },
            dataType: 'json',
            beforeSend: function() {
                $('#choosePayment .spinner-container').removeClass('d-none');
            },
            success: function (response) {
                $('#choosePayment .modal-body').html('');

                $('#choosePayment select[name="choosePayment"]').append(`
                    <option value="">Selecione</option>    
                `);

                var payments = [];

                payments = response.payments.reduce((payments, payment) => {
                    payments.push(payment.payment_id);
                    return payments;
                }, []);

                console.log(payments);
                
                $('#choosePayment .modal-body').html('');
                
                $('#choosePayment .modal-body').append(`
                    <h6><strong><i class="fas fa-money-check-alt mr-2"></i>Pagamento Online:</strong></h6>
                    
                    <p>Disponível em breve.</p>
                    
                    <h6><strong><i class="fas fa-truck mr-2"></i>Pagamento na Entrega:</strong></h6>

                    <div class="row justify-content-between">
                        <div class="form-group row flex-column no-gutters col-6">
                            <span class="payment-method-name mb-1"><strong>Dinheiro</strong></span>
                            
                            <span>
                                ${payments.includes(7) ? `
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id ="restaurantPaymentMethods7" name="restaurantPaymentMethods" class="custom-control-input" value="7" checked>

                                        <label class="custom-control-label" for="restaurantPaymentMethods7">
                                        
                                        <img src="/media/payment-methods/money-40x23.jpg" alt=""></label>
                                    </div>
                                ` : ''}
                            </span>
                        </div>

                        <div class="form-group row flex-column no-gutters col-6">
                            <span class="payment-method-name mb-1"><strong>Cartão de Débito</strong></span>
                            
                            <span class="d-flex">
                                ${payments.includes(4) ? `
                                    <div class="custom-control custom-radio mr-3">
                                        <input type="radio" id ="restaurantPaymentMethods4" name="restaurantPaymentMethods" class="custom-control-input" value="4">
                                        <label class="custom-control-label" for="restaurantPaymentMethods4"><img src="/media/payment-methods/card-mastercard-40x23.jpg" alt=""></label>
                                    </div>
                                ` : ''}

                                ${payments.includes(5) ? `
                                    <div class="custom-control custom-radio mr-3">
                                        <input type="radio" id ="restaurantPaymentMethods5" name="restaurantPaymentMethods" class="custom-control-input" value="5">

                                        <label class="custom-control-label" for="restaurantPaymentMethods5"><img src="/media/payment-methods/card-visa-40x23.jpg" alt=""></label>
                                    </div>
                                ` : ''}

                                ${payments.includes(6) ? `
                                    <div class="custom-control custom-radio mr-3">
                                        <input type="radio" id ="restaurantPaymentMethods6" name="restaurantPaymentMethods" class="custom-control-input" value="6">

                                        <label class="custom-control-label" for="restaurantPaymentMethods6"><img src="/media/payment-methods/card-elo-40x23.jpg" alt=""></label>
                                    </div>
                                ` : ''}
                            </span>
                        </div>

                        <div class="form-group row flex-column no-gutters col-6">
                            <span class="payment-method-name mb-1"><strong>Cartão de Crédito</strong></span>

                            <span class="d-flex">
                                ${payments.includes(1) ? `
                                    <div class="custom-control custom-radio mr-3">
                                        <input type="radio" id ="restaurantPaymentMethods1" name="restaurantPaymentMethods" class="custom-control-input" value="1">                                          <label class="custom-control-label" for="restaurantPaymentMethods1"><img src="/media/payment-methods/card-mastercard-40x23.jpg" alt=""></label>
                                    </div>
                                ` : ''}

                                ${payments.includes(2) ? `
                                    <div class="custom-control custom-radio mr-3">
                                        <input type="radio" id ="restaurantPaymentMethods2" name="restaurantPaymentMethods" class="custom-control-input" value="2">

                                        <label class="custom-control-label" for="restaurantPaymentMethods2"><img src="/media/payment-methods/card-visa-40x23.jpg" alt=""></label>
                                    </div>
                                ` : ''}

                                ${payments.includes(3) ? `
                                    <div class="custom-control custom-radio mr-3">
                                        <input type="radio" id ="restaurantPaymentMethods3" name="restaurantPaymentMethods" class="custom-control-input" value="3">
                                        
                                        <label class="custom-control-label" for="restaurantPaymentMethods3"><img src="/media/payment-methods/card-elo-40x23.jpg" alt=""></label>
                                    </div>
                                ` : ''}
                            </span>
                        </div>
                    </div>   
                `);
            },
            complete: function() {
                $('#choosePayment .spinner-container').addClass('d-none');
            }
        });
    });

    $('#choosePayment button#checkout').on('click', function() {
        $.ajax({
            type: 'post',
            url: '/cart/checkout',
            data: {
                restaurant_id: $('button.choose-payment').attr('restaurant-id'),
                payment_method: $('input[name="restaurantPaymentMethods"]:checked').val(),
                cart: cart,        
            },
            dataType: 'json',
            beforeSend: function() {
                $('#choosePayment button#checkout').attr('disabled', true);
            },
            success: function (response) {
                if (response.message == 'success') {
                    iziToast.success({
                        title: 'Sucesso!',
                        message: 'Pedido Finalizado!',
                        position: 'topRight',
                        timeout: 2000,
                    });  
    
                    // Redirect page
                    setTimeout(function() {
                        window.location.href = BASE_URL+'/account/orders';
                    }, 2100);
    
                    cleanCart();
                } 
            }
        });
    });
});