<?php
class User_Ctrl
{
    public $M_User = null;
    public $M_Person = null;
    public $M_Type_User = null;

    public function __construct()
    {
        $this->M_User = new M_User();
        $this->M_Person = new M_Person();
        $this->M_Type_User = new M_Type_User();
    }

    public function loadUserById($f3){
        $user = $this->M_User->load(["user_id = ? and state = 'A'", $f3->get('PARAMS.user_id')]); 
        if( !$user ){
            echo json_encode([
                'status' => false,
                'message' => 'No se ha podido ecnontrar este usuario.'
            ]);     
            return;
            
        }     
        
        $type_user = $this->M_Type_User->load(["type_user_id = ? and state = 'A'", $user->get('type_user_id')]);
        $person = $this->M_Person->load(["person_id = ? and state = 'A'", $user->get('person_id')]);
        $dataUser = $user->cast();
        $dataUser['person_id'] = $person->cast();
        $dataUser['type_user_id'] = $type_user->cast();
        echo json_encode([
            'status' => true,
            'message' => 'Datos encontrados con éxito.',
            'data' => $dataUser
        ]);     

    }
  
    public function createUser($f3){
        $user = $this->M_User->find(["email = ? and state = 'A'", $f3->get('POST.email')]);            
        if( ( count($user) > 0 ) ){
            echo json_encode([
                'status' => false,
                'message' => 'Este correo ya esta registrado.'
            ]);     
            return;
        }

        // $person = $this->M_Person->load([""])
        $this->M_Persona->name = $f3->get('POST.name');
        $this->M_Persona->last_name = $f3->get('POST.last_name');
        $this->M_Persona->phone = $f3->get('POST.phone');
        $this->M_Persona->direction = $f3->get('POST.direction');
        $this->M_Persona->image = $f3->get('POST.image');
        $this->M_Persona->state = "A";
        if( $this->M_Persona->save() ){
            $newUser = new M_User();
            $newUser->email = $f3->get('POST.email');
            $password_encript = password_hash($f3->get("POST.password"), PASSWORD_BCRYPT);
            $newUser->password = $password_encript;
            $newUser->person_id = $f3->get('POST.person_id');
            $newUser->type_user_id = 2;
            $newUser->state = "A";
            if( $this->M_User->save() ){
                echo json_encode([
                    'status' => true,
                    'message' => 'Registro realizado con éxito.',                            
                ]); 
                return;             

            }
            echo json_encode([
                'status' => false,
                'message' => 'Hubo un error al registrar este usuario.',
                // 'password' => password_hash( $f3->get('POST.password'), PASSWORD_BCRYPT)
            ]);  
            return; 
        }




        echo json_encode([
            'status' => false,
            'message' => 'Parece que hubo un error al registrar este usuario.',
            // 'password' => password_hash( $f3->get('POST.password'), PASSWORD_BCRYPT)
        ]);   
    }
   
}