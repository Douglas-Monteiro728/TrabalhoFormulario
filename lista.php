<?php


/*
SQL necessário para a tarefa
CREATE DATABASE tarefas_db;
USE tarefas_db;

CREATE TABLE tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(255) NOT NULL
);
*/


$servername = "localhost";
$username = "root"; // Usuário padrão do XAMPP/MAMP
$password = ""; // Senha padrão (deixe vazia se não tiver senha)
$dbname = "tarefas_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: ");
}

// Adicionar tarefa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tarefa"]) && empty($_POST["id_editar"])) {
    $tarefa = $_POST["tarefa"];
    $sql = "INSERT INTO tarefas (descricao) VALUES ('$tarefa')";
    $conn->query($sql);
}

// Remover tarefa
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $conn->query("DELETE FROM tarefas WHERE id=$id");
}

// Buscar tarefas
$result = $conn->query("SELECT * FROM tarefas");



//Atualizar tarefa (quando está editando)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_editar"])){
    $id = $_POST["id_editar"];
    $descricao = $_POST["tarefa"];
    $conn->query("UPDATE tarefas SET descricao='$descricao' WHERE id=$id");

    header("Location: lista.php");
    exit();

}


//Buscar tarefa para edição
$tarefa_editar = null;
if (isset($_GET["edit"])){
    $id = $_GET["edit"];
    $res = $conn->query("SELECT * FROM tarefas WHERE id=$id");
    if($res->num_rows > 0){
        $tarefa_editar = $res->fetch_assoc();
    }
}



?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Lista de Tarefas (PHP)</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
    <h1 class="text-center mb-4">Lista de Tarefas</h1>
    <form method="POST" class="mb-4">
        <div class="input-group">
        <input class="form-control" type="text" name="tarefa" placeholder="Digite uma tarefa" required 
        value="<?php if ($tarefa_editar) echo $tarefa_editar['descricao']?>">

        <?php if ($tarefa_editar): ?>
            <input type="hidden" name="id_editar" value="<?php echo $tarefa_editar['id']; ?>">
            <div class="gap-2">
            <button class="btn btn-success" type="submit"> Atualizar </button>
            <button class="btn btn-secondary"><a href="lista.php"> cancelar</a></button>
            </div>
        <?php else: ?>
            <button class="btn btn-primary"type="submit">Adicionar</button>
        <?php endif; ?>
        </div>
    </form>

    <?php
       /*
       if ($tarefa_editar) {
            echo '<input type="hidden" name="id_editar" value="' . $tarefa_editar['id'] . '">';
            echo '<button type="submit">Atualizar</button>';
            echo '<a href="index.php">Cancelar</a>';
        } else {
             echo '<button type="submit">Adicionar</button>';
             }
        */
    ?>


    <ul class="list-group">
        <?php while ($row = $result->fetch_assoc()): ?>
            <li class="list-group-item d-flex justify-content-between align-itens-center">
                <?PHP echo $row["descricao"]; ?> 
                <div class="d-flex gap-2">
                <a class="btn btn-warning" href="?edit=<?= $row["id"]; ?>">Editar</a>
                <a class="btn btn-danger "href="?delete=<?= $row["id"]; ?>">Remover</a>
                </div>
            </li>
        <?php endwhile; ?>
    </ul>
    </div>
</body>
</html>

<?php $conn->close(); ?>