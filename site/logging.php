<?php
    require __DIR__ . '/vendor/autoload.php';
    require __DIR__ . '/connection.php';
    
    
    function getLastDeletionsNum($date, $conn) {
        $delCount = $conn->prepare("SELECT COUNT(*) FROM log WHERE operation_date >= DATE_SUB(:date, INTERVAL 1 DAY) AND operation='deletion'");
        $delCount->execute(array(':date' => $date));
        return $delCount->fetchColumn();
    }
    
    function getLastAdditionsNum($date, $conn) {
        $addCount = $conn->prepare("SELECT COUNT(*) FROM log WHERE operation_date >= DATE_SUB(:date, INTERVAL 1 DAY) AND operation='addition'");
        $addCount->execute(array(':date' => $date));
        return $addCount->fetchColumn();
    }
    
    $googleAccountKeyFilePath = __DIR__ . '/credentials.json';

    putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $googleAccountKeyFilePath );

    $client = new Google_Client();
    $client->useApplicationDefaultCredentials();

    $client->addScope(Google_Service_Sheets::SPREADSHEETS);   
    $service = new Google_Service_Sheets( $client );

    $spreadsheetId = '1VnG-a-i_zvM6wkWbaMNE_wFFhLyvidzMFFQ85LCQHCM';
    
    $range = "List";    
    
    $date = (new DateTime())->format('Y-m-d H:i:s');
    $additionsCount = getLastAdditionsNum($date, $connection);
    $deletionsCount = getLastDeletionsNum($date, $connection);
    
    $valueRange = new Google_Service_Sheets_ValueRange([
        'majorDimension' => 'ROWS',
        'values' => [
            [$date, $additionsCount, $deletionsCount]
        ]
    ]);
    
    $params = [
        'valueInputOption' => 'RAW'
    ];
    
    $service->spreadsheets_values->append(
        $spreadsheetId,
        $range, 
        $valueRange, 
        $params
    );
?>

