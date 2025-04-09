<?php
    /*
    CREATE DATABASE cadastro;

    USE cadastro;
    
    CREATE TABLE alunos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        telefone VARCHAR(20)
    );
    */
    // Conexão com o banco
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "cadastro";
    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // CREATE ou UPDATE
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST["nome"];
        $email = $_POST["email"];
        $telefone = $_POST["telefone"];

        if (isset($_POST["id"]) && $_POST["id"] != "") {
            // UPDATE
            $id = $_POST["id"];
            $conn->query("UPDATE alunos SET nome='$nome', email='$email', telefone='$telefone' WHERE id=$id");
        } else {
            // CREATE
            $conn->query("INSERT INTO alunos (nome, email, telefone) VALUES ('$nome', '$email', '$telefone')");
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // DELETE
    if (isset($_GET["delete"])) {
        $id = $_GET["delete"];
        $conn->query("DELETE FROM alunos WHERE id=$id");
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // EDIT - buscar dados do aluno
    $editando = false;
    if (isset($_GET["edit"])) {
        $id = $_GET["edit"];
        $resultado = $conn->query("SELECT * FROM alunos WHERE id=$id");
        if ($resultado->num_rows > 0) {
            $aluno = $resultado->fetch_assoc();
            $editando = true;
        }
    }
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Cadastro de Alunos (PHP)</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body style="background-color:rgb(232, 232, 232);" class="container mt-5">
        <h2><?= $editando ? "Editar Aluno" : "Cadastrar Aluno" ?></h2>
        <form method="POST" class="mb-4">
            <?php if ($editando): ?>
                <input type="hidden" name="id" value="<?= $aluno['id'] ?>">
            <?php endif; ?>
            <div class="mb-3">
                <input type="text" name="nome" class="form-control" placeholder="Digite seu nome" value="<?= $editando ? $aluno['nome'] : '' ?>" required>
            </div>
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Digite seu email" value="<?= $editando ? $aluno['email'] : '' ?>" required>
            </div>
            <div class="mb-3">
                <input type="text" name="telefone" class="form-control" placeholder="Digite seu telefone" value="<?= $editando ? $aluno['telefone'] : '' ?>">
            </div>
            <button type="submit" class="btn btn-success"><?= $editando ? "Atualizar" : "Salvar" ?></button>
            <?php if ($editando): ?>
                <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary">Cancelar</a>
            <?php endif; ?>
        </form>

        <h2>Lista de Alunos</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Alterações</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $result = $conn->query("SELECT * FROM alunos");
            while ($row = $result->fetch_assoc()):
            ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nome'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['telefone'] ?></td>
                    <td>
                        <a href="?edit=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" >Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </body>
    </html>
    
