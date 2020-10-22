<?
class UserController extends BaseController
{
    public function showAuth(Request $request)
    {
        if(!self::checkAuth()){
            $this->render('auth', [], 'Todo');
        }else{
            header('Location: /');
        }
        
    }

    public static function checkAuth()
    {
        if($_SESSION['user']['id'] > 0){
            return true;
        }else{
            return false;
        }
    }


    public function logout(Request $request)
    {
        UserModel::logout();
        header('Location: /auth');
    }

    public function makeAuth(Request $request)
    {
        $status = 'fail';
        $err = '';
        
        $login = htmlspecialchars($request->post['login']);
        $pass = htmlspecialchars($request->post['pass']);
        
        $result = UserModel::login($login, $pass);

        if($result){
            $status = 'success';
        }else{
            $err = 'Неверный логин или пароль';
        }

        $json = ['status' => $status, 'err' => $err];
        
        echo json_encode($json);
        
    }
}
?>