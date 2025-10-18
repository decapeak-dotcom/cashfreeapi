<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Cashfree Sandbox Keys
$clientId = "TEST103385352abd6b8ad104301be2dd53583301";
$clientSecret = "cfsk_ma_test_3e71a5dba61e0a2b707846d8e48d2ac7_04a09049";

// Read JSON input from Kodular or browser
$data = json_decode(file_get_contents("php://input"), true);

$orderId = $data["order_id"];
$orderAmount = $data["order_amount"];
$orderNote = $data["order_note"];
$customerName = $data["customer_name"];
$customerPhone = $data["customer_phone"];
$customerEmail = $data["customer_email"];

$payload = json_encode(array(
    "order_id" => $orderId,
    "order_amount" => $orderAmount,
    "order_currency" => "INR",
    "customer_details" => array(
        "customer_id" => $orderId,
        "customer_name" => $customerName,
        "customer_email" => $customerEmail,
        "customer_phone" => $customerPhone
    ),
    "order_note" => $orderNote
));

// Send API Request to Cashfree
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://sandbox.cashfree.com/pg/orders",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $payload,
  CURLOPT_HTTPHEADER => array(
    "x-client-id: $clientId",
    "x-client-secret: $clientSecret",
    "x-api-version: 2022-09-01",
    "Content-Type: application/json"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

// Return response
if ($err) {
  echo json_encode(array("error" => $err));
} else {
  echo $response;
}
?>
