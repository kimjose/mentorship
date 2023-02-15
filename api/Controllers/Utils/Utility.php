<?php

namespace Umb\Mentorship\Controllers\Utils;

use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Google\Client;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
// use Umb\EventsManager\Models\County;
// use Umb\EventsManager\Models\Facility;
// use Illuminate\Database\Capsule\Manager as DB;

class Utility
{

    /***
     * Checks for missing attributes
     * @param array $data
     * @param array $attributes
     *
     * @return array - an array of missing attrs
     */
    public static function checkMissingAttributes(array $data, array $attributes): array
    {
        $missingAttrs = [];
        foreach ($attributes as $attribute) {
            if (!isset($data[$attribute])) $missingAttrs[] = $attribute;
        }
        return $missingAttrs;
    }

    /***
     * Builds an excel sheet for downloading
     * @param String $name The name of the file to be built
     * @param string[] $headers The headers of the sheet
     * @param string[] $attributes The attributes contained in the data
     * @param array $data An Array containing the data to be loaded in the excel
     *
     * Note The length of these arrays i.e. $headers, $attributes should be the same
     *
     */
    public static function buildExcel($name, $headers, $attributes, $data)
    {
        try {
            if (sizeof($headers) != sizeof($attributes))
                throw new \Exception("Invalid Data Passed", -1);

            $writer = WriterEntityFactory::createXLSXWriter();
            $writer->openToFile($_ENV['PUBLIC_DIR'] . $name);


            $boldRowStyle = (new StyleBuilder())
                ->setFontBold()
                ->setFontSize(12)
                ->setFontUnderline()
                ->setCellAlignment(CellAlignment::CENTER)
                ->build();

            $normalRowStyle = (new StyleBuilder())
                ->setFontSize(10)
                ->setCellAlignment(CellAlignment::CENTER)
                ->build();

            $headerCells = [];
            for ($i = 0; $i < sizeof($headers); $i++) {
                $header = $headers[$i];
                array_push($headerCells, WriterEntityFactory::createCell($header));
            }
            $headerRow = WriterEntityFactory::createRow($headerCells, $boldRowStyle);
            $writer->addRow($headerRow);
            foreach ($data as $datum) {
                // if (sizeof($datum) != sizeof($attributes))
                //     throw new \Exception("Attributes mismatch. " . $i, -1);
                $datumCells = [];
                for ($i = 0; $i < sizeof($datum); $i++) {
                    $m = WriterEntityFactory::createCell($datum[$attributes[$i]]);
                    array_push($datumCells, $m);
                }
                $writer->addRow(WriterEntityFactory::createRow($datumCells, $normalRowStyle));
            }
            $writer->openToBrowser($name);
            $writer->close();
            unlink($_ENV['PUBLIC_DIR'] . $name);
        } catch (\Throwable $e) {
            Utility::logError($e->getCode(), $e->getMessage());
            echo $e->getMessage();
        }
    }

    public static function logError($code, $message)
    {
        if (!is_dir($_ENV['LOGS_DIR'])) {
            mkdir($_ENV['LOGS_DIR']);
        }
        $today = date_format(date_create(), 'Ymd');
        $handle = fopen($_ENV['LOGS_DIR'] . "errors_" . $today . ".txt", 'a');
        $data = date("Y-m-d H:i:s ", time());
        $data .= "      Code " . $code;
        $data .= "      Message " . $message;
        $data .= "      ClientAddr " . $_SERVER["REMOTE_ADDR"];
        $data .= "\n";
        fwrite($handle, $data);
        fclose($handle);
    }

    public static function uploadFile($newName = '', $dir = null)
    {
        try {
            if (!is_dir($_ENV['PUBLIC_DIR'])) {
                mkdir($_ENV['PUBLIC_DIR']);
            }
            $uploadDir = $dir ?? $_ENV['PUBLIC_DIR'];
            // $uploadedFiles = '';
            $file_name = $_FILES['upload_file']['name'];
            $ext = substr($file_name, strrpos($file_name, '.'));
            $mF = ($newName == '' ? $file_name : $newName . $ext);
            $tmp_name = $_FILES['upload_file']['tmp_name'];
            $file_name = str_replace(" ", "_", $file_name);
            $file_name = str_replace("/", "_", $file_name);
            $file_name = str_replace(".", "_" . time() . ".", $file_name);
            $uploaded = move_uploaded_file($tmp_name, $uploadDir . $mF);
            if (!$uploaded) throw new \Exception("File not uploaded");
            /*
            foreach ($_FILES['upload_files']['name'] as $file_name) {
                $tmp_name = $_FILES['upload_files']['tmp_name'][$count];
                $file_name = str_replace(" ", "_", $file_name);
                $file_name = str_replace(".", "_" . time() . ".", $file_name);
                $uploaded = move_uploaded_file($tmp_name, $_ENV['PUBLIC_DIR'] . $file_name);
                if (!$uploaded) throw new \Exception("File not uploaded");
                if ($count == (sizeof($_FILES['upload_files']['tmp_name']) - 1)) {
                    $uploadedFiles .= $file_name;
                } else {
                    $uploadedFiles .= $file_name . ',';
                }
                $count++;
            }*/
            return $mF;
        } catch (\Throwable $th) {
            self::logError($th->getCode(), $th->getMessage());
            //            http_response_code(PRECONDITION_FAILED_ERROR_CODE);
            return null;
        }
    }

    /**
     * @param array $recipients an array containing the address and name for recipients of this email [['address', 'name'], []...]
     * @param string $subject The subject message
     * @param string $body The body oof the email. Supports html
     * @param array $attachments An array of attachments  [['path', 'name'], [...]...]. This field is not required.
     * @return void
     */
    public static function sendMail(array $recipients, string $subject, string $body, array $attachments = [])
    {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        $footer = "<hr> <h4>Click <a href='http://psms.mgickenya.org:81/event-management/admin/'>here</a> to open event management application. </h4>";
        $body .= $footer;
        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host = $_ENV['MAILER_HOST'];                     //Set the SMTP server to send through
            $mail->SMTPAuth = true;                              //Enable SMTP authentication
            $mail->Username = $_ENV['MAILER_ADDRESS'];                     //SMTP username
            $mail->Password = $_ENV['MAILER_PASSWORD'];                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
            $mail->Port = $_ENV['MAILER_PORT'];                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom($_ENV['MAILER_ADDRESS'], $_ENV['MAILER_NAME']);
            foreach ($recipients as $recipient) {
                $mail->addAddress($recipient['address'], $recipient['name']);
            }

            // $mail->addAddress('ellen@example.com');               //Name is optional
            $mail->addReplyTo('noreply@example.com', 'No Reply');
            //            $mail->addCC('cc@example.com');
            //            $mail->addBCC('bcc@example.com');

            //Attachments
            foreach ($attachments as $attachment) {
                $mail->addAttachment($attachment['path'], $attachment['name'] ?? '');
            }

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $body;
            //            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
//            echo 'Message has been sent';
        } catch (\Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            self::logError($e->getCode(), $e->getMessage());
        }
    }

    /***
     * This function takes an integer number $num and generates a string similar to columns in a spreadsheet.
     * ie A, B, ...... AA, AB, ..... BA, and so on.
     * given a number 0 = A, 1 = B and so on...
     * @param Integer $num
     *
     *
     * @return String corresponding column string
     */
    public static function getColumnLabel(int $num): string
    {
        $numeric = $num % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval($num / 26);
        if ($num2 > 0) {
            return self::getColumnLabel($num2 - 1) . $letter;
        } else {
            return $letter;
        }
    }

    /*******
     * This function calculates the distance between two points A and B given the gps coordinates of the points
     * @param $pointA array of [lat, lon]
     * @param $pointB array of [lat, lon]
     *
     * @return double The distance.
     * */
    public static function getDistanceFromCoordinates(array $pointA, array $pointB)
    {
        $radius = 6378; // Radius of the earth in km
        $dLat = self::deg2rad($pointB[0] - $pointA[0]);
        $dLon = self::deg2rad($pointB[1] - $pointA[1]);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(self::deg2rad($pointA[0])) * cos(self::deg2rad($pointB[0])) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $radius * $c;
    }

    public static function deg2rad($deg)
    {
        return $deg * (pi() / 180);
    }

    /***
     * This method gets facilities from KMHFL and loads them in our database.
     * There is a county filter for facilities update the code for defferent county codes as per KMHL. Current is Nairobi
     * Runs only on cli
    */
/*    public static function getFacilitiesFromKmhfl() {
        (PHP_SAPI !== 'cli' || isset($_SERVER['HTTP_USER_AGENT'])) && die('cli only');
        try{
            require_once __DIR__ . '/../../../vendor/autoload.php';
            $county = "6c34a4b5-af53-44f9-9c1e-17fdf438dc1f";
            $url = "http://api.kmhfl.health.go.ke/api/facilities/material/?fields=id,code,name,facility_type_name,county,operation_status_name&county={$county}";
            echo " \033[93m Getting Facilities from KMHFL County code:  \033[0m $county \n";
            echo " \033[96m Getting {$url} ..... \033[0m\n";
            $facilities = [];
            ini_set('memory_limit', '100m');
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', "Authorization: Bearer HCH79f6OHfOur8nK7k5pIXhklO5rrK"));

            $data = curl_exec($curl);
            $jdata = json_decode($data);

            curl_close($curl);
            $facilities = $jdata->results;

            $pages = $jdata->total_pages;
            $currentPage = $jdata->current_page;
            $newUrl = $jdata->next;
            while ($currentPage < $pages) {
                echo " \033[96m Getting {$newUrl} ..... \033[0m \n";
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $newUrl);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', "Authorization: Bearer HCH79f6OHfOur8nK7k5pIXhklO5rrK"));

                $data = curl_exec($curl);
                $jdata = json_decode($data);

                curl_close($curl);
                array_push($facilities, ...$jdata->results);
                $currentPage = $jdata->current_page;
                $newUrl = $jdata->next;

            }

            echo "\033[32m Success:  \033[0m Facilities Retrieved successfully. \n";
            echo " \033[93m Loading Facilities to Database...  \033[0m  \n";
            DB::beginTransaction();
            foreach ($facilities as $facility){
                $mflCode = $facility->code;
                $name = $facility->name;
                $countyName = $facility->county_name;
                echo " \033[96m Loading ..... \033[0m {$name} \n";
                if ($mflCode != null && $mflCode != ""){
                    $county = County::where('name', $countyName)->first();
                    if ($county == null) throw new \Exception("County {$countyName} not found");
                    $f = Facility::where('mfl_code', $mflCode)->first();
                    if ($f == null){
                        echo " {$name} not found. Inserting ..... \033[0m \n";
                        $f = Facility::create([
                            'name' => $name, 'mfl_code' => $mflCode, 'county_code' => $county->code
                        ]);
                    } else{
                        echo " {$name} found. Updating ..... \033[0m \n";
                        $f->update(['name' => $name]);
                    }
                } else echo " \033[93m  No MFL code for {$name} -----------> Skipping. \033[0m  \n";

            }
            DB::commit();

            echo "\033[32m Success:  \033[0m Facilities Inserted successfully. \n";
//            echo json_encode($jdata);
        } catch (\Throwable $th){
            DB::rollback();
            echo " \033[31m Error: \033[91m" .  $th->getMessage();
            //self::logError($th->getCode(), $th->getMessage());
        }
    }*/
}
