<?php
$host = "mybilling.telinta.com"; // mybilling.yourdomain.com or IP address
$iso_4217 = 'USD'; // currency sign
$service = 'Admin'; // Admin or Reseller service
$rate_calc_minutes = array(
    10,
    20,
); // values that will be used in the rate calculator mode
$tariff = ''; // i_tariff value
$login = ''; // SOAP login
$password = ''; // SOAP password or API authentication token
$limit_of_rows = 10; // limit of rows in SOAP request
$rates_limit = array(
    10,
    25,
    50,
    100,
); // build drop-down list for number of rates shown
$pages_limit = 5; // number of pages in pagination bar
$report_modes = array(
    // Remove the unneeded mode or leave both
    "1" => "rate calculator",
    "2" => "general rate info",
);
$sort = array(
    "direction" => "ASC", // "ASC" for an ascending order, "DESC" for descending
    "by" => "price_n", // "price_n" for sorting by price, "destination" for sorting by destination alphabetically.
);

$soap_options = array(
    'trace' => 1,
    'exceptions' => 0,
    'cache_wsdl' => WSDL_CACHE_BOTH,
    'connection_timeout' => 600,
    'stream_context' => stream_context_create(array(
        'ssl' => array(
            // 'verify_peer' => false,
            // 'verify_peer_name' => false,
            'allow_self_signed' => true,
            'ciphers' => 'AES256-SHA',
        ),
    )),
    'user_agent' => 'RATE-CALC-PHP-SOAP-' . $login,
);

$session = new SoapClient("https://" . $host . "/wsdl/Session${service}Service.wsdl", $soap_options);

#MR50 password
$session_id = $session->login(array(
    'login' => $login,
    'password' => $password,
))->session_id;

if (empty($session_id)) { # MR50 token
$session_id = $session->login(array(
    'login' => $login,
    'token' => $password,
))->session_id;
}

if (empty($session_id)) { # MR45
$session_id = $session->login($login, $password);
}

$headers = null;
$headers[] = new SoapVar("<session_id>$session_id</session_id>", XSD_ANYXML, "session_id", "https://" . $host . "/Porta/SOAP/Session");
$auth_info = new SoapHeader("https://" . $host . "/Porta/SOAP/Session", "auth_info", $headers);
$rate_client = new SoapClient("https://" . $host . "/wsdl/Internal${service}Service.wsdl", $soap_options);
