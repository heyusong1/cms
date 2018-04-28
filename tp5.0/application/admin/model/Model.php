<?php
class Model
{  echo 1;
    /**
     * PDO对象
     *
     * [url=home.php?mod=space&uid=75151]@access[/url] private
     * @var Waf_Db
     */
    private $_pdo;
    /**
     * PDO statement 对象
     *
     * @access private
     * @var Waf_Db
     */
    private $_query;
    /**
     * 连接表示
     *
     * @access private
     * @var Waf_Db
     */
    private $_connected = false;
    /**
     * 参数
     *
     * @access private
     * @var array
     */
    private $_parameters = array();
    /**
     * 构造器
     *
     * @access public
     */
    public function __construct(array $options){
        parent::__construct($options);
        /** 连接数据库 */
        $this->connect();
    }
    /**
     * 连接数据库
     *
     * @access protected
     */
    protected function connect(){
        if(!class_exists('PDO')) $this->halt('不支持PDO扩展！');
        try{
            $this->_pdo = new PDO(
                'mysql:dbname='.$this->options["dbName"].';host='.$this->options["host"].';port='.$this->options['port'],
                $this->options["user"],
                $this->options["password"],
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->_connected = true;
        }catch (PDOException $e){
            $this->halt('不能链接Mysql数据库 .Error:'.$e->getMessage());
        }
    }
  
  
    /**
     * 初始化.
     *
     * @param stirng $sql
     * @param array|string $parameters
     * [url=home.php?mod=space&uid=987628]@Return[/url] void
     */
    private function _init($sql ,$parameters = ""){
        if(!$this->_connected) $this->connect();
        try {
            $this->_query = $this->_pdo->prepare($sql);
            $this->bindMore($parameters);
            if(!empty($this->_parameters)) {
                foreach($this->_parameters as $key=>$value){
                    switch( true ) {
                        case is_int($value):
                            $type = PDO::PARAM_INT;
                            break;
                        case is_bool($value):
                            $type = PDO::PARAM_BOOL;
                            break;
                        case is_null($value):
                            $type = PDO::PARAM_NULL;
                            break;
                        default:
                            $type = PDO::PARAM_STR;
                    }
                    $this->_query->bindParam($key ,$this->_parameters[$key] ,$type);
                }
            }
            $this->_parameters = array();
            return $this->_query->execute();
        }catch(PDOException $e){
            $this->halt($e->getMessage() ,$this->_query->queryString);
        }
    }
  
    /**
     * 绑定数据
     *
     * @param string $para
     * @param string $value
     */
    public function bind($para, $value){
        $this->_parameters[":" . $para] =  $value;
    }
    /**
     * 绑定数据
     *
     * @param array $parray
     */
    public function bindMore($parray){
        if(empty($this->_parameters) && is_array($parray)) {
            foreach($parray as $key => &$value) {
                $this->bind($key, $parray[$key]);
            }
        }
    }
    /**
     * 一些简单的操作
     *
     * @param string $sql
     * @param array $params
     * @param int $mode
     * [url=home.php?mod=space&uid=987628]@Return[/url] mixed
     */
    public function query($sql,$params = NULL, $mode = PDO::FETCH_ASSOC){
        $sql = trim($sql);
        if($params){
            $this->_init($sql ,$params);
            return $this->_query->fetchAll($mode);
        }else{
            $sql = str_replace('`waf_' ,'`'.$this->options['prefix'] ,$sql);
            return $this->_pdo->exec($sql);
        }
        return false;
    }
  
    /**
     * 根据查询取出所有资料
     *
     * @param  string $sql
     * @param  mixed $params
     * @return array
     */
    public function fetchAll($sql ,array $params = NULL){
        $this->_init($sql ,$params);
        $result = array();
        while($row = $this->_query->fetch(PDO::FETCH_ASSOC)){
            $result[] = $row;
        }
        return $result;
    }
    /**
     * 查询单条数据
     *
     * @param  string $sql
     * @param  mixed $params
     * @return array
     */
    public function fetchOne($sql ,array $params = NULL){
        $this->_init($sql ,$params);
        $result = $this->_query->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    /**
     * 获取总数量
     *
     * @param  string $sql
     * @param  array  $params
     * @return int
     */
    public function getCount($sql  ,$params = NULL){
        $this->_init($sql ,$params);
        $result = $this->_query->fetch(PDO::FETCH_NUM);
        return $result[0];
    }
    /**
     * 函数返回结果集中行的数目
     *
     * @param  string $sql
     * @return int
     */
    public function numRows($sql) {
        $this->_filter = array();
        $datas = $this->fetchAll($sql);
        $counts = count($datas);
        unset($datas);
        return $counts;
    }
    /**
     * 函数返回结果集中字段的数
     *
     * @param  string $sql
     * @return int
     */
    public function numFields($sql) {
        $this->_filter = array();
        $this->_init($sql);
        return $this->_query->columnCount() ;
    }
    /**
     * 获取最后插入的ID
     *
     * @return mixed
     */
    public function insertId() {
        try {
            return $this->_pdo->lastInsertId();
        }catch( PDOException $e ) {
            $this->halt($e->getMessage() ,$this->_query->queryString);
            return false;
        }
    }
    /**
     * 关闭连接.
     *
     * @access public
     */
    public function close(){
        $this->_pdo = null;
    }
    /**
     * 接.
     *
     * @access public
     */
    public function __destruct() {
        $this->close();
    }
  
    /**
     * 写入数据
     *
     * @param  string $table
     * @param  array  $data
     * @return int
     */
    public function insert($table ,$data){
        try {
            $sql = "INSERT INTO ".table($table);
            $columns = $values = '';
            foreach( $data as $k=>$v ) {
                $columns .= "$k,";
                $values .= ":$k,";
            }
            $columns = trim($columns,',');
            $values = trim($values,',');
            $sql = $sql . " ($columns) VALUES ($values)";
            $this->_init($sql ,$data);
            unset($sql ,$columns ,$values);
            return $this->insertId();
        }catch( PDOException $e ) {
            $this->halt($e->getMessage() ,$this->_query->queryString);
            return false;
        }
    }
    /**
     * 更新数据
     *
     * @param  string $table
     * @param  array  $data
     * @param  mixed  $wheres
     * @return int
     */
    public function update($table ,$data ,$wheres = NULL){
        $sql = "UPDATE ".table($table)." SET ";
        foreach($data as $key=>$value){
            if(is_array($value)){
                $sql .= "`{$key}` = ".$key . $value[0] . $value[1] . ',';
                unset($data[$key]);
            }else{
                $sql .= "`{$key}`=:{$key},";
            }
        }
        $sql = trim($sql ,',');
        if(is_array($wheres) && !empty($wheres)){
            $where = '';
            foreach($wheres as $key=>$value){
                $where .= "`{$key}`=:{$key} AND ";
            }
            $where = trim($where ,' AND ');
            $sql .= " WHERE {$where}";
            $data = array_merge($data ,$wheres);
            unset($where ,$wheres);
  
        }else{
            $sql .= " WHERE {$wheres}";
        }
        $this->_init($sql ,$data);
        unset($sql);
        return $this->_query->rowCount();
    }
    /**
     * 删除数据
     *
     * @param  string $table
     * @param  array|string  $wheres
     * @return int
     */
    public function delete($table ,$wheres = NULL){
        $sql = "DELETE FROM ".table($table).' WHERE ';
        if(is_array($wheres)){
            foreach($wheres as $key => $value){
                $sql .= "`{$key}`=:{$key} AND ";
            }
            $sql = trim($sql ,' AND ');
        }else{
            $sql .= $wheres;
        }
        $this->_init($sql ,$wheres);
        unset($sql);
        return $this->_query->rowCount();
    }
  
}