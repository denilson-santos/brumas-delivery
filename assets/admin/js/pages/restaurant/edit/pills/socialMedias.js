$(function () {
    $('table.restaurant-social-media').on('selectric-change', 'select[name="socialMedia"]', function(event, element, selectric) {      
        var socialMediaSelected = selectric.state.selectedIdx;

        $(element).attr('data-social-media-selected', socialMediaSelected);     

        var currentRow = $(this).closest('tr');
        var currentRowElements = currentRow.find('input[name="linkOrPhone"]');

        if (socialMediaSelected == 1) {
            $(currentRowElements[0]).val('facebook.com.br/');
        } else if (socialMediaSelected == 2) {
            $(currentRowElements[0]).val('instagram.com.br/');
        } else if (socialMediaSelected == 3) {
            $(currentRowElements[0]).val('twitter.com.br/');
        }
    });

    // Add row
    var count = 0;
    var editing = false;

    $('#addSocialMedia').on('click', function () {
        if (editing) return;

        editing = true;

        if ($('.no-social-media').html()) $('.no-social-media').remove();
        
        $('#addSocialMedia').attr('disabled', true);
        $('#saveSocialMedia').attr('disabled', true);
        
        count++;

        var socialMedias = renderSocialMedias(count);

        var newRow = $(`<tr data-row="${count}"><td>${socialMedias}</td><td><input name="linkOrPhone" type="text" class="form-control" placeholder="Link ou celular"></td><td><div class="actions"><button type="button" class="btn btn-primary btn-sm save action"><i class="fas fa-check"></i></button><button type="button" class="btn btn-primary btn-sm delete action"><i class="fas fa-trash-alt"></i></button></div></td></tr>`).appendTo('table.restaurant-social-media');

        disableTableInputs(newRow);

        refreshSelect();
        refreshMask();  
    });

    $('#addSocialMedia').click();
    
    // Save row
    $('table.restaurant-social-media').on('click', '.save', function () {
        var currentRow = $(this).closest('tr');
        var currentRowElements = currentRow.find('input[name="linkOrPhone"], select');
        var renderedRow = '';

        // var validation = validateOperation(currentRowElements);

        // if (!validation.validateRow) {
        //     validateOperationStyle(validation);
        //     return;
        // }

        editing = false;

        $('#addSocialMedia').attr('disabled', false);
        $('#saveSocialMedia').attr('disabled', false);

        currentRowElements.each( function (index, element) {  
            var socialMediaIcon = '';

            if (index === 0) {
                // Verify if the row exist in div hidden
                if ($(`.selected-social-medias input[data-row="${currentRow.data('row')}"]`).data('row')) {
                    $(`.selected-social-medias input[data-row="${currentRow.data('row')}"]`).attr('data-social-media-index', currentRowElements.eq(index).data().selectric.state.selectedIdx);                    
                    $(`.selected-social-medias input[data-row="${currentRow.data('row')}"]`).attr('data-social-media', currentRowElements.eq(index).val());                    
                } else {
                    $('.selected-social-medias').append(`<input type="hidden" data-social-media-index="${currentRowElements.eq(index).data().selectric.state.selectedIdx}" data-social-media="${currentRowElements.eq(index).val()}" data-row="${currentRow.data('row')}">`);
                }
                
                if (currentRowElements.eq(index).data().selectric.state.selectedIdx == 1) {
                    socialMediaIcon = '<i class="fab fa-facebook-f mr-2"></i>';
                } else if (currentRowElements.eq(index).data().selectric.state.selectedIdx == 2) {
                    socialMediaIcon = '<i class="fab fa-instagram mr-2"></i>';
                } else if (currentRowElements.eq(index).data().selectric.state.selectedIdx == 3) {
                    socialMediaIcon = '<i class="fab fa-twitter mr-2"></i>';
                } else if (currentRowElements.eq(index).data().selectric.state.selectedIdx == 4) {
                    socialMediaIcon = '<i class="fab fa-whatsapp mr-2"></i>';
                }

                renderedRow += `<td>${socialMediaIcon}${currentRowElements.eq(index).val() || '-'}</td>`;
            } else if (index === 1) {
                $(`.selected-social-medias input[data-row="${currentRow.data('row')}"]`).attr('data-link-or-phone', currentRowElements.eq(index).val());

                socialMediaIcon = '<i class="fas fa-link mr-2"></i>';

                renderedRow += `<td class="link-or-phone"><a href="#">${socialMediaIcon}${currentRowElements.eq(index).val() || '-'}</a></td>`;
            }

        });

        renderedRow += '<td><div class="actions"><button type="button" class="btn btn-primary btn-sm edit action"><i class="fas fa-pencil-alt"></i></button><button type="button" class="btn btn-primary btn-sm delete action"><i class="fas fa-trash-alt"></i></button></div></td>';
        
        currentRow.html(renderedRow);   

        enableTableInputs();
    });

    // Edit row
    $('table.restaurant-social-media').on('click', '.edit', function () {
        if (editing) return;
        editing = true;

        $('#addSocialMedia').attr('disabled', true);
        $('#saveSocialMedia').attr('disabled', true);

        var currentRow = $(this).closest('tr');
        var currentRowElements = currentRow.find('td');
        var renderedRow = '';

        var socialMedias = renderSocialMedias(currentRow.data('row'));

        var renderedRow = `<td>${socialMedias}</td><td><input type="text" name="linkOrPhone" class="form-control" value="${$(currentRowElements.eq(1)).text()}" placeholder="Link ou celular"></td><td><div class="actions"><button type="button" class="btn btn-primary btn-sm save action"><i class="fas fa-check"></i></button><button type="button" class="btn btn-primary btn-sm delete action" disabled><i class="fas fa-trash-alt"></i></button></div></td>`;

        currentRow.html(renderedRow);

        disableTableInputs(currentRow);
        refreshSelect();
        refreshMask();        
    });

    // Delete row
    $('table.restaurant-social-media').on('click', '.delete', function () {
        var rowElement = $(this).closest('tr');
        
        if (editing && !rowElement.find('td:first-child').children().eq(0).length) return;

        rowElement = $(this).closest('tr');
        
        rowElement.remove();
        
        editing = false;
        
        $(`.selected-social-medias input[data-row="${rowElement.data('row')}"]`).remove();

        if ($(`.selected-social-medias input`).length === 0) count = 0;
        
        $('#addSocialMedia').attr('disabled', false);
        
        if (!$('table.restaurant-social-media tbody tr').length) {
            // $('#addWeekDay').attr('disabled', false);
            $('#saveSocialMedia').attr('disabled', true);

            $('table.restaurant-social-media tbody').append(`<tr row=${count} class="text-center no-social-media"><td colspan="3">Nenhuma Mídia Social Adicionada!</td></tr>`);
        }

        enableTableInputs();
    });    
});

function renderSocialMedias(row) {
    var allOptionsSocialMedias = ['Selecione', 'Facebook', 'Instagram', 'Twitter', 'Whatapp'];
    var options = '';
    var status = '';
    
    allOptionsSocialMedias.forEach((option, index) => {
        var selectedSocialMedia = $(`.selected-social-medias input[data-social-media-index="${index}"]`).data();

        if (index === 0) { 
            status = 'disabled selected'; 
        } else if (selectedSocialMedia) {
            status = 'disabled';
            
            if (selectedSocialMedia.row === row) {
                status = 'selected';
            } 
        } else {
            status = '';
        }
        
        options += `<option data-index="${index}" ${status}>${option}</option>`;
    });
    
    return `<select name="socialMedia" class="form-control selectric">${options}</select>`;
}

function refreshSelect() {
    // Selectric
    $('table.restaurant-social-media select[name="socialMedia"]').selectric();
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

function disableTableInputs(currentRow) {
    var elementRow = $('table.restaurant-social-media tbody').children('tr');

    elementRow.each(function (index, row) {
        if (!$(row).is(currentRow)) {
            $(row).last().find('button').each(function (index, button) {
                $(button).attr('disabled', true);
            }) ;
        }       
    });
}

function enableTableInputs() {
    var elementRow = $('table.restaurant-social-media tbody').children('tr');   

    elementRow.find('button').attr('disabled', false);
}