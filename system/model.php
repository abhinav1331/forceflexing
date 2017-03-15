<?php
class Model
{
	
  private $host = 'localhost';
  private $dbName = 'imarkcli_forceflexing';
  private $user = 'imarkcli_forcefl';
  private $pass = 'e8m]~$wfz@3Z';
     
  private $dbh;
  private $error;
  private $qError;
  
  private $stmt;
  
  public function __construct()
  {
      //dsn for mysql
    $dsn = "mysql:host=".$this->host.";dbname=".$this->dbName;
    $options = array(
        PDO::ATTR_PERSISTENT    => true,
        PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
        );
    
    try
	{
       $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
    }
    //catch any errors
    catch (PDOException $e)
	{
        $this->error = $e->getMessage();
    }
    
  }
  
  public function query($query)
  {
      $this->stmt = $this->dbh->prepare($query);
  }
  
  public function bind($param, $value, $type = null)
  {
    if(is_null($type))
	{
		switch (true)
		{
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
    }
    $this->stmt->bindValue($param, $value, $type);
  }
  
  public function execute()
  {
	return $this->stmt->execute();
	$this->qError = $this->dbh->errorInfo();
	if(!is_null($this->qError[2]))
	{
		echo $this->qError[2];
	}
	echo 'done with query';
  }
  
  public function resultset()
  {
    $this->execute();
    return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  
  public function single()
  {
    $this->execute();
    return $this->stmt->fetch(PDO::FETCH_ASSOC);
  }
  
  public function rowCount()
  {
    return $this->stmt->rowCount();
  }
  
  public function lastInsertId()
  {
    return $this->dbh->lastInsertId();
  }
  
  public function beginTransaction()
  {
    return $this->dbh->beginTransaction();
  }
  
  public function endTransaction()
  {
    return $this->dbh->commit();
  }
  
  public function cancelTransaction()
  {
    return $this->dbh->rollBack();
  }
  
  public function debugDumpParams()
  {
    return $this->stmt->debugDumpParams();
  }
  
  public function queryError()
  {
	$this->qError = $this->dbh->errorInfo();
	if(!is_null($qError[2]))
	{
		echo $qError[2];
	}
  }
  
  public function Insert($data,$table)
	{
		/* Insert function To insert data to desired table */
		$keys ='';	$values ='';
		$total = count($data);
		$x = 1;
		foreach($data as $key => $val)
		{
			if($x==$total)
				{	
					$keys .=$key; $values .=':'.$key;		
				}
			else
				{	
					$keys .=$key.','; $values .=':'.$key.',';		
				}
			$x++;
		}	

		$this->beginTransaction();	
		$this->query("INSERT INTO $table ($keys) VALUES($values)");
		foreach($data as $ke => $vals)
		{
			$this->bind(':'.$ke.'',$vals);
		}
		$this->execute();		
		return $last_id = $this->lastInsertId();	// Get last Inserted Record Id
	
	}
	
	//fetch row from desired table
	public function get_single_row($wherekey, $whereval,$table)
	{
		$query=$this->query("SELECT * FROM $table  WHERE  $wherekey = :id");
	    $this->bind(':id',$whereval);
		return $row= $this->single();
	}
	
	//get all data from table
	public function get_all($table)
	{
		$this->query("SELECT * FROM $table");
		return $result = $this->resultset();
	}
	
	public function get_record_count($table,$field,$value)
	{
		$this->query("SELECT * FROM $table WHERE `$field` = '".$value."'");
		$result = $this->resultset();
		return $testing = $this->rowCount();
	}
	
	//update data for a table
	public function update($data,$wherekey,$whereval,$table)
	{
		
		/* update function */
		$updata ='';	
		$total = count($data);
		$x = 1;
		foreach($data as $key => $val)
		{
			if($x==$total)
				{	
					$updata .=$key.'="'. $val.'"'; 	
				}
			else
				{	
					$updata .=$key.'="'. $val.'",'; 
				}
			$x++;
		}
		$query='UPDATE '.$table.' SET ' .$updata.' WHERE '.$wherekey.' = "'.$whereval.'"';
		$this->beginTransaction();	
		$this->query($query);
		$this->execute();		
	}
	
	//fetch a particular column
	public function get_single_row_columns($column,$wherekey, $whereval,$table)
	{
		if(is_array($column))
			$columns=implode(",",$column);
		else
			$columns=$column;
		$query=$this->query("SELECT $columns FROM $table  WHERE  $wherekey = :id");
	    $this->bind(':id',$whereval);
		return $row= $this->single();
	}
	
	
}//end class db

?>