# DriveX Motors - Sistema de Gerenciamento de Loja de Veículos

Este é um sistema web desenvolvido em PHP para gerenciar uma loja de venda de veículos. Ele permite o cadastro, consulta, atualização e exclusão de veículos, além de gerenciamento de usuários e upload de imagens dos veículos.

## Funcionalidades Principais

- **Autenticação de Usuários:**
  - Login de usuários.
  - Cadastro de novos usuários.
  - Área restrita para usuários logados.
- **Gerenciamento de Veículos:**
  - Cadastro de novos veículos (marca, modelo, ano, placa, cor, preço).
  - Upload de múltiplas imagens por veículo.
  - Consulta de veículos cadastrados com filtros (marca/modelo, placa).
  - Visualização detalhada dos veículos, incluindo galeria de imagens.
  - Atualização dos dados dos veículos.
  - Exclusão de veículos (incluindo suas imagens associadas).
- **Interface Amigável:**
  - Utilização do Bootstrap para um design responsivo.
  - Alertas e modais para feedback ao usuário.
  - Máscaras de entrada para campos como telefone e valor.

## Tecnologias Utilizadas

- **Backend:** PHP
- **Frontend:** HTML, CSS, JavaScript, jQuery
- **Framework CSS:** Bootstrap 4.6
- **Banco de Dados:** MySQL (gerenciado via PDO)
- **Servidor Web:** Apache (geralmente via XAMPP, WAMP, etc.)

## Como Executar o Projeto

### Pré-requisitos

- Servidor web local com PHP e MySQL (XAMPP, WAMP, MAMP, Laragon, etc.).
- Navegador web moderno.

### Configuração

1.  **Clone ou Baixe o Projeto:**
    Coloque os arquivos do projeto no diretório `htdocs` (ou similar) do seu servidor web. Ex: `c:\xampp\htdocs\Carlos\pw3\`.

2.  **Banco de Dados:**

    - O script de conexão está em `processos/conexao.php`. As credenciais padrão são:
      - Host: `127.0.0.1:3306`
      - Usuário: `root`
      - Senha: `(vazio)`
      - Nome do Banco: `veiculos`
    - O arquivo `processos/conexao.php` também tenta criar a tabela `usuarios` (deveria ser `tb_usuario` para consistência) se ela não existir.
    - Para criar as tabelas principais (`tb_veiculo`, `tb_imagem_veiculo`, `tb_usuario`), você pode precisar executar um script SQL manualmente ou adaptar um dos scripts de criação de banco de dados antigos (`config/recreate_db.php` ou `processos/recreate_db.php` - **CUIDADO: eles apagam o banco `db_agenda`**). Recomenda-se criar um script SQL específico para o banco `veiculos` com as tabelas:
      - `tb_usuario (id_usuario, nome, email, senha, criado_em)`
      - `tb_veiculo (id_veiculo, id_usuario, marca, modelo, ano, placa, cor, preco, titulo, descricao, criado_em)`
      - `tb_imagem_veiculo (id_imagem, id_veiculo, caminho, criado_em)`

3.  **Pasta de Uploads:**
    - Crie uma pasta chamada `uploads` na raiz do projeto (`c:\xampp\htdocs\Carlos\pw3\uploads\`) se ela não existir.
    - Certifique-se de que o servidor web tenha permissão de escrita nesta pasta.

### Acesso

1.  Inicie seu servidor Apache e MySQL.
2.  Abra seu navegador e acesse: `http://localhost/Carlos/pw3/`
3.  Para testar as funcionalidades de usuário, crie uma conta através da página de cadastro de usuário e faça login.

## Estrutura de Pastas (Principais)

```
pw3/
├── assets/
│   ├── css/              # Arquivos CSS customizados
│   └── js/               # Arquivos JavaScript customizados
│   └── img/              # Imagens estáticas da interface
├── config/               # Arquivos de configuração (alguns podem ser legados)
├── includes/             # Partes reutilizáveis da interface (header, footer)
├── processos/            # Scripts PHP para lógica de backend (conexão, CRUD)
├── uploads/              # Pasta para imagens dos veículos (requer criação manual e permissões)
├── area_usuario.php      # Painel do usuário logado
├── atualizacao.php       # Página para editar veículos
├── cadastro.php          # Página para cadastrar novos veículos
├── cadastro_usuario.php  # Página para registrar novos usuários
├── consulta.php          # Página para listar e filtrar veículos
├── exclusao.php          # Página de confirmação para excluir veículos
├── index.php             # Página inicial, vitrine de veículos
├── login.php             # Página de login
├── logout.php            # Script para encerrar a sessão do usuário
└── README.md             # Este arquivo
```

## Possíveis Melhorias

- Refatorar scripts legados na pasta `config/` e `processos/` que se referem a `db_agenda` ou `imoveis`.
- Criar um script SQL dedicado para a criação inicial do banco de dados `veiculos` e suas tabelas.
- Melhorar a segurança (ex: proteção contra XSS mais robusta, CSRF tokens).
- Implementar paginação na tela de consulta de veículos.
- Adicionar mais filtros de busca (ex: por ano, por preço).
- Melhorar a interface de gerenciamento de imagens na atualização de veículos.
- Implementar funcionalidade de "esqueci minha senha".
