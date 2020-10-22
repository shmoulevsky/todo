<?
class App
{
    function __construct()
    {
        $this->router = Router::getInstance();
        $this->router->init(parse_url($_SERVER['REQUEST_URI']));
        
       
    }

}
?>