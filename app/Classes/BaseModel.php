<?
abstract class BaseModel
{   
    public static $table_name = '';    
    var $columns = null;    
    var $db = null;
    var $sql = null;
    var $arData = [];
    var $rowCount = null;

    // $isNeedSave use for save model (this flag says if we need get table columns)
    public function __construct($isNeedSave = false){
        $this->db = Db::getInstance();
        $this->db->connect();

        // create model properties
        if($isNeedSave){
            
            $this->columns = $this->getColumns();

            foreach ($this->columns as $key => $column) {
                $this->$column = null;
            }
        }

    }

    public function getColumns(){
        
        $this->sql = 'SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`="'.DB_NAME.'" AND `TABLE_NAME` = "'.static::$table_name.'"';

        $res = $this->db->connection->query($this->sql);

        while ($row = $res->fetch(PDO::FETCH_ASSOC)){
            $arData[] = $row['COLUMN_NAME'];
        }

       return $arData;

    }

    public function save(){

        foreach ($this->columns as $key => $column){
            if($column != 'id') {
                $columns .= '`'.$column.'`,';
                $values .= ':'.$column.',';
            }
        }

        $columns = substr($columns,0 ,-1);
        $values = substr($values,0 ,-1);

        $this->sql = 'INSERT INTO '.static::$table_name.' ('.$columns.') VALUES ('.$values.')';
        
        try{
            
            $statement = $this->db->connection->prepare($this->sql);
            foreach ($this->columns as $key => $column){
                if($column != 'id') {
                    $statement->bindParam(':'.$column, $this->$column);
                }
            }
            
            $statement->execute();

            if ($statement->errorInfo() != '') {
                $this->er[] = $statement->errorInfo();
            }

        }catch(PDOException $e){

           $this->er[] = $e->getMessage();
           
        }

        return $this->db->connection->lastInsertId();

    }

    //update entity
    public function update($fields){

        $set = '';

        if(count($fields) > 0 && intval($this->id) > 0){
            
            foreach ($fields as $key => $value) {
                $set .= $key.'=:'.$key.','; 
            }

            $set = substr($set,0 ,-1);
            $this->sql = 'UPDATE '.static::$table_name.' SET '.$set.' WHERE id = '.$this->id;
            
            try{
                
                $statement = $this->db->connection->prepare($this->sql);   
                $statement->execute($fields);
    
                if ($statement->errorInfo() != '') {
                    $this->er[] = $statement->errorInfo();
                }
    
            }catch(PDOException $e){
               $this->er[] = $e->getMessage();
            }
        }else{
            $this->er[] = 'id or fields are empty';
            return 0;
        }
        
        return $statement->rowCount();

    }
    
    // get one entity
    public static function get($id){
        
        $model = new Static();
        $model->sql = 'SELECT * FROM '.static::$table_name.' WHERE id='.$id.' LIMIT 1';
        $res = $model->db->connection->query($model->sql);

        while ($row = $res->fetch(PDO::FETCH_ASSOC)){
             $model->arData[] = $row;
        }

        foreach ($model->arData[0] as $key => $value) {
            $model->$key = $value;
        }

        return $model;

    }

    // build query for select
    public function select($fields, $filter, $order, $limit = '', $offset = '', $needCount = false){
        
        if($fields == '' || count($fields) == 0){
            $fields = '*';
        }else{
            $fields = implode(',', $fields);
        }

        if($filter != ''){
            $filter = ' WHERE '.$filter;
        }

        if($order != ''){
            $order = ' ORDER BY '.$order;
        }

        if(intval($limit) > 0){
            $limit = ' LIMIT '.$limit;
        }

        if($needCount){
            $res = $this->db->connection->prepare('SELECT COUNT(*) FROM '.static::$table_name);
            $res->execute();
            $this->rowCount = $res->fetchColumn();
        }

        if(intval($offset) > 0){
            $offset = ' OFFSET '.$offset;
        }else{
            $offset = '';
        }

        $arData = null;
        $this->sql = 'SELECT '.$fields.' FROM '.static::$table_name.$filter.$order.$limit.$offset;

        $res = $this->db->connection->query($this->sql);

        if(is_object($res)){
            while ($row = $res->fetch(PDO::FETCH_ASSOC)){
                $this->arData[] = $row;
            }
        }

        return $this;

    }

    // get array result of select
    public function getArray(){

        return $this->arData;

    }

    // get array result of select with key (column)
    public function getArrayKey($key){

        foreach ($this->arData as $value) {
            $arData[$value[$key]] = $value;
        }

        return $arData;

    }

    // get array result of select
    public function getFirst(){

        return $this->arData[0];

    }

    // get json result of select
    public function getJson(){

        return json_encode($this->arData);

    }

    // get one column
    public function getColumn($column){

        return array_column($this->arData, $column);

    }

    // get one column
    public function getColumnGrouped($column){

        foreach ($this->arData as $data) {
            foreach ($data as $value) {
                if(!in_array($value[$column], $this->arData)) $arData[] = $value[$column]; 
            }
        }  
        return $arData;

    }

    // group results in column
    public function group($column, $isSingle = false){

        $arData = [];

        foreach ($this->arData as $key => $value) {
           $arData[$value[$column]][] = $value;
        }

        $this->arData = $arData;

        return $this;

    }

    //delete entity
    public static function delete($id){

        $res = null;
        $model = new Static();
        $id = intval($id);

        if($id > 0){

            try{
                
                $statement = $model->db->connection->prepare('DELETE FROM '.static::$table_name.' WHERE id='.$id);   
                $statement->execute();
    
                if ($statement->errorInfo() != '') {
                    $model->er[] = $statement->errorInfo();
                }
    
            }catch(PDOException $e){
               $model->er[] = $e->getMessage();
            }

        }
        
        return $model;
    }

}
?>