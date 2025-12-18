<?php

mail('dirkjan@rollcomm.nl', 'upload and post', 'http://www.digitar.nu/api/uploadAndPost.php aangeroepen');

require_once '../login/config.php';

// file_put_contents('uploadAndPost.txt', print_r($_POST, true));
// file_put_contents('uploadAndFile.txt', print_r($_FILES, true));

ob_start();

$encrypter = new encryption;
$pass = $encrypter->encrypt($_POST['password']);
$sql = "SELECT ID, clientID, rights FROM 
	users 
WHERE 
	login = '".$mysqli->real_escape_string($_POST['username'])."' AND
	pass = '".$pass."' AND
	rights = '1'";

if (! $query = $mysqli->query($sql)) {
    printf("Errormessage: %s\n", $mysqli->error);
}
if ($query->num_rows > 0) {
    $user = $query->fetch_assoc();
    if ($user['rights'] == 1) {

        // setting the filenames
        $filename = uniqid().'.jpg';
        $outfilename = str_replace('.jpg', '.pdf', $filename);

        // getting the parent user for the upload folder
        $sql = "SELECT ID, login FROM users WHERE ID = '".$mysqli->real_escape_string($user['clientID'])."'";
        $query = $mysqli->query($sql);
        $destUser = $query->fetch_assoc();

        // upload the temp .jpg image
        move_uploaded_file($_FILES['files']['tmp_name'], $filename) or exit('test');
        $new_pic = imagecreatefromjpeg($filename);
        $new_pic = imagerotate($new_pic, 270, 0); // rotate the picture
        imagejpeg($new_pic, $filename, 60); // save the rotated picture

        // set the temp upload path
        $path = $_SERVER['DOCUMENT_ROOT'].'/api/';

        // set the final destination path
        $outPath = $_SERVER['DOCUMENT_ROOT'].'/login/clients/'.$destUser['login'].'/'.UNSORTED;
        // convert the .jpg to a .pdf file and compress it
        exec('convert -strip -interlace Plane -resize 50% '.$path.$filename.' '.$outPath.$outfilename.'');

        // echo $outPath.$outfilename;

        // delete the temp .jpg picture
        unlink($filename);

        $fileName = strstr($outfilename, '.', true);
        $fileName = str_replace('_', ' ', $fileName);
        $sql = "
		INSERT INTO files (
			uID,
			fID,
			uploaderID,
			file,
			name
		) VALUES (
			'".$mysqli->real_escape_string($destUser['ID'])."',
			'NULL',
			'".$mysqli->real_escape_string($user['ID'])."',
			'".$mysqli->real_escape_string($outfilename)."',
			'".$mysqli->real_escape_string($fileName)."'
		)
		";

        $query = $mysqli->query($sql);
    }
    $response['errorState'] = '0';
} else {
    $response['errorState'] = '1';
}

$out = ob_get_contents();
file_put_contents('errors.txt', $out, FILE_APPEND);

echo json_encode($response);
