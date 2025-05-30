// Código de validação de formulários
$(document).ready(function () {
  // Validação do formulário
  $("#f")
    .off("submit")
    .on("submit", function (e) {
      // ...existing validation code...
    });

  // Máscaras
  $("#telefone").mask("(00) 00000-0000");
  $("#valor").mask("R$ #.##0,00", { reverse: true });
  $("#controle").mask("0000000000");
});

// filepath: /c:/xampp/htdocs/Carlos/Crud4/assets/js/image-handler.js
// Código de manipulação de imagens

// filepath: /c:/xampp/htdocs/Carlos/Crud4/assets/js/masks.js
// Código das máscaras de input
