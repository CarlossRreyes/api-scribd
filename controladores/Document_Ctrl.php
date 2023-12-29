<?php
class Document_Ctrl {
    public $M_User = null;
    public $M_Document = null;
    public $M_Document_Category = null;
    public $M_Comment = null;
    public $M_Person = null;

    public function __construct()
    {
        $this->M_User = new M_User();
        $this->M_Document = new M_Document();
        $this->M_Document_Category = new M_Document_Category();
        $this->M_Comment = new M_Comment();
        $this->M_Person = new M_Person();
    }

    public function loadAllDocuments($f3){
        $documents = $this->M_Document->find([ "state = 'A'" ]);
        if( !(count($documents) > 0) ){
            echo json_encode([
                'status' => false,
                'message' => 'No hay datos.'
            ]);     
            return;
        }

        $listDocuments = array();
        foreach( $documents as $document ){
            $newDocumentCategory = new M_Document_Category();
            $documentCategories = $newDocumentCategory->find([ "document_id = ?", $document->get('document_id') ]);
            foreach( $documentCategories as $dc ){
                $document = $this->M_Document->load([ "document_id = ?", $dc->get('document_id') ]);
                $comments = $this->M_Comment->find([ "document_id = ?", $document->get('document_id')]);
                $listComents = array();
                foreach( $comments as $comment ){
                    $user = $this->M_User->load([ "user_id = ?", $comment->get('user_id') ]);
                    $person = $this->M_Person->load([ "person_id = ?", $user->get('person_id')]);

                    $dataComment = $comment->cast();
                    $dataUser = $user->cast();
                    $dataUser['person_id'] = $person->cast();
                    $dataComment['user_id'] = $dataUser;
                    $listComents[] = $dataComment;
                }
                $dataDocument = $dc->cast();
                $dataDocumentID = $document->cast();
                $dataDocumentID['comments'] = $listComents;                
                $dataDocument['document_id'] = $dataDocumentID;
                $listDocuments[] = $dataDocument;

            }

        }
        
        echo json_encode([
            'status' => true,
            'message' => 'Datos recuperados con éxito',   
            'data' => $listDocuments       
        ]); 

    }


    public function deleteDocument($f3){

        $this->M_Document_Category->load([ "document_id = ?",  $f3->get('PARAMS.document_id') ]);
        // if( (count ($documents) > 0) ){
        //     echo json_encode([
        //         'status' => false,
        //         'message' => 'No se encuentra este registro.'
        //     ]);     
        //     return;
        // }
        
        $this->M_Document_Category->erase();
        $this->M_Document->load([ "document_id = ?",  $f3->get('PARAMS.document_id') ]);
        if( $this->M_Document->erase() ){
                echo json_encode([
                    'status' => true,
                    'message' => 'El documento fue eliminado.'
                ]);     

        }







    }

    public function loadDocumentsByUser($f3){
        $documents = $this->M_Document->find([ "user_id = ?",  $f3->get('PARAMS.user_id') ]);
        if( !(count($documents) > 0) ){
            echo json_encode([
                'status' => false,
                'message' => 'No hay datos.'
            ]);     
            return;
        }

        $listDocuments = array();
        foreach( $documents as $document ){
            $newDocumentCategory = new M_Document_Category();
            $documentCategories = $newDocumentCategory->find([ "document_id = ?", $document->get('document_id') ]);
            foreach( $documentCategories as $dc ){
                $document = $this->M_Document->load([ "document_id = ?", $dc->get('document_id') ]);
                $dataDocument = $dc->cast();
                $dataDocument['document_id'] = $document->cast();
                $listDocuments[] = $dataDocument;

            }

        }
        
        echo json_encode([
            'status' => true,
            'message' => 'Datos recuperados con éxito',   
            'data' => $listDocuments       
        ]); 

    }

    public function loadDocumentsByIdCategory($f3){

        $newDocumentCategory = new M_Document_Category();
        $documentCategories = $newDocumentCategory->find([ "category_id = ? ", $f3->get('PARAMS.category_id') ]);
        if( !(count($documentCategories) > 0) ){
            echo json_encode([
                'status' => false,
                'message' => 'No hay datos.'
            ]);     
            return;
        }

        $listDocuments = array();
        foreach( $documentCategories as $documentC ){
            $document = $this->M_Document->load([ "document_id = ?", $documentC->get('document_id') ]);

            $dataDocument = $documentC->cast();
            $dataDocument['document_id'] = $document->cast();
            $listDocuments[] = $dataDocument;
        }

        echo json_encode([
            'status' => true,
            'message' => 'Datos recuperados con éxito',   
            'data' => $listDocuments       
        ]); 



        //TODO: --------- 
        // $documents = $this->M_Document->find([ "state = 'A'" ]);
        // if( !(count($documents) > 0) ){
        //     echo json_encode([
        //         'status' => false,
        //         'message' => 'No hay datos.'
        //     ]);     
        //     return;
        // }

        // $listDocuments = array();
        // foreach( $documents as $document ){
        //     $newDocumentCategory = new M_Document_Category();
        //     $documentCategories = $newDocumentCategory->find([ "document_id = ? ", $document->get('document_id') ]);
        //     foreach( $documentCategories as $dc ){
        //         $document = $this->M_Document->load([ "document_id = ?", $dc->get('document_id') ]);
        //         $comments = $this->M_Comment->find([ "document_id = ?", $document->get('document_id')]);
        //         $listComents = array();
        //         foreach( $comments as $comment ){
        //             $user = $this->M_User->load([ "user_id = ?", $comment->get('user_id') ]);
        //             $person = $this->M_Person->load([ "person_id = ?", $user->get('person_id')]);

        //             $dataComment = $comment->cast();
        //             $dataUser = $user->cast();
        //             $dataUser['person_id'] = $person->cast();
        //             $dataComment['user_id'] = $dataUser;
        //             $listComents[] = $dataComment;
        //         }
        //         $dataDocument = $dc->cast();
        //         $dataDocumentID = $document->cast();
        //         $dataDocumentID['comments'] = $listComents;                
        //         $dataDocument['document_id'] = $dataDocumentID;
        //         $listDocuments[] = $dataDocument;

        //     }

        // }
        
        // echo json_encode([
        //     'status' => true,
        //     'message' => 'Datos recuperados con éxito',   
        //     'data' => $listDocuments       
        // ]); 


    }

    public function editDocument($f3){
        $document = $this->M_Document->load([" document_id = ? ",  $f3->get('POST.document_id') ]);
        if( !$document ){
            echo json_encode([
                'status' => false,
                'message' => 'Este documento no ha sido encontrado.'
            ]);     
            return;
        }

        $document->user_id = $f3->get( 'POST.user_id' );
        $document->name = $f3->get( 'POST.name' );
        $document->description = $f3->get( 'POST.description' );
        $document->rute = $f3->get( 'POST.rute' );
        $document->file = $f3->get( 'POST.file' );
        $document->image = $f3->get( 'POST.image' );
        $document->date_upload = $f3->get( 'POST.date_upload' );
        $document->type = $f3->get( 'POST.type' );
        $document->state = $f3->get( 'POST.state' );
        // $document->download_permission = $f3->get( 'POST.download_permission' );
        $downloadPermissionValue = $f3->get('POST.download_permission') ?? 0;
        $document->download_permission = $downloadPermissionValue;

        if( $document->update() ){

            $newDocumentCategory = new M_Document_Category();
            $documentCategory = $newDocumentCategory->load([ "document_id = ?", $document->get('document_id')]);

            // $documentCategory->document_id = $document->get('document_id');
            $documentCategory->category_id = $f3->get('POST.category_id');
            if( $documentCategory->update() ){
                echo json_encode([
                    'status' => true,
                    'message' => 'Documento actualizado con éxito.'
                ]);
                return; 
            }

            echo json_encode([
                'status' => false,
                'message' => 'Ups! Hubo un error al editar el documento.'
            ]);
            
        }

        echo json_encode([
            'status' => false,
            'message' => 'Ups. Hubo un error al editar el documento',
            // 'password' => password_hash( $f3->get('POST.password'), PASSWORD_BCRYPT)
        ]); 
        
    }
  
    public function createDocument($f3){
        $document = $this->M_Document->find([" name = ?  ", $f3->get('POST.name') ]);            
        if( $document ){
            echo json_encode([
                'status' => false,
                'message' => 'Ups! Ya existe un documento con este nombre.'
            ]);     
            return;
        }

        $newDocument = new M_Document();
        $newDocument->user_id = $f3->get( 'POST.user_id' );
        $newDocument->name = $f3->get( 'POST.name' );
        $newDocument->description = $f3->get( 'POST.description' );
        $newDocument->rute = $f3->get( 'POST.rute' );
        $newDocument->file = $f3->get( 'POST.file' );
        $newDocument->image = $f3->get( 'POST.image' );   
        $newDocument->date_upload = $f3->get( 'POST.date_upload' );
        $newDocument->type = $f3->get( 'POST.type' );
        $newDocument->state = $f3->get( 'POST.state' );
        $newDocument->download_permission = $f3->get( 'POST.download_permission' );

        if( $newDocument->save() ){

            $newDocumentCategory = new M_Document_Category();
            $newDocumentCategory->document_id = $newDocument->get('document_id');
            $newDocumentCategory->category_id = $f3->get('POST.category_id');
            if( $newDocumentCategory->save() ){
                echo json_encode([
                    'status' => true,
                    'message' => 'Documento subido con éxito.'
                ]);
                return; 
            }

            echo json_encode([
                'status' => false,
                'message' => 'Ups! Hubo un error al subir el documento.'
            ]);
            
        }

        echo json_encode([
            'status' => false,
            'message' => 'Ups. Hubo un error al subir el documento',
            // 'password' => password_hash( $f3->get('POST.password'), PASSWORD_BCRYPT)
        ]);   
    }
   
}