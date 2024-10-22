<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("location: index.php");
    exit();
}

require_once 'includes/config.php';

$mensagem_sucesso = "";
$mensagem_erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $quantidade = $_POST['quantidade'];

    // Valida se a quantidade é um número
    if (!is_numeric($quantidade) || $quantidade < 0) {
        $mensagem_erro = "Quantidade inválida.";
    } else {
        $sql_verifica = "SELECT * FROM produtos WHERE nome = ?";
        $stmt_verifica = $conn->prepare($sql_verifica);
        $stmt_verifica->bind_param('s', $nome);
        $stmt_verifica->execute();
        $resultado = $stmt_verifica->get_result();

        if ($resultado->num_rows > 0) {
            $mensagem_erro = "Este produto já está cadastrado.";
        } else {
$imagem_tmp= $_FILES['imagem']['tmp_name'];
$imagem_nome= basename($_FILES['imagem']['tmp_name']);
$diretorio_upload = '/uploads';
$caminho_imagem= $diretorio_upload . $imagem_nome;

if(!is_dir($diretorio_upload)){
mkdir($diretorio_upload, 0777,true);
}


if(move_uploaded_file($imagem_tmp, $caminho_imagem)){
    $sql = "INSERT INTO produtos (nome, descricao, quantidade) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $nome, $descricao, $quantidade);

    if ($stmt->execute()) {
        $_SESSION['mensagem_sucesso'] = "Cadastro realizado com sucesso";
        header("Location: cadastro-produto.php");
        exit();
    } else {
        $mensagem_erro = "Erro ao cadastrar: " . $coon->error;  
    }

}  else {
       $mensagem_erro = "Erro ao fazer upload da imagem.";
            }
            $stmt->close(); // Fechar o stmt após uso
        }
        $stmt_verifica->close(); // Fechar o stmt de verificação
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/cadastro.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="cadastro de produtos.css">
    <title>Cadastro de Produtos</title>
</head>
<body>
    <div class="cadastro">
        <form action="" method="POST">
            <h2>Cadastro de Produtos</h2>
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required><br>
            <label for="descricao">Descrição:</label>
            <input type="text" id="descricao" name="descricao" required><br>
            <label for="quantidade">Quantidade:</label>
            <input type="number" id="quantidade" name="quantidade" required><br>
            <input type="file" id="imagem> name="imagem" accept="image/*"><br>
            <?php if ($mensagem_sucesso): ?>
                <p><?php echo $mensagem_sucesso; ?></p>
            <?php endif; ?> 
            <?php if ($mensagem_erro): ?>
                <p><?php echo $mensagem_erro; ?></p>
            <?php endif; ?>
            <input type="submit" value="Cadastrar">
            <a href="dashboard.php">Ir para dashboard</a>
        </form>
    </div>
</body>
</html>