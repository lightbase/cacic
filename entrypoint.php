<?php


$parameters = yaml_parse_file(dirname(__FILE__) . "/config/app/cacic-dist-parameters.yml");


// Load env vars
$parameters['database_driver'] = getenv('CACIC_DB_DRIVER') ? getenv('CACIC_DB_DRIVER') : 'pdo_pgsql';
$parameters['database_host'] = getenv('CACIC_DB_HOST') ? getenv('CACIC_DB_HOST') : '127.0.0.1';
$parameters['database_port'] = getenv('CACIC_DB_PORT') ? getenv('CACIC_DB_PORT') : '5432';
$parameters['database_name'] = getenv('CACIC_DB_NAME') ? getenv('CACIC_DB_NAME') : 'cacic';
$parameters['database_password'] = getenv('CACIC_DB_PASSWORD') ? getenv('CACIC_DB_PASSWORD') : null;
$parameters['mailer_transport'] = getenv('CACIC_MAILER_TRANSPORT') ? getenv('CACIC_MAILER_TRANSPORT') : 'smtp';
$parameters['mailer_host'] = getenv('CACIC_MAILER_HOST') ? getenv('CACIC_MAILER_HOST') : '127.0.0.1';
$parameters['mailer_user'] = getenv('CACIC_MAILER_USER') ? getenv('CACIC_MAILER_USER') : null;
$parameters['mailer_password'] = getenv('CACIC_MAILER_PASSWORD') ? getenv('CACIC_MAILER_PASSWORD') : null;
$parameters['mailer_from'] = getenv('CACIC_MAILER_FROM') ? getenv('CACIC_MAILER_FROM') : 'cacic@localhost';
$parameters['locale'] = getenv('CACIC_LOCALE') ? getenv('CACIC_LOCALE') : 'pt_BR';

// Get secret
$parameters['secret'] = getToken();

// Serialize file
$output_file = dirname(__FILE__) . "/config/app/parameters.yml";
yaml_emit_file($output_file, $parameters);

// Composer commands
`php composer.phar install --no-interaction`
`php app/console cache:warmup --env=prod`
`php app/console assets:install --symlink --env=prod`
`php app/console doctrine:schema:update --force`
`php app/console doctrine:migrations:migrate --no-interaction`
`php app/console cache:warmup --env=prod`
`php app/console lexik:monolog-browser:schema-create`


/**
 * Generate secure tokens
 * Source: http://stackoverflow.com/questions/2593807/md5uniqid-makes-sense-for-random-unique-tokens
 */
function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 0) return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
}

function getToken($length=32){
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    for($i=0;$i<$length;$i++){
        $token .= $codeAlphabet[crypto_rand_secure(0,strlen($codeAlphabet))];
    }
    return $token;
}
