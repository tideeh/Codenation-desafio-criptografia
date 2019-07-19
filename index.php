<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <script src="js/jquery-3.4.1.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">

    <title>Desafio Codenation</title>
</head>

<body>

<?php
$token = '80dc1816a743a21249dc47b6ba28fa9c56867ef2';
$url = 'https://api.codenation.dev/v1/challenge/dev-ps/generate-data';
?>

<div class="row page-header text-center mb-5">
    <div class="col-1 pt-2"><button class="btn btn-outline-dark btn-sm" onclick="window.history.back()">Voltar</button></div>
    <div class="col-11"><h1>Desafio Codenation</h1></div>
</div>

<?php
if ( isset($_POST["api_url"]) && isset($_POST["api_token"]) ){
    $response = file_get_contents($_POST["api_url"].'?token='.$_POST["api_token"]);
    file_put_contents('answer.json', $response);

    $obj = json_decode($response);
    #var_dump($obj);

    ?>
    <form class="p-3" id="decifrar" method="post">

        <div class="input-group mb-2 row">
            <div class="input-group-prepend col-1 pr-0">
                <span class="text-wrap input-group-text border border-warning w-100" id="">Número de casas</span>
            </div>
            <input type="text" class="h-auto form-control bg-light border border-warning col-11" name="numero_casas" id="numero_casas" value='<?php echo $obj->numero_casas; ?>'>
        </div>

        <div class="input-group mb-2 row">
            <div class="input-group-prepend col-1 pr-0">
                <span class="text-wrap input-group-text border border-warning w-100" id="">Texto cifrado</span>
            </div>
            <input type="text" class="h-auto form-control bg-light border border-warning col-11" name="cifrado" id="cifrado" value='<?php echo $obj->cifrado; ?>' readonly>
        </div>

        <div class="mx-auto" style="width: 200px;">
            <input type="submit" class="btn btn-outline-warning" value="Decifrar">
        </div>
    </form>
    <?php
}

else if ( isset($_POST["numero_casas"]) && isset($_POST["cifrado"]) ){
    $numero_casas = $_POST["numero_casas"];
    $cifrado = $_POST["cifrado"];

    $alfabeto = array();
    foreach (range('z', 'a') as $l){
        array_push($alfabeto, $l);
    }

    #echo var_dump($alfabeto);

    $decifrado = "";
    $array = str_split($cifrado);
    foreach ($array as $char){
        if(array_search($char, $alfabeto) || $char == 'z'){
            $index = (array_search($char, $alfabeto) + intval($numero_casas)) % count($alfabeto);
            $decifrado .= $alfabeto[$index];
        }
        else {
            $decifrado .= $char;
        }
    }

    #echo $decifrado;
    $sha1 = sha1($decifrado);

    $arq = file_get_contents("answer.json");
    $obj = json_decode($arq);

    $obj->decifrado = $decifrado;
    $obj->resumo_criptografico = $sha1;
    #var_dump($obj);

    file_put_contents('answer.json', json_encode($obj));

    ?>

    <div class="p-3">

        <div class="input-group mb-2 row">
            <div class="input-group-prepend col-1 pr-0">
                <span class="text-wrap input-group-text border border-success w-100" id="">Texto Cifrado</span>
            </div>
            <input type="text" class="h-auto form-control bg-light border border-success col-11" name="cifrado" id="cifrado" value='<?php echo $cifrado; ?>' readonly>
        </div>

        <div class="input-group mb-2 row">
            <div class="input-group-prepend col-1 pr-0">
                <span class="text-wrap input-group-text border border-success w-100" id="">Texto Decifrado</span>
            </div>
            <input type="text" class="h-auto form-control bg-light border border-success col-11" name="decifrado" id="decifrado" value='<?php echo $decifrado; ?>' readonly>
        </div>

        <div class="input-group mb-2 row">
            <div class="input-group-prepend col-1 pr-0">
                <span class="text-wrap input-group-text border border-success w-100" id="">Resumo SHA1</span>
            </div>
            <input type="text" class="h-auto form-control bg-light border border-success col-11" name="sha1" id="sha1" value='<?php echo $sha1; ?>' readonly>
        </div>

    </div>

    <form class="p-3" id="resposta" method="post" enctype="multipart/form-data" action="https://api.codenation.dev/v1/challenge/dev-ps/submit-solution?token=<?php echo $token; ?>">

        <div class="input-group mb-2 row">
            <div class="input-group-prepend col-1 pr-0">
                <span class="text-wrap input-group-text border border-success w-100" id="">Arquivo</span>
            </div>
            <input type="file" class="h-auto form-control bg-light border border-success col-11" name="answer" id="answer">
        </div>

        <div class="mx-auto" style="width: 200px;">
            <input type="submit" class="btn btn-outline-success" value="Enviar">
        </div>

    </form>

    <?php
}

else {
    ?>
    <form class="p-3" id="generate-data" method="post">

        <div class="input-group mb-2 row">
            <div class="input-group-prepend col-1 pr-0">
                <span class="text-wrap input-group-text w-100" id="">URL</span>
            </div>
            <input type="text" class="h-auto form-control col-11" name="api_url" id="api_url" value="<?php echo $url; ?>">
        </div>

        <div class="input-group mb-2 row">
            <div class="input-group-prepend col-1 pr-0">
                <span class="text-wrap input-group-text w-100" id="">Token</span>
            </div>
            <input type="text" class="h-auto form-control col-11" name="api_token" id="api_token" value="<?php echo $token; ?>">
        </div>

        <div class="mx-auto" style="width: 200px;">
            <input type="submit" class="btn btn-outline-primary" value="Realizar requisição">
        </div>
    </form>
<?php
}
?>

</body>

</html>