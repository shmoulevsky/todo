<?class UserModel extends BaseModel{
    public static $table_name = 'users'; 

    public static function login($login, $pass)
    {

        $userModel = new UserModel();

        $pass = md5($pass);
        $user = $userModel->select(['id','name','login','name','lastname', 'group_id'], 'login = "'.$login.'" AND password = "'.$pass.'"', 'id desc')->getFirst();
        
        if($user['id'] > 0){

            $groupModel = new GroupModel();
            $group = $groupModel->select(['id', 'title'], 'id = '.$user['group_id'], 'id desc')->getFirst();
            
            $_SESSION['user']['id'] = $user['id'];
            $_SESSION['user']['login'] = $user['login'];
            $_SESSION['user']['name'] = $user['name'];
            $_SESSION['user']['lastname'] = $user['lastname'];
            $_SESSION['user']['group_id'] = $user['group_id'];
            $_SESSION['user']['group'] = $group['title'];

            return true;

        }else{

            return false;
        }

    }
    
    public static function logout()
    {
        
       if(isset($_SESSION['user'])){ unset($_SESSION['user']);}

    }

    public static function getId()
    {
        
        if(isset($_SESSION['user'])){ 
            return ($_SESSION['user']['id']);
        }else{
            return false;
        }
 
     }


}