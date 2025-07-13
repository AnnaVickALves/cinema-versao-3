<?php
<?php
require_once "../../php/conexao.php"; // ajuste o caminho conforme a estrutura real do seu projeto

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["poster"])) {
    // Pasta de destino
    $pasta_destino = "../../img/filme/";
    if (!is_dir($pasta_destino)) {
        mkdir($pasta_destino, 0777, true);
    }

    // Campos do formulário
    $titulo = $_POST["titulo_filme"] ?? '';
    $classificacao = $_POST["classificacao_indicativa"] ?? '';
    $genero = $_POST["genero"] ?? '';
    $subgenero = $_POST["sub_genero"] ?? '';
    $duracao = $_POST["duracao"] ?? 0;
    $sinopse = $_POST["sinopse"] ?? '';
    $trailer = $_POST["trailer"] ?? '';

    // Upload do pôster
    $nome_original = basename($_FILES["poster"]["name"]);
    $nome_arquivo = uniqid() . "_" . $nome_original;
    $caminho_completo = $pasta_destino . $nome_arquivo;

    if (move_uploaded_file($_FILES["poster"]["tmp_name"], $caminho_completo)) {
        // Caminho a ser salvo no banco (relativo)
        $caminho_para_banco = "img/filme/" . $nome_arquivo;

        // Inserção no banco
        $stmt = $conn->prepare("INSERT INTO filmes (titulo, classificacao_indicativa, genero, sub_genero, duracao, sinopse, poster, trailer)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssisss", $titulo, $classificacao, $genero, $subgenero, $duracao, $sinopse, $caminho_para_banco, $trailer);

        if ($stmt->execute()) {
            echo "<p>🎉 Filme cadastrado com sucesso!</p>";
        } else {
            echo "<p>❌ Erro ao salvar no banco: " . $stmt->error . "</p>";
        }
    } else {
        echo "<p>❌ Erro ao fazer upload do pôster.</p>";
    }
} else {
    echo "<p>❌ Formulário inválido.</p>";
}
?>
