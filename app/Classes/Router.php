<?
class Router
{
    private static $instances = [];
    public static $routes = [];

    protected function __construct() { }
    protected function __clone() { }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public static function getInstance()
    {
        $class = static::class;
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static();
        }

        return self::$instances[$class];
    }


    public function init($url)
    {
        
        $request = new Request();

        foreach ($this->routes as $pattern => $route){
            
            preg_match_all("/\((.*?)\)/", $pattern, $matches);

            $patternOut = preg_replace_callback(
                "/\((.*?)\)/",
                function ($matches) {
                    $s = explode(':',$matches[0]);
                    return '('.$s[1];
                },
                $pattern
            );

            
            $patternOut = "#".(str_ireplace('/','\\/',$patternOut))."#";
                        
            if (preg_match($patternOut, $url['path'], $params)){
                
                array_shift($params);

                $request->get = $_GET;
                $request->params = array_values($params);

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $request->post = $_POST;
                }

                if (!empty($_SESSION)) {
                    $request->session = $_SESSION;
                }
                
                if($route['auth']){
                    
                    $isAuth = UserController::checkAuth($request);

                    if($isAuth){
                        $this->navigate($route['controller'], $route['action'], $request);
                    }else{
                        $this->navigate('UserController', 'showAuth' , $request);
                    }

                    break;
                }

                $this->navigate($route['controller'], $route['action'], $request);
                break;
            }
        }

    }

    public function addRoute($pattern, $controller, $action, $isCheckAuth = false)
    {
        
        $this->routes[$pattern] = ['controller' => $controller, 'action' => $action, 'auth' => $isCheckAuth];        
    }

    public function navigate($controller, $action, Request $request)
    {
        return call_user_func([new $controller, $action], $request);   
    }
}

?>