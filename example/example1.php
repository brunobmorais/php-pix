<?php
require dirname(__DIR__, 1) . '/vendor/autoload.php';

use BMorais\Pix\Pix;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;


/** VALORES PIX */

$defaultPíxKey = "CHAVEPIX";
$defaultDescription = mb_strimwidth("DESCRICAO PIX", 0, 19, ""); //ex: Pagamento do pedido
$defaultMerchantName = "NOME PESSOA PIX";
$defaultMerchantCity = "NOME CIDADE";
$defaultTxid = "IDUNICO";
$order = "";
$amount = "100,00";

// Instancia principal do payload Pix
$obPayload =
    (new Pix)
        ->setPixKey($defaultPíxKey)
        ->setDescription($defaultDescription . $order)
        ->setMerchantName($defaultMerchantName)
        ->setMerchantCity($defaultMerchantCity)
        ->setAmount($amount)
        ->setTxid($defaultTxid);

// Código de pagamento Pix
$payload = $obPayload->getPayload();
// Instancia do Qr Code
$objQrcode = new QrCode($payload);
// Imagem do Qr Code
$qrcode = (new Output\Png)->output($objQrcode, 400);

?>

<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    </head>
    <body>
        <div style="padding-top: 30px; padding-bottom: 100px">
            <div class="container-fluid my-container">
                <div class="row">
                    <div class="col-12 text-center pb-4">
                        <img class="img-responsive" src="http://via.placeholder.com/640x360"
                             style="max-width: 130px" alt="Logo empresa">
                    </div>
                    <div class="col-md-6 col-12 text-center">
                        <p><h4 class=""> Pague R$ <?= $amount ?> para <?= $defaultMerchantName ?></h4></p>
                        <p><h6 class="text-dark"> Use o QR Code do Pix para pagar
                            Abra o app em que vai fazer a transferência, escaneie a imagem ou cole o código do QR Code</h6></p>
                        <img class="img-responsive col" alt="qrcode" style="max-width: 300px"
                             src="data:image/png;base64, <?=base64_encode($qrcode); ?>"/>
                        <p><h5>R$ <?= $amount ?></h5></p>
                        <p>
                        <h6 class="subtitle">Código Pix</h6>
                        <button class="btn btn-primary" onclick="copyToClipboard('<?= $payload ?>')">Copiar
                            código do QR Code <span class="mdi mdi-content-copy"></span></button>
                        </p>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label class="mb-0">Chave PIX</label>



                            <p class=""><?= $defaultPíxKey ?></p>
                        </div>
                        <div class="form-group">
                            <label class="mb-0">Chave PIX</label>
                            <p class=""><?= $defaultPíxKey ?> <a href="javascript:void(0)"
                                                                             onclick="copyToClipboard('<?= $defaultPíxKey ?>')"
                                                                             class="btn btn-link btn-sm">Copiar <span
                                            class="mdi mdi-content-copy"></span></a></p>
                        </div>
                        <div class="form-group">
                            <label class="mb-0">Nome</label>
                            <p class=""><?= $defaultMerchantName ?></p>
                        </div>
                        <div class="">
                            <a href="/" class="btn btn-outline-primary btn-lg">Voltar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
<script>
    function copyToClipboard(valor) {
        // Copy the text inside the text field
        navigator.clipboard.writeText(valor.toString());

        // Alert the copied text
        alert("Copied the text: " + valor.toString());
    }
</script>





