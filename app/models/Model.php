<?php
namespace app\models;

use libs\Database;

class Model extends Database
{
    protected $where;
    protected $limit;
    protected $order;
    protected $select;
    protected $fillable = [];
    protected $hidden = [];
    protected $id;

    public function __construct()
    {
        $this->setTableName();
        parent::__construct();
    }

    public function setTableName()
    {
        if(empty($this->table)) {
            $class = explode('\\', trim(static::class, '\\'));
            $class = end($class);
            $class = str_replace("Model", '', $class);
            $class = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $class)).'s';
            $this->table = $class;
        }         
    }

    public function all()
    {
        $order = $this->order ?? 'ORDER BY id DESC';
        $results = $this->connect->query("SELECT * FROM $this->table $order")->fetchAll();
        return $this->hidden($results);
    }

    public function get($hidden = true)
    {
        $select = $this->select ?? '*';
        $where = $this->where ?? '';
        $order = $this->order ?? 'ORDER BY id DESC';
        $limit = $this->limit ?? '';
        $sql = "SELECT $select FROM $this->table $where $order $limit";
        $statements = $this->execute($sql);
        $results = $statements->fetchAll();

        return $hidden ? $this->hidden($results) : $results;
    }

    public function create($data = [])
    {
        if($data == null) {
            return false;
        }

        $into = "INSERT INTO $this->table (";
        $values = "VALUES (";

        foreach ($data as $field => $value) {
            if(in_array($field, $this->fillable)) {
                $into .= "`$field`, ";
                $values .= "'$data[$field]', ";
            }
        }

        $into = trim($into, ', ').")";
        $values = trim($values, ', ').")";

        $sql = "$into $values";
        
        try {
            $this->connect->beginTransaction();
            $statements = $this->execute($sql);
            $data['id'] = $this->connect->lastInsertId();
            $this->connect->commit();
        } catch (\Exception $e) {
            $error = new InternalServerException();
            Response::json(200, [
                'code' => $error->getCode(),
                'message' => $error->getMessage(),
            ]);
            exit;
        }
        return $data;
    }

    public function update($id, $data = [])
    {
        if(!$id) {
            return false;
        }

        $sql = "UPDATE $this->table SET ";
        foreach($data as $field => $value) {
            if(in_array($field, $this->fillable)) {
                $sql .= "`$field` = '$value', ";
            }
        }

        $sql = trim($sql, ', ')." WHERE id = $id";

        return $this->executeCRUD($sql);
    }

    public function delete($id, $field = '')
    {
        if(is_array($id)) {
            $idString = implode(',', $id);
            if(empty($field)) {
                $sql = "DELETE FROM $this->table WHERE id IN ($idString)";
            } else {
                $sql = "DELETE FROM $this->table WHERE $field IN ($idString)";
            }
        } else {
            if(empty($field)) {
                $sql = "DELETE FROM $this->table WHERE id = $id";
            } else {
                $sql = "DELETE FROM $this->table WHERE $field = $id";
            }
        }

        return $this->executeCRUD($sql);
    }

    public function getById($id, $hidden = true)
    {
        $sql = "SELECT * FROM $this->table WHERE id = $id";
        $statement = $this->execute($sql);
        if($statement) {
            $result = $statement->fetch();
            return $hidden ? $this->hidden($result) : $result;
        }
    }

    public function query($sql = null)
    {
        if(!empty($sql)) {
            $statements = $this->execute($sql);

            return $this->hidden($statements->fetchAll());
        }
    }

    public function select($collumns = ['*'])
    {
        if (is_array($collumns)) {
            $this->select = trim(implode(', ', $collumns), ', ');
        }
    }

    public function limit(array $limit = [0, 10])
    {
        $this->limit = "LIMIT ".implode(', ', $limit);
    }

    public function order($order = ['id', 'DESC'])
    {
        $this->order = "ORDER BY ".implode(' ', $order)." ";
    }

    public function first($hidden = true)
    {
        return $this->get($hidden) ? $this->get($hidden)[0] : '';
    }

    public function where($where = [])
    {
        if(in_array('BETWEEN', explode(' ', $this->where))) {
            $this->where = null;
        }
        
       if(is_array($where) && !empty($where)) {
           if(count($where) === 2) {
               $operator = ' = ';
           } elseif (count($where) === 3) {
               $operator = $where[1];
           }
           $field = array_shift($where);
           $value = array_pop($where);

           $value = is_int($value) ? $value : "'$value'";
           if($this->where) {
               $this->where .= " AND $field $operator $value";
           } else {
                $this->where .= "WHERE $field $operator $value";
           }
       } 
    }

    public function orWhere($where = [])
    {
        if(in_array('BETWEEN', explode(' ', $this->where))) {
            $this->where = null;
        }

        if(is_array($where) && !empty($where)) {
            if(count($where) === 2) {
                $operator = ' = ';
            } elseif (count($where) === 3) {
                $operator = $where[1];
            }
            $field = array_shift($where);
            $value = array_pop($where);
 
            $value = is_int($value) ? $value : "'$value'";
 
            if($this->where) {
                $this->where .= " OR $field $operator $value";
            } else {
                 $this->where .= "WHERE $field $operator $value";
            }
        } 
    }   
    
    public function whereBetween($collumn, $between = [])
    {
        if (!empty($between) && count($between) === 2) {
            $from = is_int($between[0]) ? $between[0] + 0 : "'$between[0]'";
            $to = is_int($between[1]) ? $between[1] + 0 : "'$between[1]'";
            $this->where = "WHERE $collumn BETWEEN $from AND $to";
        }
    }

    public function whereNotBetween($collumn, $between = [])
    {
        if (!empty($between) && count($between) === 2) {
            $from = is_int($between[0]) ? $between[0] + 0 : "'$between[0]'";
            $to = is_int($between[1]) ? $between[1] + 0 : "'$between[1]'";
            $this->where = "WHERE $collumn NOT BETWEEN $from AND $to";
        }
    }

    public function whereIn($collumn, $in = [])
    {
        if (!empty($in) && count($in) >= 2) {
            $string = implode(',', $in);
            $this->where = "WHERE $collumn IN($string)";
        }
    }

    public function execute($sql)
    {
        $statements = $this->connect->prepare($sql);
        $check = $statements->execute();
        try {
            return $statements;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function executeCRUD($sql)
    {
        try {
            $this->connect->beginTransaction();
            $statements = $this->execute($sql);
            $this->connect->commit();
            return $statements;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function hidden($results)
    {
        if(!is_null($results)) {
            if(is_array($results)) {
                $new_results = [];
                foreach($results as $key => $result) {
                    if(is_array($result)) {
                        foreach($result as $k => $v) {
                            if(in_array($k, $this->hidden)) {
                                unset($result[$k]);
                            } 
                        }
                        array_push($new_results, $result);
                    } else {
                        if(!in_array($key, $this->hidden)) {
                            $new_results[$key] = $result;
                        } 
                    }
                }
            } 
        }

        return $new_results;
    }

    public function total()
    {
        $where = $this->where ?? '';
        $sql = "SELECT count(*) FROM $this->table $where"; 
        $statements = $this->execute($sql);
        $result = $statements->fetchColumn();
        return $result;
    }
}
?>
