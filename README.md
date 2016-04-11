# GoogleDriveApi

## SUMMARY

- おもむろにファイルをGoogleドライブにアップロードするだけです
- PHPのVerの問題でGoogle_Client v1を利用しています
- 上記の理由等で、いったんミニマムで作っています

## USAGE

- Configに下記の設定を追加してください
 - 鍵ファイルのPATH
 - APIに利用するGoogleServiceAccountのメールアドレス

- あとはおもむろに下記のパラメータを渡せばOKです
```php
require_once /path/to/g_drive.php

$dirPath  = 'foo';
$fileName = 'bar';
$fileInfo = array(
    'title'       => 'ファイル名',
    'description' => 'ファイル説明',
    'mimeType'    => 'text/csv',
);
lib\GDrive::addFile($dirPath, $fileName, $fileInfo);
```
