<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 7/8/15
 * Time: 12:17
 */
require_once 'mysql.php';

class member
{
    private $db = null;
    private $club_id = null;
    private $student_id = null;
    private $department = null;
    private $name = null;
    private $telephone = null;
    private $email = null;
    private $qq = null;
    private $portrait = null;
    private $grade = null;
    private $subject = null;
    private $class = null;

    function __construct($club_id, $student_id = null)
    {
        $this->db = new mysql();
        $this->club_id = $club_id;
        $this->student_id = $student_id;
        if ($student_id != null) {
            $member_info = $this->db->exec_select('member', [], [
                'student_id' => $student_id,
                'club_id' => $this->club_id
            ], 1)[0];
            $this->name = $member_info['name'];
            $this->telephone = $member_info['telephone'];
            $this->email = $member_info['email'];
            $this->qq = $member_info['qq'];
            $this->portrait = $member_info['portrait'];
            $this->grade = $member_info['grade'];
            $this->subject = $member_info['subject'];
            $this->class = $member_info['class'];
            $department_info = $this->db->exec_select('department', ['name'], [
                'id' => $member_info['department_id']
            ])[0];
            $this->department = $department_info['name'];
        }
    }

    function login_verify($student_id, $name, $telephone)
    {
        $this->__construct($this->club_id, $student_id);//reload construct function
        if ($name == $this->name && $telephone == $this->telephone) {
            return true;
        } else {
            return false;
        }
    }

    private function get_members(array $condition, $limit, $offset)
    {
        $member_info = $this->db->exec_select('member', [
            'student_id',
            'department_id',
            'name',
            'telephone',
            'email',
            'qq',
            'portrait',
            'grade',
            'subject',
            'class'
        ], $condition, $limit, $offset);
        for ($i = 0; $i < count($member_info); $i++) {
            $department_name = $this->db->exec_select('department', ['name'], [
                'id' => $member_info[$i]['department_id']
            ], 1)[0]['name'];
            $member_info[$i]['department'] = $department_name;
            unset($member_info[$i]['department_id']);
        }
        return json_encode($member_info, JSON_UNESCAPED_UNICODE);
    }

    function get_members_by_student_id($student_id, $limit, $offset)
    {
        return $this->get_members([
            'student_id' => $student_id
        ], $limit, $offset);
    }

    function get_members_by_department_id($grade, $department_id, $limit, $offset)
    {
        return $this->get_members([
            'department_id' => $department_id,
            'grade' => $grade
        ], $limit, $offset);
    }

    function get_members_by_name($name, $limit, $offset)
    {
        return $this->get_members(['name' => $name], $limit, $offset);
    }

    function get_members_by_telephone($telephone, $limit, $offset)
    {
        return $this->get_members(['telephone' => $telephone], $limit, $offset);
    }

    function get_members_by_email($email, $limit, $offset)
    {
        return $this->get_members(['email' => $email], $limit, $offset);
    }

    function get_members_by_qq($qq, $limit, $offset)
    {
        return $this->get_members(['qq' => $qq], $limit, $offset);
    }

    function get_members_by_grade($grade, $limit, $offset)
    {
        return $this->get_members(['grade' => $grade], $limit, $offset);
    }

    function delete_member()
    {
        if ($this->student_id != null) {
            return $this->db->exec_operation_safe(
                $this->db->gen_sql_delete('member_position', [
                    'student_id' => $this->student_id,
                    'club_id' => $this->club_id
                ]),
                $this->db->gen_sql_delete('member', [
                    'student_id' => $this->student_id,
                    'club_id' => $this->club_id
                ])
            );
        } else {
            return false;
        }
    }

    private function update_member(array $update)
    {
        if ($this->student_id != null) {
            return $this->db->exec_operation_safe(
                $this->db->gen_sql_update('member', $update, [
                    'student_id' => $this->student_id,
                    'club_id' => $this->club_id
                ])
            );
        } else {
            return false;
        }
    }

    function update_member_all($telephone, $email, $qq, $grade, $subject, $class)
    {
        return $this->update_member([
            'telephone' => $telephone,
            'email' => $email,
            'qq' => $qq,
            'grade' => $grade,
            'subject' => $subject,
            'class' => $class
        ]);
    }

    function get_current_profile()
    {
        if ($this->student_id != null) {
            $profile = array(
                'student_id' => $this->student_id,
                'name' => $this->name,
                'telephone' => $this->telephone,
                'email' => $this->email,
                'qq' => $this->qq,
                'portrait' => $this->portrait,
                'grade' => $this->grade,
                'subject' => $this->subject,
                'class' => $this->class,
                'department' => $this->department
            );
            return json_encode($profile, JSON_UNESCAPED_UNICODE);
        } else {
            return null;
        }
    }

    function application_assessment($application_id, $mark)
    {
        if ($this->student_id != null) {
            $ids = $this->db->exec_select('member_position', ['position_id'], [
                'student_id' => $this->student_id,
                'club_id' => $this->club_id
            ]);
            if (count($ids) > 0) {
                foreach ($ids as $id_info) {
                    $id = $id_info['position_id'];
                    if ($this->db->exec_select('position', ['isMarker'], [
                            'id' => $id,
                            'club_id' => $this->club_id
                        ], 1)[0]['isMarker'] == 1
                    ) {
                        if ($mark >= 0 && $mark <= 10) {
                            return $this->db->exec_operation_safe(
                                $this->db->gen_sql_insert('marks_application', [
                                    'student_id' => $this->student_id,
                                    'position_id' => $id,
                                    'application_id' => $application_id,
                                    'mark' => $mark
                                ])
                            );
                        } else {
                            return false;
                        }
                    }
                }
                return false;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}