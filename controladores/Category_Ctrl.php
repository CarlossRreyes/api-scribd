<?php
class Category_Ctrl
{
    public $M_Category = null;

    public function __construct()
    {
        $this->M_Category = new M_Category();
    }
  
    public function loadCategories($f3){
        $category = $this->M_Category->find([" state = 'A' "]);            
        if( !(count($category) > 0) ){
            echo json_encode([
                'status' => false,
                'message' => 'No hay datos.'
            ]);     
            return;
        }

        $listCategories = array();
        foreach( $category as $category ){
            $listCategories[] = $category->cast();
        }
        
        echo json_encode([
            'status' => true,
            'message' => 'Datos recuperados con éxito',   
            'data' => $listCategories       
        ]); 
 
    }
   
}