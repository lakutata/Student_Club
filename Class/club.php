<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 7/7/15
 * Time: 15:26
 */
require_once 'mysql.php';

class club
{
    private $db = null;
    private $id = null;
    private $name = null;
    private $uid = null;
    private $uname = null;
    private $intro = null;
    private $portrait = null;
    private $password = null;

    function __construct($club_id = null)
    {
        $this->db = new mysql();
        if ($club_id != null) {
            $this->id = $club_id;
            $club_info = $this->db->exec_select('club', [
                'name',
                'university_id',
                'intro',
                'portrait',
                'password'
            ], ['id' => $club_id], 1)[0];
            $this->name = $club_info['name'];
            $this->uid = $club_info['university_id'];
            $this->intro = $club_info['intro'];
            $this->portrait = $club_info['portrait'];
            $this->password = $club_info['password'];
            //query university name
            $this->uname = $this->db->exec_select('university', ['name'], ['id' => $this->uid], 1)['name'];
        }
    }

    function add($name, $uid, $portrait, $intro, $password)
    {
        return $this->db->exec_operation_safe(
            $this->db->gen_sql_insert('club', [
                'name' => $name,
                'university_id' => $uid,
                'portrait' => $portrait,
                'intro' => $intro,
                'password' => md5($password)
            ])
        );
    }

    function rename($new_name)
    {
        if ($this->id != null) {
            return $this->db->exec_operation_safe(
                $this->db->gen_sql_update('club', ['name' => $new_name], ['id' => $this->id])
            );
        } else {
            return false;
        }
    }

    function update_portrait($new_portrait)
    {
        if ($this->id != null) {
            return $this->db->exec_operation_safe(
                $this->db->gen_sql_update('club', ['portrait' => $new_portrait], ['id' => $this->id])
            );
        } else {
            return false;
        }
    }

    function update_intro($new_intro)
    {
        if ($this->id != null) {
            return $this->db->exec_operation_safe(
                $this->db->gen_sql_update('club', ['intro' => $new_intro], ['id' => $this->id])
            );
        } else {
            return false;
        }
    }

    function update_password($new_password)
    {
        if ($this->id != null) {
            return $this->db->exec_operation_safe(
                $this->db->gen_sql_update('club', ['password' => md5($new_password)], ['id' => $this->id])
            );
        } else {
            return false;
        }
    }

    function login_verify($login_password)
    {
        if ($this->id != null) {
            if (md5($login_password) != $this->password) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    function getId()
    {
        return $this->id;
    }

    function getName()
    {
        return $this->name;
    }

    function getUniversityId()
    {
        return $this->uid;
    }

    function getUniversityName()
    {
        return $this->uname;
    }

    function getPortrait()
    {
        return $this->portrait;
    }

    function getIntro()
    {
        return $this->intro;
    }

    //functions for announcement

    function add_announcements($text)
    {
        return $this->db->exec_operation_safe(
            $this->db->gen_sql_insert('announcement', ['club_id' => $this->id, 'text' => $text])
        );
    }

    function load_announcements($limit = 10, $offset = 0)
    {
        $announcement_set = $this->db->exec_select_order_DESC('announcement', ['id', 'text', 'datetime'], ['club_id' => $this->id], 'datetime', $limit, $offset);
        return json_encode($announcement_set, JSON_UNESCAPED_UNICODE);
    }

    function update_announcement($announcement_id, $new_announcement)
    {
        return $this->db->exec_operation_safe(
            $this->db->gen_sql_update('announcement', [
                'text' => $new_announcement
            ], [
                'id' => $announcement_id
            ])
        );
    }

    function delete_announcement($announcement_id)
    {
        return $this->db->exec_operation_safe(
            $this->db->gen_sql_delete('announcement', [
                'id' => $announcement_id
            ])
        );
    }

    //end of function set for announcement

    //functions for department

    function add_department($name, $intro, $isClose)
    {
        if ($isClose == 0 || $isClose == 1) {
            return $this->db->exec_operation_safe(
                $this->db->gen_sql_insert('department', [
                    'name' => $name,
                    'club_id' => $this->id,
                    'intro' => $intro,
                    'isClose' => $isClose
                ])
            );
        } else {
            return false;
        }
    }

    function update_department_name($department_id, $new_name)
    {
        return $this->db->exec_operation_safe(
            $this->db->gen_sql_update('department', [
                'name' => $new_name
            ], [
                'id' => $department_id
            ])
        );
    }

    function update_department_intro($department_id, $new_intro)
    {
        return $this->db->exec_operation_safe(
            $this->db->gen_sql_update('department', [
                'intro' => $new_intro
            ], [
                'id' => $department_id
            ])
        );
    }

    function update_department_isClose($department_id, $isClose)
    {
        if ($isClose == 1 || $isClose == 0) {
            return $this->db->exec_operation_safe(
                $this->db->gen_sql_update('department', [
                    'isClose' => $isClose
                ], [
                    'id' => $department_id
                ])
            );
        } else {
            return false;
        }
    }

    function update_department($department_id, $new_name, $new_intro, $isClose)
    {
        if ($isClose == 1 || $isClose == 0) {
            return $this->db->exec_operation_safe(
                $this->db->gen_sql_update('department', [
                    'name' => $new_name,
                    'intro' => $new_intro,
                    'isClose' => $isClose
                ], [
                    'id' => $department_id
                ])
            );
        } else {
            return false;
        }
    }

    function delete_department($del_department_id, $new_department_id)
    {
        return $this->db->exec_operation_safe(
            $this->db->gen_sql_update('member', [
                'department_id' => $new_department_id
            ], [
                'department_id' => $del_department_id
            ]),
            $this->db->gen_sql_update('application', [
                'department_id' => $new_department_id
            ], [
                'department_id' => $del_department_id
            ]),
            $this->db->gen_sql_delete('department', [
                'id' => $del_department_id
            ])
        );
    }

    function get_departments_open()
    {
        return $this->db->exec_select('department', [
            'id',
            'name',
            'intro'
        ], [
            'club_id' => $this->id,
            'isClose' => 0
        ]);
    }

    function get_departments_all()
    {
        return $this->db->exec_select('department', [
            'id',
            'name',
            'intro'
        ], [
            'club_id' => $this->id,
        ]);
    }

    //end of function set of department

    //functions for position

    function add_position($name, $intro, $isMarker)
    {
        if ($isMarker == 0 || $isMarker == 1) {
            return $this->db->exec_operation_safe(
                $this->db->gen_sql_insert('position', [
                    'club_id' => $this->id,
                    'name' => $name,
                    'intro' => $intro,
                    'isMarker' => $isMarker
                ])
            );
        } else {
            return false;
        }
    }

    function rename_position($position_id, $new_name)
    {
        return $this->db->exec_operation_safe(
            $this->db->gen_sql_update('position', [
                'name' => $new_name
            ], [
                'id' => $position_id,
                'club_id' => $this->id
            ])
        );
    }

    function update_position_intro($position_id, $new_intro)
    {
        return $this->db->exec_operation_safe(
            $this->db->gen_sql_update('position', [
                'intro' => $new_intro
            ], [
                'id' => $position_id,
                'club_id' => $this->id
            ])
        );
    }

    function update_position_isMarker($position_id, $new_isMarker)
    {
        if ($new_isMarker == 0 || $new_isMarker == 1) {
            return $this->db->exec_operation_safe(
                $this->db->gen_sql_update('position', [
                    'isMarker' => $new_isMarker
                ], [
                    'id' => $position_id,
                    'club_id' => $this->id
                ])
            );
        } else {
            return false;
        }
    }

    function update_position($position_id, $new_name, $new_intro, $new_isMarker)
    {
        if ($new_isMarker == 0 || $new_isMarker == 1) {
            return $this->db->exec_operation_safe(
                $this->db->gen_sql_update('position', [
                    'name' => $new_name,
                    'intro' => $new_intro,
                    'isMarker' => $new_isMarker
                ], [
                    'id' => $position_id,
                    'club_id' => $this->id
                ])
            );
        } else {
            return false;
        }
    }

    function delete_position($del_position_id)
    {
        return $this->db->exec_operation_safe(
            $this->db->gen_sql_delete('member_position', [
                'position_id' => $del_position_id
            ]),
            $this->db->gen_sql_delete('marks_application', [
                'position_id' => $del_position_id
            ]),
            $this->db->gen_sql_delete('position', [
                'id' => $del_position_id
            ])
        );
    }

    function set_member_position($student_id, $position_id)
    {
        return $this->db->exec_operation_safe(
            $this->db->gen_sql_insert('member_position', [
                'student_id' => $student_id,
                'position_id' => $position_id,
                'club_id' => $this->id
            ])
        );
    }

    function update_member_position($student_id, $new_position_id)
    {
        return $this->db->exec_operation_safe(
            $this->db->gen_sql_update('member_position', [
                'position_id' => $new_position_id
            ], [
                'student_id' => $student_id,
                'club_id' => $this->id
            ])
        );
    }

    function delete_member_position($student_id)
    {
        return $this->db->exec_operation_safe(
            $this->db->gen_sql_delete('member_position', [
                'student_id' => $student_id,
                'club_id' => $this->id
            ])
        );
    }

    //end of function set of position

    //functions for question

    function add_question($question)
    {
        return $this->db->exec_operation_safe(
            $this->db->gen_sql_insert('question', [
                'club_id' => $this->id,
                'question' => $question
            ])
        );
    }

    function update_question($question_id, $new_question)
    {
        return $this->db->exec_operation_safe(
            $this->db->gen_sql_update('question', [
                'question' => $new_question
            ], [
                'id' => $question_id,
                'club_id' => $this->id
            ])
        );
    }

    function delete_question($question_id)
    {
        return $this->db->exec_operation_safe(
            $this->db->gen_sql_delete('question_application', [
                'question_id' => $question_id
            ]),
            $this->db->gen_sql_delete('question', [
                'id' => $question_id
            ])
        );
    }

    //end of function set of question

    //functions for club history

    function add_club_event($event_text, $date)
    {
        return $this->db->exec_operation_safe(
            $this->db->gen_sql_insert('event', [
                'club_id' => $this->id,
                'event' => $event_text,
                'date' => $date
            ])
        );
    }

    function update_club_event($event_id, $new_event_text, $date)
    {
        return $this->db->exec_operation_safe(
            $this->db->gen_sql_update('event', [
                'event' => $new_event_text,
                'date' => $date
            ], [
                'id' => $event_id,
                'club_id' => $this->id
            ])
        );
    }

    function delete_club_event($event_id)
    {
        return $this->db->exec_operation_safe(
            $this->db->gen_sql_delete('event', [
                'id' => $event_id
            ])
        );
    }

    function load_events($club_id, $limit, $offset)
    {
        $data = $this->db->exec_select_order_DESC('event', [
            'id',
            'event',
            'date'
        ], [
            'club_id' => $club_id
        ], 'date', $limit, $offset);
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}