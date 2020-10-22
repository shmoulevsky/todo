<?
abstract class BaseController
{   
    // render view file
    public function render($view ,$args = [], $template = ''){
        
        extract($args, EXTR_SKIP);

        $file = APP_DIR. '/View/'.$view.'.php'; 
        
        if($template != ''){
            $header = PUBLIC_DIR . '/templates/'.$template.'/header.php'; 
            $footer = PUBLIC_DIR . '/templates/'.$template.'/footer.php'; 
        }
        

        if (is_readable($file)){
            if ($header != ''){require $header;}
            require $file;
            if ($footer != ''){require $footer;}
        } else {
            throw new \Exception($file.'not found');
        }
        
    }

}
?>