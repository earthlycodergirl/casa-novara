<?php
class Users{
    public $UsersList;
    public $ActiveUsers;
    public $InactiveUsers;

    public function __construct(){

    }
}



class User{
    public $UserId;
    public $Username;
    public $Created;
    public $FirstName;
    public $LastName;
    public $LastLogin;
    public $UserEmail;
    public $UserPhone;
    public $UserType;
    public $Message;
    public $Error = 0;
    public $Errors = array();
    public $RedirectUrl;

    public function __construct($action="get",$elems){
        switch($action){
            case "login":
                $this->userLogin($elems);
                break;
            case "add":
                $this->addUser($elems);
                break;
            case "update":
                $this->updateUser($elems);
                break;
            case "get":
                $this->UserId = $elems;
                $this->getUser();
                break;
        } // End of switch
    } // End of construct function



    /** ----- USER LOGIN ------ **/
    public function userLogin($vars){
        if(isset($vars['user_login'])){
            // Check if the user is in the TB
            $getU = new SqlIt("SELECT * FROM users WHERE username = ? AND password = ?","select",array($vars['user'],md5($vars['pass'])));
            if($getU->NumResults > 0){
                // add last login
                $this->UserId = $getU->Response[0]->uid;
                new SqlIt("UPDATE users SET last_login = NOW() WHERE uid = ?","update",array($this->UserId));
            }else{
                $this->Error = 1;
                $this->Errors[] = 'The login information you entered was incorrect. Please try again.';
            }
        }
    }



    /** ----- ADD NEW USER ------- **/
    public function addUser($vars){
        if(isset($vars['submit_user_info'])){

            if($this->validateInput($vars)){
                // Add new user
                $addUser = new SqlIt("INSERT INTO users (username,password,creation_date,name,email,position,active) VALUES (?,?,NOW(),?,?,?,?)","insert",array($vars['username'],md5($vars['password']),$vars['name'],$vars['email'],$vars['position'],$vars['active']));

                if($addUser){
                    // Get the newly created ID from the database
                    $getUid = new SqlIt("SELECT * FROM users WHERE username = ? ORDER BY uid DESC LIMIT 1","select",array($vars['username']));
                    if($getUid->NumResults == 1){
                        $uu = $getUid->Response[0];
                        $this->UserId = $uu->uid;
                        $this->Username = $uu->username;
                        $this->Created = $uu->creation_date;
                        $this->FirstName = $uu->name;
                        $this->UserEmail = $uu->email;
                        $this->UserType = $uu->active;
                        $this->Message = 'The user was added successfully.';
                    }else{
                        $this->Error = 1;
                        $this->Errors[] = 'Lo sentimos, tuvimos un error al agregar el usuario. Favor de intentar de nuevo. Si el problema persiste, favor de contactar a tu administrador web.';
                    }
                }else{
                    $this->Error = 1;
                    $this->Errors[] = 'Lo sentimos, tuvimos un error al agregar el usuario. Favor de intentar de nuevo. Si el problema persiste, favor de contactar a tu administrador web.';
                }
            }
        }else{
            // return an error for invalid form submission
            $this->Error = 1;
            $this->Errors[] = 'Lo sentimos el formulario que ingreso no es correcto. Favor de contactar a su administrador de web.';
        }
    }



    /** ----- UPDATE USER ------- **/
    public function updateUser($vars){
        if(isset($vars['submit_user_info'])){

            if($this->validateInput($vars)){
                // Add new user
                $updateUser = new SqlIt("UPDATE users SET username=?,password=?,name=?,email=?,position=?,active=? WHERE uid = ?","update",array($vars['username'],md5($vars['password']),$vars['name'],$vars['email'],$vars['position'],$vars['active'],$vars['user_id']));

                if($updateUser){
                    $this->UserId = $vars['user_id'];
                    $this->getUser();
                    if($this->Error == 0){
                        $this->Message = 'The user was updated successfully.';
                    }
                }else{
                    $this->Error = 1;
                    $this->Errors[] = 'We apologize, we encountered and error while updating this user. Please try again, if the problem persists, contact your web administrator.';
                }
            }
        }else{
            // return an error for invalid form submission
            $this->Error = 1;
            $this->Errors[] = 'We apologize the information could not be submitted. Please check all fields are complete. If the problem persists please contact your web administrator.';
        }
    }



    /** ----- GET USER ----- **/
    public function getUser(){
        if($this->UserId != ''){
            $getUid = new SqlIt("SELECT * FROM users WHERE uid = ?","select",array($this->UserId));
            if($getUid->NumResults == 1){
                $uu = $getUid->Response[0];
                $this->Username = $uu->username;
                $this->Created = $uu->creation_date;
                $this->FirstName = $uu->name;
                $this->UserEmail = $uu->email;
                $this->UserType = $uu->active;
                $this->LastLogin = $uu->last_login;
            }else{
                $this->Error = 1;
                $this->Errors[] = 'Lo sentimos, tuvimos un error al encontrar el usuario en la base de datos. Favor de intentar de nuevo. Si el problema persiste, favor de contactar a tu administrador web.';
                $this->RedirectUrl = 'index.php?nouser=1';
            }
        }else{
            $this->Error = 1;
            $this->RedirectUrl = 'index.php?nosess=1';
        }
    }





    /** ------ VALIDATE USER INPUT FORM ------ **/
    public function validateInput($vars){
        // check that all the elements are present
        if(!isset($vars['username'])){
            $this->Errors[] = 'Favor de ingresar un usuario con un minimo de 3 caracteres';
        }
        if(!isset($vars['pass_user'])){
            $this->Errors[] = 'Favor de ingresar una contraseña valido';
        }
        if(!isset($vars['first_name']) || strlen($vars['first_name']) < 3){
            $this->Errors[] = 'Favor de ingresar los nombres del usuario con un minimo de 3 caracteres';
        }
        if(!isset($vars['last_name']) || strlen($vars['last_name']) < 3){
            $this->Errors[] = 'Favor de ingresar los apellidos con un minimo de 3 caracteres';
        }
        if(!isset($vars['user_email'])){
            $this->Errors[] = 'Favor de ingresar un email para asociar el usuario';
        }
        if (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
            $this->Errors[] = 'Favor de ingresar un email valido';
        }

        if(!empty($this->Errors)){
            $this->Error = 1;
            return false;
        }else{
            return true;
        }
    }
}


?>