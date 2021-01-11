$(function () {
    // $('#restaurantAddOperation').fireModal({
    //     title: 'Adicionar Horários de Funcionamento',
    //     body: $('#modalAddOperation'),
    //     buttons: [
    //         {
    //             text: 'Voltar',
    //             id: 'backAddOperation',
    //             class: 'btn btn-secondary',
    //             handler: function(currentModal) {
    //                 $.destroyModal(currentModal);
    //             }
    //         },
    //         {
    //             text: 'Salvar',
    //             id: 'saveAddOperation',
    //             class: 'btn btn-primary',
    //             handler: function(currentModal) {               
    //                 $.destroyModal(currentModal);
    //             }
    //         }
    //     ],
    //     size: 'modal-lg',
    //     footerClass: 'justify-content-between',
    // });

    // Timepicker in inpus of time
    // $('.table-operation').on('focus', 'input.time', function (e) {
    //     $(this).timepicker({
    //         icons: { 
    //             up: 'fas fa-chevron-up',
    //             down: 'fas fa-chevron-down'
    //         },
    //         showMeridian: false,
    //         defaultTime: '00:00'
    //     });    
    // });

    $('table.restaurant-social-media').on('selectric-change', 'select[name="weekDay"]', function(event, element, selectric) {      
        $(element).attr('data-day-selected', selectric.state.selectedIdx);     
    });

    // Add row
    var count = 0;
    var editing = false;

    $('#addSocialMedia').on('click', function () {
        if (editing) return;

        editing = true;

        if ($('.no-operation').html()) $('.no-operation').remove();
        
        $('#addSocialMedia').attr('disabled', true);
        $('#saveSocialMedia').attr('disabled', true);
        
        count++;

        var weekDays = renderWeekDays(count);

        var newRow = $(`<tr data-row="${count}"><td>${weekDays}</td><td><input type="text" class="form-control"></td><td><div class="actions"><button type="button" class="btn btn-primary btn-sm save action"><i class="fas fa-check"></i></button><button type="button" class="btn btn-primary btn-sm delete action"><i class="fas fa-trash-alt"></i></button></div></td></tr>`).appendTo('table.restaurant-social-media');

        disableTableInputs(newRow);

        refreshSelect();
        refreshMask();  
    });

    $('#addSocialMedia').click();
    
    // Save row
    $('.table-operation').on('click', '.save', function () {
        var currentRow = $(this).closest('tr');
        var currentRowElements = currentRow.find('input.time, select');
        var renderedRow = '';

        var validation = validateOperation(currentRowElements);

        if (!validation.validateRow) {
            validateOperationStyle(validation);
            return;
        }

        editing = false;

        $('#addWeekDay').attr('disabled', false);
        $('#saveAddOperation').attr('disabled', false);

        currentRowElements.each( function (index, element) {           
            if (index === 0) {
                // Verify if the row exist in div hidden
                if ($(`.selected-week-days input[data-row="${currentRow.data('row')}"]`).data('row')) {
                    $(`.selected-week-days input[data-row="${currentRow.data('row')}"]`).attr('data-day-index', currentRowElements.eq(index).data().selectric.state.selectedIdx);                    
                    $(`.selected-week-days input[data-row="${currentRow.data('row')}"]`).attr('data-week-day', currentRowElements.eq(index).val());                    
                } else {
                    $('.selected-week-days').append(`<input type="hidden" data-day-index="${currentRowElements.eq(index).data().selectric.state.selectedIdx}" data-week-day="${currentRowElements.eq(index).val()}" data-row="${currentRow.data('row')}">`);
                }

                if (currentRowElements.eq(index).data().selectric.state.selectedIdx === 8) {
                    $('#addWeekDay').attr('disabled', true);
                } else {
                    $('#addWeekDay').attr('disabled', false);
                }

                renderedRow += `<td>${currentRowElements.eq(index).val() || '-'}</td>`;
            } else {
                if (index === 1) {
                    $(`.selected-week-days input[data-row="${currentRow.data('row')}"]`).attr('data-open1', currentRowElements.eq(index).val());
                } else if (index === 2) {
                    $(`.selected-week-days input[data-row="${currentRow.data('row')}"]`).attr('data-close1', currentRowElements.eq(index).val());
                } else if (index === 3) {
                    $(`.selected-week-days input[data-row="${currentRow.data('row')}"]`).attr('data-open2', currentRowElements.eq(index).val());
                } else {
                    $(`.selected-week-days input[data-row="${currentRow.data('row')}"]`).attr('data-close2', currentRowElements.eq(index).val());
                }

                renderedRow += `<td class="text-center">${currentRowElements.eq(index).val() || '-'}</td>`;
            }
        });

        renderedRow += '<td><div class="actions"><button type="button" class="btn btn-primary btn-sm edit action"><i class="fas fa-pencil-alt"></i></button><button type="button" class="btn btn-primary btn-sm delete action"><i class="fas fa-trash-alt"></i></button></div></td>';
        
        currentRow.html(renderedRow);   

        enableTableInputs();
    });

    // Edit row
    $('.table-operation').on('click', '.edit', function () {
        if (editing) return;
        editing = true;

        $('#addWeekDay').attr('disabled', true);
        $('#saveAddOperation').attr('disabled', true);

        var currentRow = $(this).closest('tr');
        var currentRowElements = currentRow.find('td');
        var renderedRow = '';

        // var weekDays = renderWeekDays(currentRowElements.eq(0).html());
        var weekDays = renderWeekDays(currentRow.data('row'));

        var renderedRow = `<td>${weekDays}</td><td><input type="text" name="open1" class="form-control text-center time" value=${currentRowElements.eq(1).html()}></td><td><input type="text" name="close1" class="form-control text-center time" value=${currentRowElements.eq(2).html()}></td><td><input type="text"  name="open2" class="form-control text-center time" value=${currentRowElements.eq(3).html()}></td><td><input type="text"  name="close2" class="form-control text-center time" value=${currentRowElements.eq(4).html()}></td><td><div class="actions"><button type="button" class="btn btn-primary btn-sm save action"><i class="fas fa-check"></i></button><button type="button" class="btn btn-primary btn-sm delete action" disabled><i class="fas fa-trash-alt"></i></button></div></td>`;

        currentRow.html(renderedRow);

        disableTableInputs(currentRow);
        refreshSelect();
        refreshMask();        
    });

    // Delete row
    $('.table-operation').on('click', '.delete', function () {
        var rowElement = $(this).closest('tr');
        
        if (editing && !rowElement.find('td:first-child').children().eq(0).length) return;

        rowElement = $(this).closest('tr');
        
        rowElement.remove();
        
        editing = false;
        
        $(`.selected-week-days input[data-row="${rowElement.data('row')}"]`).remove();

        if ($(`.selected-week-days input`).length === 0) count = 0;
        
        $('#addWeekDay').attr('disabled', false);
        
        if (!$('.table-operation tbody tr').length) {
            // $('#addWeekDay').attr('disabled', false);
            $('#saveAddOperation').attr('disabled', true);

            $('.table-operation tbody').append(`<tr row=${count} class="text-center no-operation"><td colspan="6">Nenhum Horário Adicionado!</td></tr>`);
        }

        enableTableInputs();
    });    
});

function renderWeekDays(row) {
    var allOptionsWeekDay = ['Selecione', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado', 'Domingo', 'Todos os dias'];
    var options = '';
    var status = '';
    var optionAllDays = '';
    
    allOptionsWeekDay.forEach((option, index) => {
        var selectedWeekDay = $(`.selected-social-medias input[data-day-index="${index}"]`).data();

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
    $('table.restaurant-social-media select[name="weekDay"]').selectric();
}

function refreshMask() {
    // Masks
    $('.table-operation input.time').mask('00:00', { 
        placeholder: '-- : --', 
        reverse: true 
    });
}
// Operation Validation
function validateOperation(rowElements) {
    var weekDay = rowElements.eq(0).val();
    var dayOpen1 = rowElements.eq(1).val();
    var dayClose1 = rowElements.eq(2).val();
    var dayOpen2 = rowElements.eq(3).val();
    var dayClose2 = rowElements.eq(4).val();

    var validateWeekDay = !!weekDay ?? false;
    var validateSchedule1 = !!dayOpen1 && !!dayClose1;
    var validateSchedule2 = !!validateSchedule1 && (!!dayOpen2 && !!dayClose2 || !dayOpen2 && !dayClose2 );

    if (dayOpen1.length === 4) {
        dayOpen1 = `0${dayOpen1}`;
    }
    
    if (dayClose1.length === 4) {
        dayClose1 = `0${dayClose1}`;
    }
    
    if (dayOpen2.length === 4) {
        dayOpen2 = `0${dayOpen2}`;
    }
    
    if (dayClose2.length === 4) {
        dayClose2 = `0${dayClose2}`;
    }

    // Parse schedules to mins and validate range of schedules
    if (validateSchedule1) {
        // console.log(dayOpen1);
        // console.log(dayClose1);
        // console.log(dayOpen2);
        // console.log(dayClose2);

        if (dayOpen1 >= dayClose1) {
            validateSchedule1 = false;
            validateSchedule2 = false;
        }

        if (dayOpen1 && dayClose2) {
            if (dayOpen2 >= dayClose2) {
                validateSchedule2 = false;
            } else if (dayOpen2 <= dayOpen1 || dayOpen2 <= dayClose1 ) {
                validateSchedule2 = false;
            }

        }
    }

    var validateRow = !!validateWeekDay && !!validateSchedule1 && validateSchedule2;
    
    validation = {
        validateWeekDay,
        validateSchedule1,
        validateSchedule2,
        validateRow
    }
    
    console.log(validation);

    return validation;
}    

function validateOperationStyle(validation) {
    if (!validation.validateWeekDay) {
        $('.table-operation td .selectric-wrapper .selectric').css('border-color', '#fa3734');
        $('.table-operation td .selectric-wrapper .selectric').css('border-color', '#fa3734');
    } else {
        $('.table-operation td .selectric-wrapper .selectric').css('border-color', '#ced4da');
        $('.table-operation td .selectric-wrapper .selectric').css('border-color', '#ced4da');
    }

    if (!validation.validateSchedule1) {
        $(`.table-operation input[name="open1"]`).css('border-color', '#fa3734');
        $(`.table-operation input[name="close1"]`).css('border-color', '#fa3734');
    } else {
        $(`.table-operation input[name="open1"]`).css('border-color', '#ced4da');
        $(`.table-operation input[name="close1"]`).css('border-color', '#ced4da');
    }

    if (!validation.validateSchedule2) {
        $(`.table-operation input[name="open2"]`).css('border-color', '#fa3734');
        $(`.table-operation input[name="close2"]`).css('border-color', '#fa3734');
    } else {
        $(`.table-operation input[name="open2"]`).css('border-color', '#ced4da');
        $(`.table-operation input[name="close2"]`).css('border-color', '#ced4da');
    }
}
// lembrar de usar a lib para validacao no back - Respect/Validation

function disableTableInputs(currentRow) {
    var elementRow = $('.table-operation tbody').children('tr');

    elementRow.each(function (index, row) {
         if (!$(row).is(currentRow)) {
            $(row).last().find('button').each(function (index, button) {
                $(button).attr('disabled', true);
            }) ;
         }
       
    });
}

function enableTableInputs() {
    var elementRow = $('.table-operation tbody').children('tr');   

    elementRow.find('button').attr('disabled', false);
}