document.addEventListener("DOMContentLoaded", function () {
  console.log("Script de preview carregado"); // Para debug

  // Elementos do DOM
  const inputImagens = document.getElementById("imagens");
  const previewContainer = document.getElementById("preview");
  const imageCount = document.getElementById("imageCount");

  if (!inputImagens || !previewContainer || !imageCount) {
    console.error("Elementos necessários não encontrados");
    return;
  }

  // Listener para mudanças no input de arquivo
  inputImagens.addEventListener("change", function (e) {
    console.log("Input de imagem alterado"); // Para debug

    // Limpa o preview anterior
    previewContainer.innerHTML = "";

    // Verifica se há arquivos selecionados
    if (this.files.length > 5) {
      alert("Por favor, selecione no máximo 5 imagens.");
      this.value = "";
      updateCount(0);
      return;
    }

    // Atualiza o contador
    updateCount(this.files.length);

    // Cria preview para cada arquivo
    Array.from(this.files).forEach((file) => {
      if (file.size > 2 * 1024 * 1024) {
        alert(`O arquivo ${file.name} é maior que 2MB e será ignorado.`);
        return;
      }

      if (!file.type.startsWith("image/")) {
        alert(`O arquivo ${file.name} não é uma imagem válida.`);
        return;
      }

      const reader = new FileReader();

      reader.onload = function (e) {
        const previewCard = document.createElement("div");
        previewCard.className = "col-md-4 mb-3";
        previewCard.innerHTML = `
                  <div class="card">
                      <img src="${e.target.result}" 
                           class="card-img-top preview-image" 
                           style="height: 200px; object-fit: cover; cursor: pointer"
                           onclick="viewImage('${e.target.result}')"
                           alt="Preview">
                      <div class="card-footer p-2 text-center">
                          <button type="button" class="btn btn-sm btn-danger" onclick="removePreview(this)">
                              <i class="fas fa-trash"></i> Remover
                          </button>
                      </div>
                  </div>
              `;
        previewContainer.appendChild(previewCard);
      };

      reader.readAsDataURL(file);
    });
  });

  // Função para atualizar o contador
  function updateCount(count) {
    imageCount.textContent = `${count}/5`;
    imageCount.classList.toggle("badge-primary", count > 0);
    imageCount.classList.toggle("badge-secondary", count === 0);
  }
});

// Funções globais
function viewImage(src) {
  const modalImage = document.getElementById("modalImage");
  if (modalImage) {
    modalImage.src = src;
    $("#imageModal").modal("show");
  }
}

function removePreview(button) {
  const card = button.closest(".col-md-4");
  if (card) {
    card.remove();
    const currentCount = document.querySelectorAll("#preview .card").length;
    const imageCount = document.getElementById("imageCount");
    if (imageCount) {
      imageCount.textContent = `${currentCount}/5`;
      imageCount.classList.toggle("badge-primary", currentCount > 0);
      imageCount.classList.toggle("badge-secondary", currentCount === 0);
    }
  }
}
