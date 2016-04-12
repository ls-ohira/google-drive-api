<?php namespace lib;
/**
 * @see https://github.com/google/google-api-php-client/blob/master/UPGRADING.md
 */
require dirname(__FILE__) . '/../vendors/composers/autoload.php';
require dirname(__FILE__) . '/../etc/config.php';

if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}

class GDrive {
    /**
     * Returns an authorized API client.
     * @return Google_Client the authorized client object
     */
    static function getClient() {
        $credentials = new \Google_Auth_AssertionCredentials(
            \etc\Config::API_ACCOUNT,
            array(\Google_Service_Drive::DRIVE_FILE),
            file_get_contents(\etc\Config::AUTH_FILE)
        );

        $client = new \Google_Client();
        $client->setAssertionCredentials($credentials);
        if ($client->getAuth()->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion();
        }

        return $client;
    }

    static function addFile($dirId, $filePath, $fileInfo) {
        $client = self::getClient();
        $service = new \Google_Service_Drive($client);

        // 追加したいファイルオブジェクトを作成
        $file = new \Google_Service_Drive_DriveFile();
        $file->setTitle($fileInfo['title']);
        $file->setDescription($fileInfo['description']);
        $file->setMimeType($fileInfo['description']);

        // 親オブジェクト
        $parent = new \Google_Service_Drive_ParentReference();
        $parent->setId($dirId);
        $file->setParents(array($parent));

        try {
            $result = $service->files->insert($file, array(
                'data'        => file_get_contents($filePath),
                'mimeType'    => 'text/csv',
                'uploadType'  => 'multipart',
                'convert' =>  true,
            ));

            echo $result ? "[SUCCESS] Succeed upload\n" : "[ERROR] Failed upload\n";
        } catch (Exception $e) {
            print "An error occurred: {$e->getMessage()}\n";
        }
    }

    static function updateFile($dirId, $fileId, $filePath, $fileInfo) {
        $client = self::getClient();
        $service = new \Google_Service_Drive($client);

        // 追加したいファイルオブジェクトを作成
        $file = new \Google_Service_Drive_DriveFile();
        $file->setTitle($fileInfo['title']);
        $file->setDescription($fileInfo['description']);
        $file->setMimeType($fileInfo['description']);

        // 親オブジェクト
        $parent = new \Google_Service_Drive_ParentReference();
        $parent->setId($dirId);
        $file->setParents(array($parent));

        try {
            $result = $service->files->patch($fileId, $file, array(
                'data'        => file_get_contents($filePath),
                'mimeType'    => $config['mimeType'],
		'uploadType'  => $config['uploadType'],
                'convert' =>  true,
            ));

            echo $result ? "[SUCCESS] Succeed upload\n" : "[ERROR] Failed upload\n";
        } catch (Exception $e) {
            print "An error occurred: {$e->getMessage()}\n";
        }
    }
}