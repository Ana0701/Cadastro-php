<?php
require_once 'includes/config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM produtos WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo "Produto excluído com sucesso!";
    } else {
        echo "Erro ao excluir o produto: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: dashboard.php");
} else {
    echo "ID do produto não encontrado!";
}