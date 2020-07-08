$('#addOperation').fireModal({
  title: 'Adicionar Horários de Funcionamento',
  body: $('#modalAddOperation'),
  buttons: [
    {
      text: 'Cancelar',
      id: 'cancelAddOperation',
      class: 'btn btn-secondary',
      handler: function(current_modal) {
        $.destroyModal(current_modal);
      }
      
    },

    {
      text: 'Salvar',
      id: 'saveAddOperation',
      class: 'btn btn-primary',
      handler: function(current_modal) {
        $.destroyModal(current_modal);
      }
    }
  ],
  size: 'modal-lg',
  footerClass: 'justify-content-between'
});

// Masks
$('.table-operation td input').mask("00:00", {placeholder: "__ : __"});