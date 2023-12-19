<?php

class File_Ctrl {

    public function __construct() {
        
    }

    public function uploadFile($f3){
        $file = $_FILES['file'];
        $fileName = $file['name'];

        $uploadsFolder = './files/';
        $uploadFilePath = $uploadsFolder . $fileName;
        if( move_uploaded_file( $file['tmp_name'], $uploadFilePath ) ){
            echo json_encode([
                'status' => true,
                'message' => 'Archivo subido con éxito.',
                // 'data' => 'files/' . $fileName
            ]);     
            return;
        }
        echo json_encode([
            'status' => false,
            'message' => 'Error al subir el archivo.'
        ]);    
        
    }
    
    public function getFile( $f3 ){
        $fileName = $f3->get('PARAMS.fileName');
        $filePath = './files/' . $fileName;

        if( file_exists( $filePath ) ){
            readfile($filePath);
            return;
        }
        echo json_encode([
            'status' => false,
            'data' => 'No se encuentra este archivo.'
        ]);    


    }

}