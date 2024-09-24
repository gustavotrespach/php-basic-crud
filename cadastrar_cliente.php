<?php


function limpar_texto($str){
    return preg_replace("/[^0-9]/", "", $str);
}

$erro = false;
if (count($_POST) > 0 ) {
    
    include('conexao.php');
    
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $nascimento = $_POST['nascimento'];

    if(empty($nome)) {
        $erro = "Preencha o nome";
    }
    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL   )) {
        $erro = "Preencha o e-mail";
    }
    if(!empty($nascimento)) {
        $pedacos = explode('/', $nascimento);
        if (count($pedacos) == 3) {
            $nascimento = implode ('-', array_reverse(explode('/', $nascimento))); 
        } else {
        $erro = "A data de nascimento deve seguir o padrão dia/mês/ano. ";
        }
    }

    if(!empty($telefone)){
        $telefone = limpar_texto($telefone);
        if(strlen($telefone) != 11){
            $erro = "O telefone deve ser preenchido no padrão (11) 99999-9999";
        }
    }

    if($erro) {
        echo "<p><b>ERRO: $erro</b></p>";
    } else {
        $sql_code = "INSERT INTO clientes (nome, email, telefone, nascimento, data) 
        VALUES ('$nome','$email', '$telefone', '$nascimento', NOW())";
        $deu_certo = $mysqli->query($sql_code) or die($mysqli->error);
        if($deu_certo){
            echo "<p><b>Cliente cadastrado com sucesso!!</b></p>";
            unset($_POST);
        }
    } 
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cliente</title>
</head>
<body>
    <a href="clientes.php">Voltar para a Lista</a>
    <form method="POST" action="">
        <p>
            <label>Nome:</label>
            <input value="<?php if(isset($_POST['nome'])) echo$_POST['nome']; ?>"name="nome" type="text"><br>
        </p>
        <p>
            <label>E-mail:</label>
            <input value="<?php if(isset($_POST['email'])) echo$_POST['email']; ?>" name="email" type="text">
        </p>
        <p>
            <label>Telefone:</label>
            <input value="<?php if(isset($_POST['telefone'])) echo$_POST['telefone']; ?>" placeholder="(11) 99999-9999" name="telefone" type="text">
        </p>
        <p>
            <label>Data Nascimento:</label>
            <input value="<?php if(isset($_POST['nascimento'])) echo $_POST['nascimento']; ?>" name="nascimento" type="text">
        </p>
        <button type="submit">Salvar Cliente</button>
    </form>
</body>
</html>