<?

class ToDB {

	private $connection;

	public function setup($server, $username, $password, $schema, $charset = null){
		$this->connection = new ToDBConnection(
			$server, $username, $password, $schema, $charset
		);
	}

	public function select(){
		return new ToDBSelect($this->connection);
	}

	public function update(){
		return new ToDBUpdate($this->connection);
	}

	public function insert(){
		return new ToDBInsert($this->connection);
	}

	public function delete(){
		return new ToDBDelete($this->connection);
	}

}

class ToDBConnection {

	private $connection = null;

	public function __construct($server, $username, $password, $schema, $charset = 'utf8'){
		if(!$this->connection = @mysql_connect($server, $username, $password)){
            throw new Exception("Can't connect to server");
        } else {
            mysql_set_charset($charset, $this->connection);
            if(!mysql_select_db($schema)){
                throw new Exception("Can't select the database");
            }
        }
	}

    public function query($sql){
    	if($this->connection === null){
            throw new Exception("There is no connection. Use ::Config() method to setup ToDB");
    	}
        $sql = preg_replace('/\s\s+/', ' ', $sql);
        if(!$query = @mysql_query($sql, $this->connection)){
            throw new exception(mysql_error());
        }
        return $query;           
    }

}

class ToDBSelect extends ToDB {

	public $tables, $fields, $wheres, $orders, $joins;
	private $builder, $connection;

	public function __construct($connection){
		$this->connection = $connection;
		$this->builder = new ToDBSelectBuilder($this); 
	}

	public function table($name, $alias=null){
		$this->tables[] = new ToDBTable($name, $alias);
		return $this;
	}

	public function field($name, $alias=null){
		$this->fields[] = new ToDBColumn($name, $alias);
		return $this;
	}

	public function where($column, $operator, $value=null, $connector='and'){
		$this->wheres[] = new ToDBWhere($column, $operator, $value, $connector);
		return $this;
	}

	public function order($column, $direction = 'ASC'){
		$this->orders[] = new ToDBOrder($column, $direction);
		return $this;
	}

	public function orWhere($column, $operator, $value=null){
		$this->where($column, $operator, $value, 'or');
		return $this;
	}

	public function innerjoin($table, $clauses){
		$this->joins[] = new ToDBJoin('inner', $table, $clauses);
		return $this;
	}

	public function leftjoin($table, $clauses){
		$this->joins[] = new ToDBJoin('left', $table, $clauses);
		return $this;
	}

	public function rightjoin($table, $clauses){
		$this->joins[] = new ToDBJoin('right', $table, $clauses);
		return $this;
	}

	public function get(){
		$sql = $this->builder->make();
		$query = $this->connection->query($sql);
        if(mysql_num_rows($query) > 0){
            return mysql_fetch_object($query);
        }
        return null;
	}

	public function getAll(){
		$sql = $this->builder->make();
		$ret = array();
        $query = $this->connection->query($sql);
        if(mysql_num_rows($query) > 0){
            while($rs = mysql_fetch_object($query)){
                $ret[] = $rs;
            }
            return $ret;
        } 
        return null;
	}

	public function dump(){
		return $this->builder->make();
	}

}

class ToDBUpdate extends ToDB {

	public $tables, $fields, $wheres;
	private $builder, $connection;

	public function __construct($connection){
		$this->connection = $connection;
		$this->builder = new ToDBUpdateBuilder($this); 
	}

	public function table($name, $alias=null){
		$this->tables[] = new ToDBTable($name, $alias);
		return $this;
	}

	public function field($name, $value){
		$this->fields[] = new ToDBField($name, $value);
		return $this;
	}

	public function where($column, $operator, $value=null, $connector='and'){
		$this->wheres[] = new ToDBWhere($column, $operator, $value, $connector);
		return $this;
	}

	public function orWhere($column, $operator, $value=null){
		$this->where($column, $operator, $value, 'or');
		return $this;
	}

	public function run(){
		$sql = $this->builder->make();
		$query = $this->connection->query($sql);
		return mysql_affected_rows();
	}

	public function dump(){
		return $this->builder->make();
	}

}

class ToDBInsert extends ToDB {

	public $tables, $fields;
	private $builder, $connection;

	public function __construct($connection){
		$this->connection = $connection;
		$this->builder = new ToDBInsertBuilder($this); 
	}

	public function table($name, $alias=null){
		$this->tables[] = new ToDBTable($name, $alias);
		return $this;
	}

	public function field($name, $value){
		$this->fields[] = new ToDBField($name, $value);
		return $this;
	}

	public function run(){
		$sql = $this->builder->make();
		$query = $this->connection->query($sql);
		return mysql_insert_id();
	}

	public function dump(){
		return $this->builder->make();
	}

}

class ToDBDelete extends ToDB {

	public $tables, $wheres;
	private $builder, $connection;

	public function __construct($connection){
		$this->connection = $connection;
		$this->builder = new ToDBDeleteBuilder($this); 
	}

	public function table($name, $alias=null){
		$this->tables[] = new ToDBTable($name, $alias);
		return $this;
	}

	public function where($column, $operator, $value=null, $connector='and'){
		$this->wheres[] = new ToDBWhere($column, $operator, $value, $connector);
		return $this;
	}

	public function orWhere($column, $operator, $value=null){
		$this->where($column, $operator, $value, 'or');
		return $this;
	}

	public function run(){
		$sql = $this->builder->make();
		$query = $this->connection->query($sql);
		return mysql_affected_rows();
	}

	public function dump(){
		return $this->builder->make();
	}

}

class ToDBTable {

	public $name, $alias;

	public function __construct($name, $alias=null){
		$this->name = $name;
		$this->alias = $alias;
	}

}

class ToDBColumn {

	public $name, $alias;

	public function __construct($name, $alias){
		$this->name = $name;
		$this->alias = $alias;
	}

}

class ToDBField {

	public $name, $value;

	public function __construct($name, $value){
		$this->name = $name;

		if($value === false){
			$this->value = 'false';
		} elseif($value === true){
			$this->value = 'true';
		} elseif($value === null){
			$this->value = 'null';
		} else {
			$this->value = $value;
		}
	}

}

class ToDBWhere {

	public $column, $operator, $value, $connector; 

	public function __construct($column, $operator, $value, $connector){
		if($value == null){
			$value = $operator; $operator = '=';
		}
		$this->column = $column;
		$this->operator = $operator;
		$this->value = $value;
		$this->connector = $connector;
	}

}

class ToDBOrder {

	public $column, $direction; 

	public function __construct($column, $direction){
		$this->column = $column;
		$this->direction = $direction;
	}

}

class ToDBJoin {

	public $table, $type, $clauses;

	public function __construct($type, $table, $clauses){
		$this->table = $table;
		$this->type = $type;

		if(is_array($clauses)){
			foreach ($clauses as $clause) {
				$this->addClause($clause[0], $clause[1], $clause[2], $clause[3]);
			}
		} else {
			$this->addClause(1, 1);
		}
	}

	public function addClause($column1, $operator, $column2=null, $connector='and'){

		if($column2 == null){
			$column2 = $operator; $operator = '=';
		}

		if($connector == null) $connector = 'and';

		$this->clauses[] = (object)array(
			'column1' => $column1,
			'operator' => $operator,
			'column2' => $column2,
			'connector' => $connector,
		);
 	}

}

/*
============================================================================================================
										SQL BUILDERS 
============================================================================================================

The section below has the classes to handle the SQL creation from 2Database Objects
*/

class ToDBBuilder {

	public function removeInjection($sql){
        return addslashes($sql);
    }

	public function quot($text){
        if(strpos($text, '.*')){
            return "`".str_replace(".", "`.", $text);
        } else {
            return "`".str_replace(".", "`.`", $text)."`";
        }
    }

    public $operators = [
		'=', '<', '>', '<=', '>=', '<>', '!=',
		'like', 'not like', 'between', 'ilike',
		'&', '|', '^', '<<', '>>',
		'rlike', 'regexp', 'not regexp',
		'~', '~*', '!~', '!~*',
	];

	public $words = [
		'false', 'true', 'null'
	];

}

/* ======== INSERT BUILDER ======== */

class ToDBSelectBuilder extends ToDBBuilder {

	public $sql, $sqlTables = array(), $sqlFields = array(), $sqlWheres = array(), $sqlOrders, $sqlJoins = array();
	private $queryObject = null;

	public function __construct($queryObject){
		$this->queryObject = $queryObject;
	}

	public function make(){
		$qo = $this->queryObject;
		
		if(is_array($qo->tables)){
			foreach ($qo->tables as $table) {
				if($table->alias !== null){
					$this->sqlTables[] = 	" ".$this->quot($table->name).
											" as ".$this->quot($table->alias)." ";
				} else {
					$this->sqlTables[] = 	" ".$this->quot($table->name)." ";
				}
			}
		}

		if(is_array($qo->fields)){
			foreach ($qo->fields as $field) {
				if($field->alias !== null){
					$this->sqlFields[] = 	" ".$this->quot($field->name).
											" as ".$this->quot($field->alias)." ";
				} else {
					$this->sqlFields[] = 	" ".$this->quot($field->name)." ";
				}
			}
		} else {
			$this->sqlFields[] = '*';
		}

		if(is_array($qo->joins)){
			foreach ($qo->joins as $join) {
				$sqlJoinClauses = array();

				foreach ($join->clauses as $clause) {
					$sqlJoinClauses[] = " ".strtoupper($clause->connector).
										" ".$this->quot($clause->column1).
										" ".$clause->operator.
										" ".$this->quot($clause->column2)." ";
				}

				$this->sqlJoins[] = 	" ".strtoupper($join->type)." JOIN ".
										" ".$this->quot($join->table)." ON 1=1 ".
										" ".implode('', $sqlJoinClauses)." ";
			}
		}

		if(is_array($qo->wheres)){
			foreach ($qo->wheres as $where) {
				$this->sqlWheres[] = 	" ".strtoupper($where->connector).
										" ".$this->quot($where->column).
										" ".$where->operator.
										" '".$where->value."' ";
			}
		}

		if(is_array($qo->orders)){
			foreach ($qo->orders as $order) {
				$this->sqlOrders[] = 	" ".$this->quot($order->column).
										" ".$order->direction." ";
			}
		}

		$this->sql = 	"SELECT ".(implode(', ', $this->sqlFields))." ".
						" FROM ".(implode(' , ', $this->sqlTables)).
						" ".(implode('', $this->sqlJoins)).
						" WHERE 1=1 ".(implode('', $this->sqlWheres))." ";

		if(!empty($this->sqlOrders)){
			$this->sql .= " ORDER BY ".(implode(' , ', $this->sqlOrders));
		}


		return $this->sql;
	}

}

/* ======== UPDATE BUILDER ======== */

class ToDBUpdateBuilder extends ToDBBuilder {

	public $sql, $sqlTables = array(), $sqlFields = array(), $sqlWheres = array();
	private $queryObject = null;

	public function __construct($queryObject){
		$this->queryObject = $queryObject;
	}

	public function make(){
		$qo = $this->queryObject;

		$this->sqlTables = " ".$this->quot($qo->tables[0]->name)." ";

		if(is_array($qo->fields)){
			foreach ($qo->fields as $field) {
				$this->sqlFields[] = 	" ".$this->quot($field->name).
										" = ".(in_array($field->value, $this->words) ? $field->value : " '".$this->removeInjection($field->value)."' ")." ";
			}
		}

		if(is_array($qo->wheres)){
			foreach ($qo->wheres as $where) {
				$this->sqlWheres[] = 	" ".strtoupper($where->connector).
										" ".$this->quot($where->column).
										" ".$where->operator.
										" '".$where->value."' ";
			}
		}

		$this->sql =	"UPDATE ".$this->sqlTables." SET ".
						" ".implode(' , ', $this->sqlFields)." ".
						"WHERE 1=1 ".implode('', $this->sqlWheres)." ";

		return $this->sql;
	}

}

/* ======== INSERT BUILDER ======== */

class ToDBInsertBuilder extends ToDBBuilder {

	public $sql, $sqlTables, $sqlFields = array(), $sqlValues = array();
	private $queryObject = null;

	public function __construct($queryObject){
		$this->queryObject = $queryObject;
	}

	public function make(){
		$qo = $this->queryObject;

		$this->sqlTables = " ".$this->quot($qo->tables[0]->name)." ";

		if(is_array($qo->fields)){
			foreach ($qo->fields as $field) {
				$this->sqlFields[] = 	" ".$this->quot($field->name)." ";
				$this->sqlValues[] =	" ".(in_array($field->value, $this->words) ? $field->value : " '".$this->removeInjection($field->value)."' ")." ";
			}
		}

		$this->sql =	"INSERT INTO  ".$this->sqlTables." ".
						" (".implode(', ', $this->sqlFields).")  ".
						" VALUES (".implode(', ', $this->sqlValues).") ";

		return $this->sql;
	}

}


/* ======== DELETE BUILDER ======== */

class ToDBDeleteBuilder extends ToDBBuilder {

	public $sql, $sqlTables, $sqlWheres = array();
	private $queryObject = null;

	public function __construct($queryObject){
		$this->queryObject = $queryObject;
	}

	public function make(){
		$qo = $this->queryObject;

		$this->sqlTables = " ".$this->quot($qo->tables[0]->name)." ";

		if(is_array($qo->wheres)){
			foreach ($qo->wheres as $where) {
				$this->sqlWheres[] = 	" ".strtoupper($where->connector).
										" ".$this->quot($where->column).
										" ".$where->operator.
										" '".$where->value."' ";
			}
		}

		$this->sql =	"DELETE FROM ".$this->sqlTables." ".
						"WHERE 1=1 ".implode('', $this->sqlWheres)." ";

		return $this->sql;
	}

}

?>