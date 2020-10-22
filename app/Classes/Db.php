<?
class Db
{
    private static $instances = [];
    public static $connection = null;
    public $host = DB_HOST;
    public $dbname = DB_NAME;
    public $user = DB_USER;
    public $password = DB_PASSWORD;

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


    public function connect()
    {
        $this->connection = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->password);
    }

    public function query($sql)
    {
        return $this->connection->query($sql);
    }

}

?>