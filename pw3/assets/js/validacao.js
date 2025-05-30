// js/validacao.js

$(document).ready(function () {
  // Máscara para telefone
  $("#telefone").mask("(00) 00000-0000");

  // Máscara para valor monetário
  $("#valor").mask("#.##0,00", { reverse: true });

  // Validação do formulário
  $("#f").submit(function (e) {
    var isValid = true;

    // Limpa mensagens de erro anteriores
    $(".is-invalid").removeClass("is-invalid");

    // Validação do nome
    if ($("#nome_cliente").val().trim() === "") {
      $("#nome_cliente").addClass("is-invalid");
      alert("Por favor, preencha o nome");
      isValid = false;
    }

    // Validação do email
    var email = $("#email").val().trim();
    if (email !== "") {
      var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        $("#email").addClass("is-invalid");
        alert("Por favor, insira um email válido");
        isValid = false;
      }
    }

    // Validação do telefone
    var telefone = $("#telefone").val().replace(/\D/g, "");
    if (telefone !== "" && telefone.length < 10) {
      $("#telefone").addClass("is-invalid");
      alert("Por favor, insira um telefone válido");
      isValid = false;
    }

    // Validação do valor
    var valor = $("#valor").val().trim();
    if (
      valor !== "" &&
      isNaN(valor.replace(/[R$.\s]/g, "").replace(",", "."))
    ) {
      $("#valor").addClass("is-invalid");
      alert("Por favor, insira um valor válido");
      isValid = false;
    }

    // Previne o envio do formulário se houver erros
    if (!isValid) {
      e.preventDefault();
    }
  });

  // Remove classe de inválido ao digitar
  $(".form-control").on("input", function () {
    $(this).removeClass("is-invalid");
  });
});
