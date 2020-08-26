$(function () {
    $('#restaurantAddOperation').fireModal({
        title: 'Adicionar Horários de Funcionamento',
        body: $('#modalAddOperation'),
        buttons: [
            {
                text: 'Voltar',
                id: 'backAddOperation',
                class: 'btn btn-secondary',
                handler: function(currentModal) {
                    $.destroyModal(currentModal);
                }
            },
            {
                text: 'Salvar',
                id: 'saveAddOperation',
                class: 'btn btn-primary',
                handler: function(currentModal) {
                    var validation = isValidOperation(currentModal.find('.table-operation'));
                    
                    // Object.keys(validation.weekDays).forEach(day => {
                    //     // if(validateOperation[day].validateRequired) hasOneValidateRequired = true;
                    //     if (!validation.weekDays[day].validateRequired) {
                    //         $(`.table-operation input[name="${day}Open1"]`).css('border-color', '#fa3734');
                    //         $(`.table-operation input[name="${day}Close1"]`).css('border-color', '#fa3734');
                    //         // $(`.table-operation input[name="${day}Open1"]`).css('border-color', '#ced4da');
                    //         // $(`.table-operation input[name="${day}Close1"]`).css('border-color', '#ced4da');
                    //         return false;
                    //     } else {
                    //         $(`.table-operation input[name="${day}Open1"]`).css('border-color', '#ced4da');
                    //         $(`.table-operation input[name="${day}Close1"]`).css('border-color', '#ced4da');
                    //         // $(`.table-operation input[name="${day}Open1"]`).css('border-color', '#fa3734');
                    //         // $(`.table-operation input[name="${day}Close1"]`).css('border-color', '#fa3734');
                    //     }
                    // });

                    // if (validation.weekDays) {
                    //     $('.table-operation input.required').css('border-color', '#28a745');
                    // } else {
                    //     $('.table-operation input.required').css('border-color', '#fa3734');
                    // }
                }
            }
        ],
        size: 'modal-lg',
        footerClass: 'justify-content-between',
    });

    // Timepicker in inpus of time
    $('.table-operation').on('focus', 'input.time', function (e) {
        $(this).timepicker({
            icons: { 
                up: 'fas fa-chevron-up',
                down: 'fas fa-chevron-down'
            },
            showMeridian: false,
            defaultTime: '0:00'
        });    
    });

    $('.table-operation').on('selectric-change', 'select[name="weekDay"]', function(event, element, selectric) {      
        // console.log(selectric.state.selectedIdx);
        $(element).attr('data-day-selected', selectric.state.selectedIdx);
        // console.log(selectric.state.selectedIdx);
        // if (selectric.state.selectedIdx.length == 3) {
        //     $(`.selectric-restaurant-main-categories ul li:nth-child(${selectric.state.selectedIdx[0] + 1})`).removeClass('selected');
        //     selectric.state.selectedIdx.shift();
        // }        
    });

    // Add row
    var count = 0
    ;
    $('#addWeekDay').on('click', function () {
        $('#addWeekDay').attr('disabled', true);
        
        count++;

        var weekDays = renderWeekDays(count);

        $('.table-operation').append(`<tr data-row="${count}"><td>${weekDays}</td><td><input type="text" name="sundayOpen1" class="form-control text-center time"></td><td><input type="text" name="sundayOpen1" class="form-control text-center time"></td><td><input type="text" name="sundayOpen1" class="form-control text-center time"></td><td><input type="text" name="sundayOpen1" class="form-control text-center time"></td><td><div class="actions"><button type="button" class="btn btn-primary btn-sm save action"><i class="fas fa-check"></i></button><button type="button" class="btn btn-primary btn-sm delete action"><i class="fas fa-trash-alt"></i></button></div></td></tr>`);

        refreshSelect();
        refreshMask();      
    });

    $('#addWeekDay').click();
    
    // Save row
    $('.table-operation').on('click', '.save', function () {
        $('#addWeekDay').attr('disabled', false);

        var currentRow = $(this).closest('tr');
        var currentRowElements = currentRow.find('input.time, select');
        var renderedRow = '';

        currentRowElements.each( function (index, element) {
            if (index === 0) {
                // Verify if the row exist in div hidden
                if ($(`.selected-week-days input[data-row="${currentRow.data('row')}"]`).data('row')) {
                    $(`.selected-week-days input[data-row="${currentRow.data('row')}"]`).attr('data-week-day-index', currentRowElements.eq(index).data().selectric.state.selectedIdx);                    
                    $(`.selected-week-days input[data-row="${currentRow.data('row')}"]`).attr('data-week-day', currentRowElements.eq(index).val());                    
                } else {
                    $('.selected-week-days').append(`<input type="hidden" data-week-day-index="${currentRowElements.eq(index).data().selectric.state.selectedIdx}" data-week-day="${currentRowElements.eq(index).val()}" data-row="${currentRow.data('row')}">`);
                }

                if (currentRowElements.eq(index).data().selectric.state.selectedIdx === 8) {
                    $('#addWeekDay').attr('disabled', true);
                } else {
                    $('#addWeekDay').attr('disabled', false);
                }

                renderedRow += `<td>${currentRowElements.eq(index).val() || '-'}</td>`;
            } else {
                renderedRow += `<td class="text-center">${currentRowElements.eq(index).val() || '-'}</td>`;
            }
        });

        renderedRow += '<td><div class="actions"><button type="button" class="btn btn-primary btn-sm edit action"><i class="fas fa-pencil-alt"></i></button><button type="button" class="btn btn-primary btn-sm delete action"><i class="fas fa-trash-alt"></i></button></div></td>';
        
        currentRow.html(renderedRow);
        
    });

    // Edit row
    $('.table-operation').on('click', '.edit', function () {
        $('#addWeekDay').attr('disabled', true);

        var currentRow = $(this).closest('tr');
        var currentRowElements = currentRow.find('td');
        var renderedRow = '';

        // var weekDays = renderWeekDays(currentRowElements.eq(0).html());
        var weekDays = renderWeekDays(currentRow.data('row'));

        console.log(currentRow.data('row'));

        var renderedRow = `<td>${weekDays}</td><td><input type="text" class="form-control text-center time" value=${currentRowElements.eq(1).html()}></td><td><input type="text" class="form-control text-center time" value=${currentRowElements.eq(2).html()}></td><td><input type="text" class="form-control text-center time" value=${currentRowElements.eq(3).html()}></td><td><input type="text" class="form-control text-center time" value=${currentRowElements.eq(4).html()}></td><td><div class="actions"><button type="button" class="btn btn-primary btn-sm save action"><i class="fas fa-check"></i></button><button type="button" class="btn btn-primary btn-sm delete action" disabled><i class="fas fa-trash-alt"></i></button></div></td>`;

        currentRow.html(renderedRow);
        
        refreshSelect();
        refreshMask();        
    });

    // Delete row
    $('.table-operation').on('click', '.delete', function () {
        var rowElement = $(this).closest('tr');
        
        rowElement.remove();
        
        $(`.selected-week-days input[data-row="${rowElement.data('row')}"]`).remove();

        if ($(`.selected-week-days input`).length === 0) {
            count = 0;
        }

    });
});

function renderWeekDays(row) {
    var allOptionsWeekDay = ['Selecione', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado', 'Domingo', 'Todos os dias'];
    var options = '';
    var status = '';
    var optionAllDays = '';
    
    allOptionsWeekDay.forEach((option, index) => {
        var selectedWeekDay = $(`.selected-week-days input[data-week-day-index="${index}"]`).data();

        console.log(selectedWeekDay);

        if (index === 0) { 
            status = 'disabled selected'; 
        } else if (selectedWeekDay) {
            status = 'disabled';
            
            if (selectedWeekDay.row === row) {
                status = 'selected';
            } 
            
            if (selectedWeekDay.row != row && selectedWeekDay.row >= 1) {
                optionAllDays = 'disabled';                
            }
        } else {
            status = '';

            if (index === 8) {
                status = optionAllDays;
            }
        }
        
        options += `<option data-index="${index}" ${status}>${option}</option>`;
    });
    
    return `<select name="weekDay" class="form-control selectric" name="weekDay">${options}</select>`;
}

function refreshSelect() {
    // Selectric
    $('.table-operation select[name="weekDay"]').selectric();
}

function refreshMask() {
    // Masks
    $('.table-operation input.time').mask('00:00', { placeholder: '-- : --' });
}
// Operation Validation
// 



// function isValidOperation(table) {
//     var weekDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
//     var validate = true;
    
//     var validateOperation = {};
    
//     weekDays.forEach(day => {
//         var dayOpen1 = table.find(`input[name="${day}Open1"]`).eq(0).val();
//         var dayClose1 = table.find(`input[name="${day}Close1"]`).eq(0).val();
//         var dayOpen2 = table.find(`input[name="${day}Open2"]`).eq(0).val();
//         var dayClose2 = table.find(`input[name="${day}Close2"]`).eq(0).val();
//         var validate = false;
//         var validate1 = true;
//         var validate2 = true;
        
//         // var validate1 = !!dayOpen1 && !dayClose1 || !dayOpen1 && !!dayClose1;
//         // var validate2 = !!dayOpen2 && !dayClose2 || !dayOpen2 && !!dayClose2;

//         if ((!dayOpen1 && !dayClose1) && (!dayOpen2 && !dayClose2)) {
//             validate1 = false;
//             validate2 = false;
//         } else if (!!dayOpen1 && !dayClose1 || !dayOpen1 && !!dayClose1) {
//             validate1 = false;
//             validate2 = true
//         } else if (!!dayOpen2 && !dayClose2 || !dayOpen2 && !!dayClose2) {
//             validate2 = false;
//         }

//         // if (!!dayOpen2.val() && !dayClose2.val() || !dayOpen2.val() && !!dayClose2.val()) {
//         //     validate2 = false;
//         // }

//         validateOperation[day] = {
//             validate1,
//             validate2
//         };       
//     });

//     // var hasOneValidateRequired = false;

//     // Object.keys(validateOperation).forEach(day => {
    
//     //     if(validateOperation[day].validateRequired) hasOneValidateRequired = true;
//     // });

//     // if (validate && !hasOneValidateRequired) {
//     //     validate = false
//     // }
    
//     console.log({ weekDays: validateOperation, validate });   
    
//     return {
//         weekDays: validateOperation,
//         validate
//     };
// }

function isValidOperation(table) {
    var weekDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    var validate = true;
    
    var validateOperation = {};
    
    weekDays.forEach(day => {
        var dayOpenRequired = table.find(`input[name="${day}Open1"]`).eq(0).val();
        var dayCloseRequired = table.find(`input[name="${day}Close1"]`).eq(0).val();
        var dayOpenOptional = table.find(`input[name="${day}Open2"]`).eq(0).val();
        var dayCloseOptional = table.find(`input[name="${day}Close2"]`).eq(0).val();
        
        var validateRequired = !!dayOpenRequired && !!dayCloseRequired || !dayOpenRequired && !dayCloseRequired;
        var validateOptional = !!dayOpenOptional && !!dayCloseOptional || !dayOpenOptional && !dayCloseOptional;

        if (!!dayOpenRequired && !dayCloseRequired || !dayOpenRequired && !!dayCloseRequired) {
            validate = false;
        }

        if (!!dayOpenOptional && !dayCloseOptional || !dayOpenOptional && !!dayCloseOptional) {
            validate = false;
        }

        if (!dayOpenRequired && !dayCloseRequired && !dayOpenOptional && !dayCloseOptional) {
            validate = false;
        } else {
            validate = true;
        }

        validateOperation[day] = {
            validateRequired,
            validateOptional
        };       
    });

    // var hasOneValidateRequired = false;

    Object.keys(validateOperation).forEach(day => {
    
        if(validateOperation[day].validateRequired) validate = true;
    });

    // if (validate && !hasOneValidateRequired) {
    //     validate = false
    // }
    
    console.log(
        {
            days: validateOperation,
            validate
        });   
    
    return {
        days: validateOperation,
        validate
    };
}


// lembrar de usar a lib para validacao no back - Respect/Validation
