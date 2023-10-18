<?php
header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
set_time_limit(3600);
putenv("PATH=C:\wamp64\www\gmail\venv\Scripts;" . getenv("PATH"));


require 'vendor/autoload.php';
require 'simple_html_dom.php';

// Start session to manage tokens and authorization code
session_start();
$dictionary = [];
$imagesInBody = [];


$client = new Google_Client();
$client->setAuthConfig('credentials.json');
$client->addScope(Google_Service_Gmail::GMAIL_READONLY);
$client->setRedirectUri('http://localhost:8080/gmail/gmail.php');
$client->setHttpClient(new \GuzzleHttp\Client([
    'verify' => false,
]));



try {
    // Check if an access token already exists in the session and if it's expired
    if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
        $client->setAccessToken($_SESSION['access_token']);
        
        // Check token expiration
        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $_SESSION['access_token'] = $client->getAccessToken();
            } else {
                // No valid access token or refresh token is available
                unset($_SESSION['access_token']);
            }
        }
    }

    // If there's no valid access token, attempt to get one using the authorization code
    if (!isset($_SESSION['access_token']) || !$_SESSION['access_token']) {
        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            if (!isset($token['error'])) {
                $_SESSION['access_token'] = $token;
                $client->setAccessToken($token);
            } else {
                die("Error fetching the access token: " . $token['error']);
            }
        } else {
            // Redirect to OAuth 2.0 consent screen
            $authUrl = $client->createAuthUrl();
            header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
            exit;
        }
    }
} catch (Exception $e) {
    // Handle exceptions, including the "invalid_grant" error
    if ($e->getCode() == 400 || $e->getCode() == 401) {
        $errorDetails = json_decode($e->getMessage(), true);
        if (isset($errorDetails['error_description'])) {
            // Handle "invalid_grant" error by clearing credentials and redirecting to re-authentication
            unset($_SESSION['access_token']);
            unset($_SESSION['refresh_token']);
            $authUrl = $client->createAuthUrl();
            header('Location: ' . $authUrl);
            exit;
        } else {
            // Handle other errors without a description
            error_log("Invalid grant error occurred");
            header("Location: http://localhost:8080/gmail/gmail.php");
            exit;
        }
    } else {
        // Handle other exceptions
        error_log("An error occurred: " . $e->getMessage());
        echo "An error occurred: " . $e->getMessage();
    }
}


$service = new Google_Service_Gmail($client);

function getBodyData($payload) {

        $bodyData = '';
        if ($payload->getBody() && $payload->getBody()->size > 0) {
            $rawData = $payload->getBody()->data;
            $sanitizedData = strtr($rawData, '-_', '+/');
            $bodyData .= base64_decode($sanitizedData);
        }
    
    foreach ($payload->getParts() as $part) {
      
        $bodyData .= getBodyData($part);
    }
 
    return $bodyData;
}

// Helper function to extract attachments
function getAttachments($message, $service) {
    $attachments = [];
    $parts = $message->getPayload()->getParts();
    foreach ($parts as $part) {
        if (!empty($part->getFilename())) {

            $attachmentId = $part->getBody()->attachmentId;
            $attachmentData = $service->users_messages_attachments->get('me', $message->getId(), $attachmentId);
            // $data = strtr($attachmentData['data'], array('-' => '+', '_' => '/'));
            $data = base64_decode(strtr($attachmentData['data'], '-_', '+/'));
            $attachments[] = [
                'filename' => $part->getFilename(),
                'mimeType' => $part->getMimeType(),
                'data'     => $data,
                'id' => $attachmentId,
            ];
        }
    }
    return $attachments;
}

function extractIdAfterText($string) {
    if (preg_match('/(דרכון מספר|תז|מספר זהות|מספר הזהות|זהות|ת\.ז|תעוד|ת\"ז)[^\d]*((?<!\d)[A-Z0-9]{8}(?!\d)|\d+)/u', $string, $matches)) {
        return $matches[2];
    }
    return null;
}

function extractDigits($string) {
    preg_match_all('/\b\d{9}\b/', $string, $matches);
    return isset($matches[0]) ? $matches[0] : [];
}

function getDigitsInfo($string) {
    $digits = extractDigits($string);
    if (empty($digits)) {
        return null;
    } else {
        return implode(", ", $digits);
    }
}

function replaceCidImages($htmlContent, $attachments) {
    foreach ($attachments as $attachment) {
        $cid = str_replace('@', '', $attachment['filename']);
        $data = $attachment['data'];
        $mimeType = $attachment['mimeType'];
        
        $base64Data = base64_encode($data);
        $dataUrl = "data:$mimeType;base64,$base64Data";

        $htmlContent = str_replace("cid:$cid", $dataUrl, $htmlContent);
    }
    return $htmlContent;
}

function removeRepetitiveNumbers($input) {
    // Split the input string by hyphens
    $parts = explode('-', $input);
    
    // Create an array to store unique numbers
    $uniqueNumbers = [];

    // Iterate through the parts
    foreach ($parts as $part) {
        // Remove leading and trailing spaces from the part (optional)
        $cleanedPart = trim($part);

        // Check if the cleaned part is not in the unique numbers array
        if (!in_array($cleanedPart, $uniqueNumbers)) {
            // Add the cleaned part to the unique numbers array
            $uniqueNumbers[] = $cleanedPart;
        }
    }

    // Join the unique numbers with hyphens to create the output string
    $output = implode('-', $uniqueNumbers);

    return $output;
}

function findHtmlContent($payload) {
    $htmlBodyPart = null;

    foreach ($payload->getParts() as $part) {
        if ($part['mimeType'] === 'text/html') {
            $htmlBodyPart = $part;
            break;
        }

        if (isset($part['parts'])) {
            // Recursively search in sub-parts
            $subHtmlBodyPart = findHtmlContent($part);
            if ($subHtmlBodyPart) {
                $htmlBodyPart = $subHtmlBodyPart;
                break;
            }
        }
    }

    if ($htmlBodyPart) {
        return base64_decode($htmlBodyPart['body']['data']);
    }

    // If HTML content is not found, check the message body
    if (isset($payload->body) && $payload->mimeType === 'text/html') {
        return base64_decode($payload->body->data);
    }

    return null;
}



// If we have a valid access token at this point, proceed to access Gmail

$params = [
    'labelIds' => ['INBOX'],
    // 'orderBy' => 'internalDate',
    'maxResults' => 7
];
$results = $service->users_messages->listUsersMessages('me', $params);
$messages = $results->getMessages();


if (!empty($messages)) {
  
$counter = 0;
foreach ($messages as $message) {
    $messageId = $message->getId();
    $fullMessage = $service->users_messages->get('me', $messageId);

    $payload = $fullMessage->getPayload();
    if (!$payload) {
        continue; // Skip this message if there's no payload
    }

    // Get Subject
    $headers = $payload->getHeaders();
    $subjectHeader = array_filter($headers, function($header) {
        return $header->getName() == 'Subject';
    });
    $subject = $subjectHeader ? current($subjectHeader)->getValue() : null;
    
    //echo "Subject: $socialID<br>";
    
    // Get Body
    $bodyData = getBodyData($payload);
    
    

    $escaped_subject = escapeshellarg($subject);
    //$escaped_body = escapeshellarg($bodyData);
    $finalFileName = ""; 

    $socialIDInSubject = shell_exec("python findSocialId.py $escaped_subject");
    $tempFile = tempnam(sys_get_temp_dir(), 'tempfile');
    //echo $tempFile . "<br>";
    //echo $socialIDInSubject . "<br>";
    file_put_contents($tempFile, $bodyData);
    if(!$socialIDInSubject){
        
        $command = "python findSocialId.py \"$tempFile\"";
        $socialIDInBody = shell_exec($command);

        //echo "body " . $socialIDInBody . "<br>";
        if(!$socialIDInBody){
            //echo "id not found, will look after name" . "<br>";
            //echo $escaped_subject;
            $nameInSubject = shell_exec("python findName.py $escaped_subject");
            if (strpos($nameInSubject, "None") !== false) {
                $command = "python findName.py \"$tempFile\"";
                $nameInBody = shell_exec($command);
                if (strpos($nameInBody, "None") !== false) {
                    continue;
                }
                else{
                    $socialIdCompleted = $nameInBody;
                    //echo "in body " . $nameInBody;
                }
                
            }
            else{
                $socialIdCompleted = $nameInSubject;
                //echo $nameInSubject;
            }
            
            
            
        }
        else{
            $unique_socialIDInBody = removeRepetitiveNumbers($socialIDInBody);
            
            $socialIdCompleted = $unique_socialIDInBody;
            //echo $unique_socialIDInBody . "<br>";
        }
        
    }
    else{
        $unique_socialIDInSubject = removeRepetitiveNumbers($socialIDInSubject);
        $socialIdCompleted = $unique_socialIDInSubject;
        //echo $unique_socialIDInSubject . "<br>";

    }
    
    echo $finalFileName;
    if($socialIdCompleted){
        $dictionary[$messageId] = [
            'id' => mb_convert_encoding($socialIdCompleted, 'UTF-8', 'auto'),
            'images' => []
        ];
    }
    else{
        continue;
    }
    
    
    
    //echo "Body: $bodyData<br>";

    // Get Attachments
    $attachments = getAttachments($fullMessage, $service);
    $bodyWithImages = replaceCidImages($bodyData, $attachments);
    //echo "Body: $bodyWithImages<br>";
    $images = [];
    $zips = [];
    $filesToDelete = [];
    foreach ($attachments as $attachment) {
        $filename = $attachment['filename'];
        echo $filename;
        $data = $attachment['data'];
        $mimeType = $attachment['mimeType'];
        
        if($mimeType=="image/jpeg" ||  $mimeType=="image/png"){
            // echo $mimeType;
            $dictionary[$messageId]['zipData'] = "";
            $dataDecode = base64_decode($data);
            // Convert data to Data URL
            $base64Data = base64_encode($data);
            $dataUrl = "data:$mimeType;base64,$base64Data";
            $images[] = $data;
        }
        if($mimeType==="application/zip" || $mimeType==="application/x-zip-compressed"){
            
        
        if ($filename !== null) {
            $filePath = $messageId.'_' . str_replace(' ', '', $filename).'.zip';
            if ($data !== false) {
                // Save the decoded data to a file
                $zips[] = $filePath;
                
                file_put_contents($filePath, $data);
                $filesToDelete[] = $filePath;
                //echo "Zip file saved successfully.";
            } else {
                echo "Failed to decode base64 data.";
            }
            
         
                
            
            //echo $tempFile;
            
            //unlink($tempFile);
            // file_put_contents($filename, $attachmentData);
            // echo "Attachment file name: $filename\n";
            // echo "Attachment ID: $attachmentId\n";
            // echo "Attachment Data: $attachmentData\n";
            
        }
        }
        

        // Display image using Data URL
        //echo "<img src='$dataUrl' alt='$filename' /><br>";
        
        //echo "Attachment: " . $attachment['filename'] . "-------" . $attachment['data'] . "<br>";
        // You can save or handle the attachment data here if needed
    }
    
    $mergedImagesArray = array_merge($images, $imagesInBody);
    $dictionary[$messageId]["images"] = $mergedImagesArray;
    $dictionary[$messageId]['zipData'] = $zips;

    // echo "<hr>";
    unlink($tempFile);
    
}
// echo "counter - " . $counter;
//file_put_contents("savedData/1.jpg", $dictionary['18b22b6b060a4628']['images'][0]);
// echo '<pre>';
// print_r($dictionary);
// echo '</pre>';
$savedDataDir = "C:\\wamp64\\www\\gmail\\savedData\\";


foreach ($dictionary as $messageKey => $messageData) {
    //echo "Message Key: $messageKey<br>";
    

    // Assuming $messageData['id'] contains the folder name
// Set the default charset to UTF-8


// Assuming $messageData['id'] contains the folder name
$folderNameHebrew = $messageData['id'];

//$folderPath = "C:\\wamp64\\www\\gmail\\savedData\\";
//mkdir($folderPath, 0777, true);
//$folderNameUtf8 = mb_convert_encoding("בנימין_רון", 'UTF-8', 'auto');
$sanitizedText = preg_replace('/[^\p{L}\p{N}_-]/u', '', $folderNameHebrew);

// Convert the sanitized text to a suitable encoding (e.g., UTF-8)
$utf8Text = mb_convert_encoding($sanitizedText, 'UTF-8', 'auto');

// Define the parent directory where you want to create the folder
$parentDirectory = 'savedData';

// Combine the parent directory and sanitized UTF-8 text to create the folder path
$directoryPath = $parentDirectory . DIRECTORY_SEPARATOR . $utf8Text;

//$directoryPath = "C:\\wamp64\\www\\gmail\\savedData\\{$folderNameHebrew}";

if (!file_exists($directoryPath)) {
    if (mkdir($directoryPath, 0777, true)) {
        echo "Folder created successfully: " . $directoryPath;
    } else {
        echo "Failed to create folder: " . $directoryPath;
        echo "Error: " . error_get_last()['message'];
    }
} 
// echo is_dir($savedDataDir . $folderNameUtf8) . " - " . $savedDataDir . $folderNameUtf8;
// // // Check if the folder already exists or create it
// if (!is_dir($savedDataDir . $folderNameUtf8)) {
//     mkdir($savedDataDir . $folderNameUtf8, 0777, true); 
//     echo "Folder created successfully: " . $savedDataDir . $folderNameUtf8;
// } else {
//     echo "Folder already exists: " . $savedDataDir . $folderNameUtf8;
// }
    // if (!file_exists("savedData/" . $personSocialID)) {
    //     mkdir("savedData/" . $personSocialID, 0777, true);  // The third parameter "true" allows the creation of nested directories
    // }
    $zip = new ZipArchive();
    foreach ($messageData['zipData'] as $index => $zipData) {
    $zipPath = $zipData;
    if ($zipPath && $zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
        //echo $zip->numFiles;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $fileInfo = $zip->statIndex($i);
            $fileName = $fileInfo['name'];
            // echo "File Name: " . $fileInfo['name'] . "<br>";
            // echo "File Size: " . $fileInfo['size'] . " bytes<br>";
            // echo "File Modified: " . date('Y-m-d H:i:s', $fileInfo['mtime']) . "<br>";
            echo "<br>";
            if (!empty(pathinfo($fileName, PATHINFO_EXTENSION)) && preg_match('/\.(jpg|jpeg|png)$/i', $fileName)) {
                // Extract the file to the specified folder
                if ($zip->extractTo($directoryPath . "/", $fileName)) {
                    //echo "Extracted: $fileName<br>";
                } else {
                    echo "Failed to extract: $fileName<br>";
                }
                
            }
        }

        $zip->close();
        //echo "loaded successfully";
    }
    else{
        echo "failed to load";
    }
}
foreach ($filesToDelete as $filePath) {
    if (file_exists($filePath)) {
        unlink($filePath);
        // Optionally, you can check if unlink was successful or handle any errors
    }
}

    foreach ($messageData['images'] as $index => $imageData) {
        // If image data is in base64 format and you want to save it:
        $filename = $directoryPath . "/image_$index.jpg";
        file_put_contents($filename, $imageData);
        
        // To display the image
        //echo "<img src='$filename' alt='Image $index for {$messageData['id']}'><br>";
    }
    
    //echo "<hr>";  // Separator for each message
}
// Helper function to recursively extract the body data


} else {
    echo 'No messages found in the inbox.';
}
?>
