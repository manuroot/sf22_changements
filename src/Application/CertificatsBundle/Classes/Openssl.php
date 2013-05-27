<?php

namespace Application\CertificatsBundle\Classes;

class Openssl {

    public function __construct() {
        
    }

    //===============================
    public function asn1der_ia5string($str) {

        $len = strlen($str) - 2;
        if ($len < 0 && $len > 127) {
            return false;
        }

        /* check tag and len */
        if (22 != (ord($str[$pos++]) & 0x1f) &&
                ord($str[$pos++]) != $len) {
            /* not a valid DER encoding of an IA5STRING */
            return false;
        }

        return substr($str, 2, $len);
    }

//===============================
    public function execCommand($IP, $Command) {

        exec('sudo -H -u chris ssh utest@' . escapeshellcmd($IP) . ' "' . escapeshellcmd($Command) . ' 2>&1"', $output, $retval);
        if ($retval == 0) {
            $count = count($output);
            for ($i = 0; $i < $count; $i++) {
                echo (htmlspecialchars($output[$i]) . "\r\n");
            }
        }
        return $retval;
    }

//===============================
function View_openssl($name, $what, $pass='') {
//===============================
    $cmd = '';
    $cmd1 = '';
    $file = basename($name);
    $dir = dirname($name);
    $info = pathinfo($file);
    if (Test_value_file($name) != TRUE){print "Erreur de fichier<br>";return FALSE;}	
    $file_name = basename($file, '.' . $info['extension']);
//print "pass=$pass<br>";
    print "<h3>$what</h3>";

    switch ($what) {
        case "View ac_csr":
        case "View csr":
            $cmd = ' req -noout -text ';
            break;
        case "View crl":
            $cmd = ' crl -noout -text ';
            break;
        case "View p12":
	// openssl pkcs12 -info -nodes -in cred.p12
           $cmd = ' pkcs12 -info -nodes ';
            break;
        case "View key":
    		exec('cat ' . escapeshellcmd($name) . ' 2>&1', $output, $retval);
		$retval .="<br>";
    		Affiche_RetVal($retval, $output);
		unset($retval);
		unset($output);
        	    $cmd = ' rsa -noout -text ';
        	    break;
        case "View ac_crt":
            $cmd = ' x509  -inform PEM -noout -text ';
            break;
        case "View crt":
        case "View bundle":
            $cmd = ' x509  -inform PEM -noout -text ';
            break;
        case "View der":
            $cmd = ' x509  -inform DER -noout -text ';
            break;
        case "Decrypt priv_key":
            $cmd = ' rsa ';
            $cmd1 = " -out ${dir}/${file_name}-decrypt.key ";
            print "cmd1=$cmd1<br>";
            break;
    }

    if (!isset($pass) || "$pass" == '') {
        echo "no password";
        $pass = '';
    } else {
        $pass = '-passin pass:' . $pass;
    }
    print "<br><h6>/usr/bin/openssl $cmd -in $name $pass</h6><br>";
    print "cmd=$cmd<br>";
    print "pass=$pass<br>";
//print "/usr/bin/openssl' . $cmd  -in $name $pass $cmd1.<br>";
    exec('/usr/bin/openssl' . $cmd . ' -in ' . escapeshellcmd($name) . ' ' . escapeshellcmd($pass) . escapeshellcmd($cmd1) . ' 2>&1', $output, $retval);
    Affiche_RetVal($retval, $output);
    return $retval;
}

//===============================
function Parse_x509($name) {
//===============================

    if (Test_value_file($name) != TRUE){return FALSE;}
    $file_basename = basename($name);
	$info = pathinfo($name);
    $data = file_get_contents($name);

    print "<h3>View Certificate: $file_basename</h3>";

    if (isset($info['extension'])) {

        /* rename with new extension */
        if ($info['extension'] == 'der') {
            $pem = chunk_split(base64_encode($data), 64, "\n");
            $pem = "-----BEGIN CERTIFICATE-----\n" . $pem . "-----END CERTIFICATE-----\n";
            $data = $pem;
        }
    }

    $data_parse = openssl_x509_parse($data);
    if (!$data_parse) {
        print "Error parsing data from $file_basename";
        return (FALSE);
    }
//$data_parse = openssl_x509_parse(file_get_contents($name));
    array_walk($data_parse, 'print_element');
//$ext_value =  $ssl['extensions']['1.2.3.4.5.6'];
//$ssl = openssl_x509_parse($cert);
//  print_r(array_values($data));
//  print_r(array_keys($data));
    return (TRUE);
}

//===============================
function Affiche_RetVal($retval, $output) {
//===============================

    if ($retval == 0) {
        return null;
    } 
    // traitement par ligne
       foreach ($output as $line) {
        echo (htmlspecialchars($line)) . "\n";
    }
    return $retval;
}
}
