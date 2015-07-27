<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 7/7/15
 * Time: 14:15
 */
require_once 'mysql.php';

class university
{
    private $db = null;
    private $uid = null;
    private $name = null;
    private $portrait = null;

    function __construct($uid = null)
    {
        $this->db = new mysql();
        $this->uid = $uid;
        if ($uid != null) {
            $university_info = $this->db->exec_select('university', [], ['id' => $uid], 1);
            if (count($university_info) > 0) {
                $this->name = $university_info['name'];
                $this->portrait = $university_info['portrait'];
            }
        }
    }

    /*
     * add a university
     * @param string university name
     * @param Base64Str university portrait data
     * @return boolean the result of adding
     **/
    function add($name, $portrait_data)
    {
        return $this->db->exec_operation_safe($this->db->gen_sql_insert('university', ['name' => $name, 'portrait' => $portrait_data]));
    }

    /*
     * rename current university
     * @param string new university name
     * @return boolean the result of renaming
     **/
    function rename($new_name)
    {
        if ($this->uid != null) {
            return $this->db->exec_operation_safe($this->db->gen_sql_update('university', ['name' => $new_name], ['id' => $this->uid]));
        } else {
            return false;
        }
    }

    /*
     * update current university portrait
     * @param Base64Str new university portrait
     * @return boolean the result of updating
     **/
    function update_portrait($new_portrait)
    {
        if ($this->uid != null) {
            return $this->db->exec_operation_safe($this->db->gen_sql_update('university', ['portrait' => $new_portrait], ['id' => $this->uid]));
        } else {
            return false;
        }
    }

    function getId()
    {
        return $this->uid;
    }

    function getName()
    {
        return $this->name;
    }

    function getPortriat()
    {
        return $this->portrait;
    }

    function get_all_universities(){
        $unis=$this->db->exec_select('university',[],[]);
        return json_encode($unis,JSON_UNESCAPED_UNICODE);
    }

    function get_clubs(){
        if($this->uid!=null){
            $club_info=$this->db->exec_select('club',[
                'id',
                'name',
                'portrait'
            ],[
                'university_id'=>$this->uid
            ],1000);
            return json_encode($club_info,JSON_UNESCAPED_UNICODE);
        }else{
            return null;
        }
    }
}