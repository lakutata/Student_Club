<?php

/**
 * Created by PhpStorm.
 * User: M
 * Date: 7/6/15
 * Time: 11:17
 */
class mysql
{
    /*Define mysql connection info*/
    /*--BEGIN--*/

    private $host = "localhost";
    private $username = "lakutata_club";
    private $password = "lakutata10086";
    private $dbname = "lakutata_club";
    private $port = "3306";

    /*--END--*/

    private $conn = null;//mysql connection resource

    function __construct()
    {
        $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->dbname, $this->port) or exit('Database Error');
        mysqli_set_charset($this->conn, 'utf8');//ensure all data use utf8 charset
    }

    function __destruct()
    {
        mysqli_close($this->conn);//dispose connection resource
    }

    function exec_operation_safe()//give variable sql sentence
    {
        if (count(func_get_args()) > 0) {
            mysqli_query($this->conn, "START TRANSACTION");// prepare for rollback
            $isError = false;
            foreach (func_get_args() as $query) {
                if (!mysqli_query($this->conn, $query)) {
                    $isError = true;
                    /* below code just for debug
                     * echo mysqli_error($this->conn);
                     * */
                }
            }
            if (!$isError) {
                mysqli_query($this->conn, "COMMIT");
                return true;
            } else {
                mysqli_query($this->conn, "ROLLBACK");//if error occurred, rollback
                return false;
            }
        } else {
            return null;// if no agreements, return  null
        }
    }

    function exec_select($table, array $fields, array $conditions, $limit = 500, $offset = 0)
    {
        $fields_str = "";
        if (count($fields) > 0) {
            $count = count($fields);
            for ($i = 0; $i < $count; $i++) {
                $fields_str .= "`" . $fields[$i] . "`";
                if ($i != $count - 1) {
                    $fields_str .= ",";
                }
            }
        } else {
            $fields_str = "*";
        }

        $conditions_str = " WHERE ";
        if (count($conditions) > 0) {
            $condition_count = count($conditions);
            $l = 0;
            foreach ($conditions as $key => $val) {
                $conditions_str .= "`" . $key . "`='" . $val . "'";
                if ($l != $condition_count - 1) {
                    $conditions_str .= " AND ";
                    $l += 1;
                }
            }
        } else {
            $conditions_str = "";
        }

        $sql = "SELECT " . $fields_str . " FROM " . "`" . $table . "`" . $conditions_str . " LIMIT " . $limit . " OFFSET " . $offset;
        if (!$result = mysqli_query($this->conn, $sql)) {
            return [];
        } else {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

    }

    function exec_select_order_DESC($table, array $fields, array $conditions, $order_field, $limit = 500, $offset = 0)
    {
        $fields_str = "";
        if (count($fields) > 0) {
            $count = count($fields);
            for ($i = 0; $i < $count; $i++) {
                $fields_str .= "`" . $fields[$i] . "`";
                if ($i != $count - 1) {
                    $fields_str .= ",";
                }
            }
        } else {
            $fields_str = "*";
        }

        $conditions_str = " WHERE ";
        if (count($conditions) > 0) {
            $condition_count = count($conditions);
            $l = 0;
            foreach ($conditions as $key => $val) {
                $conditions_str .= "`" . $key . "`='" . $val . "'";
                if ($l != $condition_count - 1) {
                    $conditions_str .= " AND ";
                    $l += 1;
                }
            }
        } else {
            $conditions_str = "";
        }

        $sql = "SELECT " . $fields_str . " FROM " . "`" . $table . "`" . $conditions_str . " ORDER BY `" . $order_field . "` DESC" . " LIMIT " . $limit . " OFFSET " . $offset;
        if (!$result = mysqli_query($this->conn, $sql)) {
            return [];
        } else {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
    }

    function exec_select_order_ASC($table, array $fields, array $conditions, $order_field, $limit = 500, $offset = 0)
    {
        $fields_str = "";
        if (count($fields) > 0) {
            $count = count($fields);
            for ($i = 0; $i < $count; $i++) {
                $fields_str .= "`" . $fields[$i] . "`";
                if ($i != $count - 1) {
                    $fields_str .= ",";
                }
            }
        } else {
            $fields_str = "*";
        }

        $conditions_str = " WHERE ";
        if (count($conditions) > 0) {
            $condition_count = count($conditions);
            $l = 0;
            foreach ($conditions as $key => $val) {
                $conditions_str .= "`" . $key . "`='" . $val . "'";
                if ($l != $condition_count - 1) {
                    $conditions_str .= " AND ";
                    $l += 1;
                }
            }
        } else {
            $conditions_str = "";
        }

        $sql = "SELECT " . $fields_str . " FROM " . "`" . $table . "`" . $conditions_str . " ORDER BY `" . $order_field . "` ASC" . " LIMIT " . $limit . " OFFSET " . $offset;
        if (!$result = mysqli_query($this->conn, $sql)) {
            return [];
        } else {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
    }

    function gen_sql_insert($table, array $data)//generate insert sql
    {
        $keys_str = "";
        $val_str = "";
        $l = 0;
        foreach ($data as $key => $val) {
            $keys_str .= "`" . $key . "`";
            $val_str .= "'" . $this->Check_Val($val) . "'";
            if ($l != count($data) - 1) {
                $keys_str .= ",";
                $val_str .= ",";
                $l += 1;
            }
        }
        return $sql = "INSERT INTO " . $table . "(" . $keys_str . ") VALUES(" . $val_str . ")";
    }

    function gen_sql_update($table, array $change, array $condition)//generate update sql
    {
        $change_str = "";
        $condition_str = " WHERE ";
        $l1 = 0;
        $l2 = 0;
        foreach ($change as $change_key => $change_val) {
            $change_str .= "`" . $change_key . "`='" . $this->Check_Val($change_val) . "'";
            if ($l1 != count($change) - 1) {
                $change_str .= ",";
                $l1 += 1;
            }
        }
        if (count($condition) > 0) {
            $count = count($condition);
            foreach ($condition as $condition_key => $condition_val) {
                $condition_str .= "`" . $condition_key . "`='" . $this->Check_Val($condition_val) . "'";
                if ($l2 != $count - 1) {
                    $condition_str .= " AND ";
                    $l2 += 1;
                }
            }
        } else {
            $condition_str = "";
        }
        return $sql = "UPDATE " . $table . " SET " . $change_str . $condition_str;
    }

    function gen_sql_delete($table, array $condition)//generate delete sql
    {
        $condition_str = " WHERE ";
        $l = 0;
        if (count($condition) > 0) {
            $count = count($condition);
            foreach ($condition as $key => $val) {
                $condition_str .= "`" . $key . "`='" . $this->Check_Val($val) . "'";
                if ($l != $count - 1) {
                    $condition_str .= " AND ";
                    $l += 1;
                }
            }
        } else {
            $condition_str = "";
        }
        return $sql = "DELETE FROM " . $table . $condition_str;
    }

    private function Check_Val($value)
    {
        return mysqli_real_escape_string($this->conn, $value);
        //return addslashes($value);
    }
}