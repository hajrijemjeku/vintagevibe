<?php
include 'db.php';

class Crud {
    private $pdo;
    function __construct($pdo) {
        $this -> pdo = $pdo;
    }


    public function select($table, $columns = [], $where =[], $limit, $sort) {
        $columns = $this->columns($columns);
        $whereClause = $this->where($where);
        $sort = ($sort == 'DESC') ? ' ORDER BY id DESC ' : ' ';
        $limitClause = ($limit > 0) ?  "LIMIT $limit" : "" ;

         $sql = "SELECT $columns FROM $table $whereClause $sort $limitClause";         
         
         return $this->pdo->query($sql);

         //return $this->pdo->query($sql);
    }

    public function search($table, $columns = [], $where =[], $limit) {
        $columns = $this->columns($columns);
        $where_like = $this->where_like($where);
        $limit = ($limit > 0) ? " LIMIT  $limit" : "  " ;

         $sql = "SELECT $columns FROM $table $where_like  $limit";

         return $this->pdo->query($sql);
    }

    private function columns($columns = []){

        // if(count($columns) == 0)  {return "*";}
        // else{ return implode(",", $columns);}
        if (empty($columns)) {
            return "*";
        } else {
            return implode(",", $columns);
        }
    }
    private function where($where = []){

        $where_statement = "";
        // $counter = 0;
        if(count($where) > 0) {
            $where_statement .= " WHERE ";
            $counter = 0;

            foreach($where as $column => $value){

                if($counter < count($where) - 1){
    
                    $where_statement .= " $column = '$value' AND";
                }
                else{
                    $where_statement .= " $column = '$value' ";
                }
                $counter++;
    
            }
            

        }
        return $where_statement;

    }

    private function where_like($where = []){

        $where_statement = '';
        // $counter = 0;
        if(count($where) > 0) {
            $where_statement .= "WHERE";
            $counter = 0;

            foreach($where as $column => $value){

                if($counter < count($where) - 1){
    
                    $where_statement .= " $column LIKE '%$value%' OR";
                }
                else{
                    $where_statement .= " $column LIKE '%$value%' ";
                }
                $counter++;
            }
        }
        return $where_statement;
    }

}