<?php
class Auth_Ctrl
{
    public $M_User = null;

    public function __construct()
    {
        $this->M_User = new M_User();
    }
  
    public function login($f3){
        $user = $this->M_User->load(["email = ? and state = 'A'", $f3->get('POST.email')]);            
        if( !$user ){
            echo json_encode([
                'status' => false,
                'message' => 'El correo electrónico es incorrecto.'
            ]);     
            return;
        }

        if( password_verify($f3->get('POST.password'), $user->get('password')) ){
            $data = array();
            $data['user_id'] = $user->get('user_id');   
            $data['email'] = $user->get('email');   
            echo json_encode([
                'status' => true,
                'message' => 'Bienvenido',   

                'data' => $data           
            ]); 
            return;             
        }

        echo json_encode([
            'status' => false,
            'message' => 'La contraseña es incorrecta.',
            // 'password' => password_hash( $f3->get('POST.password'), PASSWORD_BCRYPT)
        ]);   
    }
   
}