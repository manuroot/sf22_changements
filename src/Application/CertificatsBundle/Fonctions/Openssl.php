<?php

namespace Application\CertificatsBundle\Fonctions;

class Openssl {

    private $private_key;
    private $csr_key;
    private $req_cert;
    private $dn_default;
    private $req_csr;

    //private $SSLcnf;
//===============================
    private function print_element($item, $key, $nom_array = 'temp') {
//===============================
        if (is_array($item)) {
            echo "$key is Array:\n<br>";
            array_walk($item, 'print_element');
            echo "$key done\n";
        } else {
            echo "$key = $item\n<br>";
        }
    }

    public function __construct() {
        // TOUS LES ATTRIBUTS EN PRIVÉ 


    }

//===============================
    private function Affiche_RetVal($retval, $output) {
//===============================

        print "<br>#==========================<br>";
        if ($retval == 0) {
            print "<br><font color=red>SUCCESS RETVAL</font><br>";
        } else {
            print "<br><font color=red>ERROR RETVAL</font><br>";
        }


//print "<br>return value=$retval<br>";
        echo "<pre>";
        foreach ($output as $line) {
            echo (htmlspecialchars($line)) . "\n";
        }

        print "</pre>";
        // print "</FONT>";
//}
//echo (htmlspecialchars($output) . "\r\n");
//echo htmlentities($output, ENT_QUOTES, 'UTF-8');
        print "#==========================<br>";
        return $retval;
    }

//===============================
    private function asn1der_ia5string($str) {
//===============================
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
    private function execCommand($IP, $Command) {
//===============================
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
    private function OpenSSL($p12, $pass) {
//===============================
    }

//===============================
    private function Convert_p12tocertificat($p12, $pass, $dest_crt = '/tmp/tmp.crt') {
//===============================
        $file = basename($p12);
//$dir = dirname($p12);
        $info = pathinfo($file);
        $file_name = basename($file, '.' . $info['extension']);
        /* if ( ! isset($pass) || "$var"=="test" ) {
          echo "$pass est définie même si elle est vide ou par default";
          } */
        print "<h3>Decrypt_key</h3>";
        print "openssl pkcs12 -in $p12 -passin pass:$pass -out ${dest_crt} -nodes -nokeys";
        $output = shell_exec("(/usr/bin/openssl pkcs12 -in $p12 -passin pass:$pass -out ${dest_crt} -nodes -nokeys) 2>&1");
        echo "<pre>$output</pre>";
    }

//===============================
//===============================
    private function Convert_p12topem($p12, $pass, $dest_key = 'key', $dest_crt = 'crt') {
//===============================
        if (Test_value_file($p12) != TRUE) {
            return array(FALSE, NULL);
        }
        $file = basename($p12);
//$dir = dirname($p12);
        $info = pathinfo($file);
        $file_name = basename($file, '.' . $info['extension']);
        list($reponse_crt, $newcrt) = Test_And_Rename_File("${dest_crt}/${file_name}.crt");
        list($reponse_key, $newkey) = Test_And_Rename_File("${dest_key}/${file_name}.key");
        if (!$reponse_crt || !$reponse_key) {
            return array(FALSE, NULL, NULL);
        }
        print "<h3>Decrypt_key</h3>";
        print "openssl pkcs12 -in $p12 -passin pass:$pass -out ${dest_crt}/${file_name}.crt -nodes -nokeys";
        $output = shell_exec("(/usr/bin/openssl pkcs12 -in $p12 -passin pass:$pass -out ${dest_crt}/${file_name}.crt -nodes -nokeys) 2>&1");
        echo "<pre>$output</pre>";
        print "openssl pkcs12 -in $p12 -passin pass:$pass -out ${dest_key}/${file_name}.key -nodes -nocerts";
        $output = shell_exec("(openssl pkcs12 -in $p12 -passin pass:$pass -out ${dest_key}/${file_name}.key -nodes -nocerts) 2>&1");
        echo "<pre>$output</pre>";
        return array(TRUE, "${dest_crt}/${file_name}.crt", "${dest_key}/${file_name}.key");
    }

//===============================
    private function Decrypt_key_php($file, $pass, $sub_dir = 'key') {
//===============================
        if (!Test_value_file($file)) {
            return array(FALSE, NULL);
        }
        print "starting decrypt $file<br>";

        $priv_key = file_get_contents($file);
        print "key=<br>$priv_key<br>";
        $res = openssl_get_privatekey($priv_key, $pass);
//openssl_private_encrypt($source,$crypttext,$res)
        $info = pathinfo($file);
        $path = dirname($file);
        $prefixe_name = basename($file, '.' . $info['extension']);
        $file = $prefixe_name . '-decrypt' . '.key';
//openssl_private_encrypt($source,$crypttext,$res)
        print "file=$file<br>";
        list($reponse, $newfile) = Test_And_Rename_File("$path/$file");
        print "Retour 1: file=$newfile <br>";
//if ($reponse == "TRUE"){
        if ($reponse && $res) {
            openssl_pkey_export_to_file($res, "$newfile");
            $file_basename = basename($newfile);
            $file_return = $sub_dir . '/' . $file_basename;
            print "file=$file_return <br>";
            return array($reponse, $file_return);
        } else {
            return array(FALSE, NULL);
        }

        /*
          $part = explode("${dir_default}/", $newfile);
          $saved=$part[1]; */
        /* $file_basename=basename($newfile);
          $file_return=$sub_dir . '/' . $file_basename;
          print "file=$file return = $saved<br>";
          return array($reponse,$saved); */
//openssl_pkey_export_to_file ( $this->csr_key , "${path}/${file}.key",$password);
    }

//===============================
    private function Read_p12($file, $pass, $var = 'test') {
//===============================
        if (!Test_value_file($file)) {
            return FALSE;
        }
        $p12cert = array();
        $fd = fopen($file, 'r');
        $p12buf = fread($fd, filesize($file));
        fclose($fd);
        echo "pass=--$pass--<br>";
        //if (!isset($pass) || "$var" == "test") {
        if (!isset($pass)) {
            echo "$pass est définie même si elle est vide ou par default: no password !";
            $pass = '';
        }
        if (openssl_pkcs12_read($p12buf, $p12cert, $pass)) {
            foreach ($p12cert as $line) {
                print "<pre>$line</pre>";
            }
            return (TRUE);
        } else {
            echo 'Fail';
            return (FALSE);
        }
    }

//===============================
    private function Return_Dates($name) {
//===============================
        $file_basename = basename($name);
        $info = pathinfo($name);
        $data = file_get_contents($name);

        if (isset($info['extension'])) {
            if ($info['extension'] == 'der') {
                $pem = chunk_split(base64_encode($data), 64, "\n");
                $pem = "-----BEGIN CERTIFICATE-----\n" . $pem . "-----END CERTIFICATE-----\n";
                $data = $pem;
            }
        }
        $data_parse = openssl_x509_parse($data);

        /* $validFrom = date('Ymd', $data['validFrom_time_t']);
          $validTo = date('Ymd', $data['validTo_time_t']); */

        $validFrom = date('Y-m-d', $data_parse['validFrom_time_t']);
        $validTo = date('Y-m-d', $data_parse['validTo_time_t']);
        echo "d1=$validFrom <br> d2=$validTo<br>";
//echo "$validFrom  -- $validFrom <br>";
        return array($validFrom, $validTo);
    }

//===============================
    private function Return_CN_Cert($name) {
//===============================

        global $def_data;
        $file_basename = basename($name);
        $info = pathinfo($name);
        $data = file_get_contents($name);

        if (isset($info['extension'])) {

            /* rename with new extension */
            if ($info['extension'] == 'der') {
                $pem = chunk_split(base64_encode($data), 64, "\n");
                $pem = "-----BEGIN CERTIFICATE-----\n" . $pem . "-----END CERTIFICATE-----\n";
                $data = $pem;
            }
        }

        $data = openssl_x509_parse($data);
//array_walk( $data, 'print_element' );
        if (isset($data['subject']['CN'])) {
            $cn = $data['subject']['CN'];
        } else {
            $cn = '----';
        }
        return ($cn);
    }

//===============================
    private function Test_value_file($name) {
//===============================
        if (!(isset($name)) || $name == '') {
            print "Pas de fichier selectionné<br>";
            return (FALSE);
        }
        $file = basename($name);
#if (! file_exists($name)) {print "Le fichier $file n\'existe pas<br>"; return (FALSE);}
        if (!file_exists($name)) {
            print "Le fichier $file n\'existe pas<br>";
            return (FALSE);
        } else {
            if (!is_file($name)) {
                print "$file n\'est pas un fichier<br>";
                return (FALSE);
            } elseif (!is_readable($name)) {
                print "$file n\'est pas lisible<br>";
                return (FALSE);
            } else {
                print "<br>Tests $file OKI<br>";
                return(TRUE);
            }
        }
    }

//===============================
    private function View_Cert_php($name) {
//===============================
        if (Test_value_file($name) != TRUE) {
            return FALSE;
        }
        global $def_data;
        $file = basename($name);
        print "<h3>View Certificate</h3>";
        $data_simple = file_get_contents($name);
        $data = openssl_x509_parse(file_get_contents($name));

        if (!$data_simple) {
            print "Error data from $name<br>";
            return (FALSE);
        }

        if (is_file($name)) {
            if ($TabFich = file($name)) {
                for ($i = 0; $i < count($TabFich); $i++)
                    echo $TabFich[$i] . "<br>";
            }
        }
        /*
          else {
          echo "Le fichier ne peut être lu...<br>";

          }
          }

          else {
          echo "Désolé le fichier n'est pas valide<br>";

          }
         */
        echo "<br>\n";

        if (!$data) {
            print "Error data from $name<br>";
            return (FALSE);
        }

        $validFrom = date('Y-m-d H:i:s', $data['validFrom_time_t']);
        $validTo = date('Y-m-d H:i:s', $data['validTo_time_t']);
        echo "<b>from " . $validFrom . "</b>\n";
        echo "<br>\n";
        echo "<b>To " . $validTo . "</b>\n";
        echo "<br>\n";
        echo "<br>\n";
//array_walk( $data, 'print_element' );
//print $data['subject']['C'];
//print $data['name'];
//$def_data=array('C','ST','L','O','OU','CN','emailAddress');
        print "<font color=\"blue\">";
        print "<br>#============================<br>";
        print "ISSUER<br>";
        print "#============================<br>";
        print "</font>";
        foreach ($def_data as $key => $value) {
            if (isset($data['issuer'][$value])) {
                print $value . "=" . $data['issuer'][$value] . "<br>";
            }
        }
        print "<br>";
        print "<font color=\"blue\">";
        print "#============================<br>";
        print "SUBJECT<br>";
        print "#============================<br>";
        print "</font>";
        foreach ($def_data as $key => $value) {
            if (isset($data['subject'][$value])) {
                print $value . "=" . $data['subject'][$value] . "<br>";
            }
        }
        /*
          foreach ($data as $key => $value){
          print "<br>$key=$value<br>";
          if( is_array( $value ) )
          {
          echo "$value is Array:\n";
          array_walk( $value, 'print_element' );
          echo "$key done\n";
          }
          else {
          echo "value=$value\n";
          }
          }
         */
//print_r(array_values($data));
        /*
          print_r(array_keys($data));
          print_r(array_values($data));
          array_walk( $data, 'print_element' );
         */
        return (TRUE);
    }

//===============================
    private function Parse_x509($name) {
//===============================

        if (Test_value_file($name) != TRUE) {
            return FALSE;
        }
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
    private function Check_Cert_ca($name_cert, $name_ca) {
//===============================
        if (Test_value_file($name_cert) != TRUE || Test_value_file($name_ca) != TRUE) {
            return FALSE;
        }
        $file_cert = basename($name_cert);
        $file_ca = basename($name_ca);
        print "<h3>Verify Certificate</h3>";
//print "/usr/bin/openssl x509 -in $file -noout -text";
//print "/usr/bin/openssl verify -CAfile $file_ca -verbose $file_cert <br>";
        print "/usr/bin/openssl verify -CAfile $name_ca -verbose $name_cert <br>";

//print (/usr/bin/openssl x509 -in ' . escapeshellcmd($name) . '  -noout -text 2>&1'
//$output = shell_exec("(/usr/bin/openssl x509 -in $name  -noout -text) 2>&1");
        exec('/usr/bin/openssl verify -CAfile ' . escapeshellcmd($name_ca) . '  -verbose ' . escapeshellcmd($name_cert) . ' 2>&1', $output, $retval);
        Affiche_RetVal($retval, $output);
        return $retval;
    }

//===============================
    private function View_Cert($name) {
//===============================
        $file = basename($name);
        print "<h3>View Certificate</h3>";

        print "/usr/bin/openssl x509 -in $file -noout -text";
//print (/usr/bin/openssl x509 -in ' . escapeshellcmd($name) . '  -noout -text 2>&1'
//$output = shell_exec("(/usr/bin/openssl x509 -in $name  -noout -text) 2>&1");
        exec('/usr/bin/openssl x509 -in ' . escapeshellcmd($name) . '  -noout -text 2>&1', $output, $retval);
        Affiche_RetVal($retval, $output);
        return $retval;
    }

//===============================
    private function View_p12($name, $pass) {
//===============================
//if ( ! isset($pass) || "$var"=="test" ) {
//$pass='';
        if (!isset($pass) || "$var" == "test") {
            echo "$pass est définie même si elle est vide ou par default: no password !";
            $pass = '';
        } else {
            $pass = '-passin pass:' . $pass;
        }
        print "<h3>View p12</h3>";
//$output = shell_exec("(/usr/bin/openssl pkcs12 -info -nodes $pass -in $name) 2>&1");//echo "<pre>$output</pre>";print '/usr/bin/openssl pkcs12 -info -nodes -passin pass:integ -in ' .  $name;
        exec('/usr/bin/openssl pkcs12 -info -nodes ' . escapeshellcmd($pass) . ' -in ' . escapeshellcmd($name) . ' 2>&1', $output, $retval);
        Affiche_RetVal($retval, $output);
        return $retval;
    }

//===============================
    private function View_openssl($name, $what, $pass = '') {
//===============================
        $cmd = '';
        $cmd1 = '';
        $file = basename($name);
        $dir = dirname($name);
        $info = pathinfo($file);
        if (Test_value_file($name) != TRUE) {
            print "Erreur de fichier<br>";
            return FALSE;
        }
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

///===============================
    private function Create_p12_php($crt, $key, $pass_cert, $pass_p12, $extension_p12 = 'p12', $sub_dir_p12 = 'p12') {
//===============================
        global $save;
        if (Test_value_file($crt) != TRUE || Test_value_file($key) != TRUE) {
            return array(FALSE, NULL);
        }
        //
        echo "sub_dir=" . $sub_dir_p12 . "<br />";
        print "save=$save<br>";
        $var_key = array(file_get_contents("$key"), $pass_cert);
        $var_crt = file_get_contents("$crt");

        if (!$var_key || !$var_crt) {
            print "Error missing key/crt<br>";
            return array("Error missing key/crt<br>", NULL);
        }
        print "key=$key <br> cert=$crt<br>";
        $info = pathinfo($crt);
        //   echo "dir def=" . $dir_default . "  sub_dir=" . $sub_dir_p12 . "<br />";
        $prefixe_p12 = basename($crt, '.' . $info['extension']);
        $file_p12 = $prefixe_p12 . '.' . $extension_p12;
        $path = dirname($crt);
        $full_dir_p12 = dirname($path) . '/' . $sub_dir_p12;

        echo "fichier a tester:  ${full_dir_p12}/$file_p12 <br />";
        list($reponse, $file) = Test_And_Rename_File("${full_dir_p12}/$file_p12");
        print "Retour : file=$file <br>";
        /* a tester avant d exporter */
        $reponse = openssl_pkcs12_export_to_file($var_crt, $file, $var_key, $pass_p12);
        print "in f(create_p12-php): reponse=--" . $reponse . "--<br>";
        if ($reponse) {
            print "reponse oki <br />";
            $file_basename = basename($file);
            print "basename file: $file_basename<br>";
            print "sub_dir: $sub_dir_p12<br>";
            $file_return = $sub_dir_p12 . '/' . $file_basename;
            print "return file: ${sub_dir_p12}/${file_basename}<br>";
            return array($reponse, $file_return);
        } else {
            print "Erreur: Reponse de l\'export = $reponse<br>";
            return array($reponse, NULL);
        }
    }

///===============================
    private function Test_If_Bundle($file, $arr) {
        $file_handle = fopen($file, "r");
        $status = 0;
        while (!feof($file_handle)) {
            $line = fgets($file_handle);
            foreach ($arr as $value) {
                if (preg_match("/$value/", $line)) {
                    ++$status;
                }
            }
        }
        fclose($file_handle);
        echo "status=$status<br>";
        if ($status >= 2) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

///===============================
    private function Save_Data($data, $file, $suffixe = 'zzz') {
//-------------------------------------------------------------

        echo 'info' . pathinfo($file, PATHINFO_EXTENSION);
        echo '<br>';
        $file_basename = basename($file);
        $dir = dirname($file);
        $info = pathinfo($file);
        $prefixe_name = basename($file_basename, '.' . $info['extension']);
        $date = date("Y-m-d-Hi");

        print "basename=$file_basename<br>prefixe=$prefixe_name<br>";
        print "<br>file=$file <br>dir=$dir info=$info<br>";
        $new_file = "$prefixe_name.$suffixe";
        print "<br>new file=$new_file<br>";
        if (is_file("$dir/$new_file")) {
            $add = date("Ymd-H-i");
            print "<br>Le fichier $file existe: creation de ${new_file}-$add<br>";
            $myfile = "${prefixe_name}-${add}.${suffixe}";
        } else {
            $myfile = $new_file;
        }

        print "start saving: $dir/$myfile <br>";

#$temp="$dir/$myfile";
        if (!is_writable("$dir")) {
            return array(false, 'Not writable !');
        } else {
            print "<br>$dir writable oki<br>";
        }

        $fh = fopen("${dir}/${myfile}", 'w') or die("can't open test file");
#$fh = fopen("upload/$myFile", 'w') or die("can't open upload/$myFile");
        fwrite($fh, $data);
        fclose($fh);

        return array(true, "${dir}/${myfile}", 'write oki');
    }

///===============================
    private function Test_And_Rename_File($file, $extension_default = 'abc') {
//-------------------------------------------------------------print "<br>Analise File: $file<br>";
        $file_basename = basename($file);
        $info = pathinfo($file);
        $path = dirname($file);

// new path en parametre a tester
        if (isset($newpath)) {
            $path = $newpath;
        }
        print "<br>#=========================<br>";
        print "basename=$file_basename <br> path=$path <br>";
        print "<br>#=========================<br>";

        if (isset($info['extension'])) {
            /* rename with new extension */
            if ($extension_default != 'abc') {
                $suffixe = $extension_default;
                $prefixe_name = basename($file_basename, '.' . $info['extension']);
                $file = $prefixe_name . '.' . $suffixe;
                print "<br>case 1:= new file=$file<br>";
            } else {
                /* no rename just test file */
                $prefixe_name = basename($file_basename, '.' . $info['extension']);
                $suffixe = $info['extension']; //===============================
                $file = $prefixe_name . '.' . $suffixe;
                print "<br>case 2:= new file=$file<br>";
            }
        }
        /* pas d'extension: ajout d'extension */ elseif ($extension_default != 'abc') {
            $suffixe = $extension_default;
            $prefixe_name = $file_basename;
            $file = $prefixe_name . '.' . $suffixe;
            print "<br>case 3:= new file=$file<br>";
        }
        /* pas d'extension: rien faire/ */

        print "test path=$path<br>";

        if (!is_dir($path) || !is_writable($path)) {
            $mess = "$path not dir or not writable !";
            return array(FALSE, $mess);
#return array("FALSE",0);
        }

        if (is_file("$path/$file")) {
            print "file $path/$file existe !<br>";
            $add = date("Ymd-H-i");
            print "<br>Creation de ${prefixe_name}-$add.$suffixe<br>";
            $file = $prefixe_name . '-' . $add . '.' . $suffixe;
        } else {
            print "oki file $path/$file<br>";
        }
        print "<br>END file=$file<br>";

        return array(TRUE, "$path/$file");
    }

///===============================
///===============================
    private function Save_Data1($data, $file) {
        /* ------------------------------------------------------------- */
        print "<br>#==========================<br>";
        print "Ecriture de $file";
        print "<br>#==========================<br>";

        if (!$fh = fopen($file, 'w')) {
            echo "Cannot open file ($file)";
            return array(false, NULL, 'write no');
        }

        //   $fh = fopen("$file", 'w') or die("can't open test file");
        if (fwrite($fh, $data) === FALSE) {
            echo "Cannot write to file ($file)";
            return array(false, NULL, 'write no');
        }
        echo "Success, wrote data to file ($file)";
//    fwrite($fh, $data);
        fclose($fh);

        return array(true, "$file", 'write oki');
    }

//===============================
///===============================
    private function Check_Cert($crt, $key, $pass) {
//===============================
        if (!isset($pass) || "$pass" == '') {
            echo "no password";
            $pass = '';
        } else {
            $pass = '-passin pass:' . $pass;
        }
        if (Test_value_file($crt) != TRUE || Test_value_file($key) != TRUE) {
            return FALSE;
        }

        print "<h3>Check_cert/key</h3>";
        print "/usr/bin/openssl x509 -in $crt -noout -modulus";
        $outputcrt = shell_exec("(/usr/bin/openssl x509 -in $crt -noout -modulus)");
# echo "<pre>$outputcrt</pre>";
        print "/usr/bin/openssl rsa -in $key -noout -modulus";
        $outputkey = shell_exec("(/usr/bin/openssl rsa -in $key $pass -noout -modulus)");
# echo "<pre>$outputkey</pre>";
        print "<br>";
        if ("$outputcrt" == "$outputkey") {
            print "<font color=\"red\"<h4>Matching<h4></font>";
            return (TRUE);
        } else {
            print "<font color=\"red\"<h4>Not Matching<h4></font>";
            return (FALSE);
        }
    }

//===============================
///===============================
    private function Decrypt_key($key, $pass) {
//===============================
        $file = basename($key);
        $dir = dirname($key);
        $info = pathinfo($file);
        $file_name = basename($file, '.' . $info['extension']);
        if (!isset($pass) || "$var" == "test") {
            echo "$pass est définie même si elle est vide ou par default";
        }

        print "<h3>Decrypt_key</h3>";
        print "openssl rsa -in $key -passin pass:$pass -out ${dir}/${file_name}decrypt.key";
        $output = shell_exec("(/usr/bin/openssl rsa -in $key -passin pass:$pass -out ${dir}/${file_name}-decrypt.key) 2>&1");
        echo "<pre>$output</pre>";
    }

    private function Der2XPem($file) {
//-------------------------------------------------------------
        $saved = '';
        $dir = dirname($file);
        print "starting conversion $file<br>";
        print "default dir=${dir_default}<br>";
        if (is_file($file) && is_readable($file)) {
            print "<br>Oki file $file exists<br>";
        }
# else {print "$file: not readable or not a file";return("false");}
        $der_data = file_get_contents($file);
        $pem = chunk_split(base64_encode($der_data), 64, "\n");
        $pem = "-----BEGIN CERTIFICATE-----\n" . $pem . "-----END CERTIFICATE-----\n";
        print "$pem";
        print "<br><br>";

        print "test and rename<br>";
        list($reponse, $file) = Test_And_Rename_File($file, 'pem');
        print "end test and rename<br>";
        print "Retour 1: file=$file <br>";
        if ($reponse == "TRUE") {
            print "<br>call save data for $file<br>";
            list($reponse, $newfile, $mes) = Save_Data1($pem, $file);
            print "<br>retour new file=${newfile}<br>";
            print "<br>sauvegarde des donnees au format pem<br>";
        }
        print $message . '<br>';
        $file = basename($newfile);
        $reste_dir = basename($dir);
        $file_return = $reste_dir . '/' . $file;
        print "fic_saved = $newfile file=$file reste=$reste_dir<br>";
        echo "<h2>Return=Creation de  $file_return</h2>";
#print $pem . '<br>';
        return array($reponse, $file_return);
    }

///===============================
    private function Create_Bundle($crt, $key, $sub_dir_bundle) {
//-------------------------------------------------------------

        print "test values<br>";
        if (Test_value_file($crt) != TRUE || Test_value_file($key) != TRUE) {
            return FALSE;
        }
        print "end test values<br>";
        echo "bundle directory=$sub_dir_bundle<br>";
        if (!isset($sub_dir_bundle)) {
            $sub_dir_bundle = dirname($crt);
        }
        echo "bundle directory=$sub_dir_bundle<br>";
        print "<h3>Create Bundle</h3>";
        // nom du fichier crt
        $file = basename($crt);
        $info = pathinfo($file);
        $path = dirname($crt);
        $dir = dirname($path);

        $dir_bundle = $dir . '/' . $sub_dir_bundle;
        echo "full bundle directory=$dir_bundle<br>";

        $info = pathinfo($file);
        $prefixe_name = basename($file, '.' . $info['extension']);
        $file_bundle = $prefixe_name . '-bundle' . '.crt';
        print "file=$file_bundle<br>";
        print "test and rename<br>";
        list($reponse, $newfile) = Test_And_Rename_File("$dir_bundle/$file_bundle");
        print "end test and rename<br>";

        print "cat $crt $key > $newfile 2>&1";
        $output = shell_exec("cat $crt $key > $newfile 2>&1");
        exec('cat ' . escapeshellcmd($crt) . ' ' . escapeshellcmd($key) . ' > ' . escapeshellcmd($newfile) . ' 2>&1', $output, $retval);
        Affiche_RetVal($retval, $output);
        echo "<pre>$output</pre>";

        $file_basename = basename($newfile);
        print "basename file: $file_basename<br>";
        $file_return = $sub_dir_bundle . '/' . $file_basename;
        print "return file: $file_return<br>";
        return array($reponse, $file_return);
    }

///===============================
///===============================
    private function Pem2XDer($file) {
//-------------------------------------------------------------
        print "starting conversion $file<br>";
        //  print "default dir=${dir_default}<br>";
        $dir = dirname($file);
#$add = "-".date("Ymd-H-i");
        if (is_file($file) && is_readable($file)) {
            print "<br>Oki file $file exists<br>";
        } else {
            print "not readable or not a file";
            return("false");
        }
        $pem_data = file_get_contents($file);
        $begin = "CERTIFICATE-----";
        $end = "-----END";
        $pem_data = substr($pem_data, strpos($pem_data, $begin) + strlen($begin));
        $pem_data = substr($pem_data, 0, strpos($pem_data, $end));
#print $pem_data . '<br>';
        $der = base64_decode($pem_data);

//print "der=$der<br>";
        list($reponse, $file) = Test_And_Rename_File($file, 'der');
        print "Retour 1: file=$file <br>";
        if ($reponse == "TRUE") {
            print "<br>call save data for $file<br>";
            list($reponse, $newfile, $mes) = Save_Data1($der, $file);
        }

        print "<br>New file=$newfile<br>";
        $file = basename($newfile);
        $reste_dir = basename($dir);
        $file_return = $reste_dir . '/' . $file;
        print "fic_saved = $saved file=$file reste=$reste_dir<br>";
        echo "<h2>Return=Creation de  $file_return</h2>";
        return array($reponse, $file_return);
    }

}

