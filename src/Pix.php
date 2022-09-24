<?php

namespace BMorais\Pix;

use Mpdf\QrCode\Output;
use Mpdf\QrCode\QrCode;

/**
 * CLASSE PIX
 *  Esta classe é responsavel por gerar o pix
 *
 * @author Bruno Morais <contato@bmorais.com>
 * @copyright GPL © 2022, bmorais.com
 * @package bmorais\pix
 * @subpackage class
 * @access private
 */
class Pix {

  /**
   * IDs do Payload do Pix
   * @var string
   */
  const ID_PAYLOAD_FORMAT_INDICATOR = '00';
  const ID_MERCHANT_ACCOUNT_INFORMATION = '26';

  const ID_MERCHANT_ACCOUNT_INFORMATION_GUI = '00';
  const ID_MERCHANT_ACCOUNT_INFORMATION_CHAVE = '01';
  const ID_MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION = '02';

  const ID_MERCHANT_CATEGORY_CODE = '52';
  const ID_TRANSACTION_CURRENCY = '53';
  const ID_TRANSACTION_AMOUNT = '54';
  const ID_COUNTRY_CODE = '58';
  const ID_MERCHANT_NAME = '59';
  const ID_MERCHANT_CITY = '60';

  const ID_ADDITIONAL_DATA_FIELD_TEMPLATE = '62';
  const ID_ADDITIONAL_DATA_FIELD_TEMPLATE_TXID = '05';

  const ID_CRC16 = '63';

  /**
   * Chave Pix
   * @var string
   */
  private $pixKey;

  /**
   * Descrição do pagamento
   * @var string
   */
  private $description;

  /**
   * Nome do titular da conta
   * @var string
   */
  private $merchantName;

  /**
   * Cidade do titular da conta
   * @var string
   */
  private $merchantCity;

  /**
   * ID de transação Pix
   * @var string
   */
  private $txid;

  /**
   * Valor da transação
   * @var string
   */
  private $amount;

  /**
   * Método responsável por definir o valor de $pixKey
   * @param string $pixKey
   */
  public function setPixKey($pixKey) {
    $this->pixKey = $pixKey;
    return $this;
  }

  /**
   * Método responsável por definir o valor de $description
   * @param string $description
   */
  public function setDescription($description) {
    $this->description = $description;
    return $this;
  }

  /**
   * Método responsável por definir o valor de $merchantName
   * @param string $merchantName
   */
  public function setMerchantName($merchantName) {
    $this->merchantName = $merchantName;
    return $this;
  }

  /**
   * Método responsável por definir o valor de $merchantCity
   * @param string $merchantCity
   */
  public function setMerchantCity($merchantCity) {
    $this->merchantCity = $merchantCity;
    return $this;
  }

  /**
   * Método responsável por definir o valor de $txid
   * @param string $txid
   */
  public function setTxid($txid) {
    $this->txid = $txid;
    return $this;
  }

    /**
     * @param $amount
     * @return $this
     */
  public function setAmount($amount) {
      $this->amount = number_format($amount, 2, ',', '.');
    return $this;
  }

  /**
   * Método responsável por retornar o valor completo de um objeto pauload
   * @param string $id
   * @param string $value
   * @return string $id.$size.$value
   */
  private function getValue($id, $value) {
    $size = str_pad(strlen($value), 2, '0', STR_PAD_LEFT);

    return $id.$size.$value;
  }

  /**
   * Método responsável por retornar valores completos da informação da conta
   * @return string
   */
  private function getMerchantAccountInformation() {
    // Domínio do Banco Central
    $gui = $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION_GUI, 'br.gov.bcb.pix');

    // Chave Pix
    $key = $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION_CHAVE, $this->pixKey);

    // Descrição do pagamento
    $description = 
    strlen($this->description) ? 
    $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION, $this->description) :
    '';

    // Retorna o valor completo da conta
    return $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION, $gui.$key.$description);
  }

  /**
   * Método responsável por retornar os valores completos do campo adicional do pix ($txid)
   * @return string
   */
  private function getAdditionalDataFieldTemplate() {
    // txid
    $txid = $this->getValue(self::ID_ADDITIONAL_DATA_FIELD_TEMPLATE_TXID, $this->txid);

    // Retorna o valor completo
    return $this->getValue(self::ID_ADDITIONAL_DATA_FIELD_TEMPLATE, $txid);
  }

  /**
   * Método responsável por calcular o valor da hash de validação do código pix
   * @return string
   */
  private function getCRC16($payload) {
    // Adiciona dados gerais do payload
    $payload .= self::ID_CRC16.'04';

    // Dados definidos pelo Bacen
    $polinomio = 0x1021;
    $resultado = 0xFFFF;

    // Checksum 
    if (($length = strlen($payload)) > 0) {
        for ($offset = 0; $offset < $length; $offset++) {
            $resultado ^= (ord($payload[$offset]) << 8);
            for ($bitwise = 0; $bitwise < 8; $bitwise++) {
                if (($resultado <<= 1) & 0x10000) $resultado ^= $polinomio;
                $resultado &= 0xFFFF;
            }
        }
    }

    // Retorna o código CRC16 de 4 caractéres
    return self::ID_CRC16.'04'.strtoupper(dechex($resultado));
  }

  /**
   * Método responsável por gerar o código completo do payload Pix
   * @return string
   */
  public function getPayload() {
    // Cria o $payload
    $payload = 
      $this->getValue(self::ID_PAYLOAD_FORMAT_INDICATOR, '01') . 
      $this->getMerchantAccountInformation() . 
      $this->getValue(self::ID_MERCHANT_CATEGORY_CODE, '0000') .
      $this->getValue(self::ID_TRANSACTION_CURRENCY, '986') .
      $this->getValue(self::ID_TRANSACTION_AMOUNT, $this->amount) .
      $this->getValue(self::ID_COUNTRY_CODE, 'BR') .
      $this->getValue(self::ID_MERCHANT_NAME, $this->merchantName) .
      $this->getValue(self::ID_MERCHANT_CITY, $this->merchantCity) . 
      $this->getAdditionalDataFieldTemplate();

    // Retorna o payload + CRC16
    return $payload.$this->getCRC16($payload);
  }

    /**
     * @param $payload
     * @param $size
     * @return string
     * @throws \Mpdf\QrCode\QrCodeException
     */
  public function qrcode($payload, $size=400){
      $objQrcode = new QrCode($payload);
      return (new Output\Png)->output($objQrcode, $size);
  }
}