<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 7/8/15
 * Time: 15:25
 */
require_once 'mysql.php';

class application
{
    private $db = null;
    private $id = null;
    private $club_id = null;
    private $club = null;
    private $department_id = null;
    private $department = null;

    function __construct($club_id, $id = null)
    {
        $this->db = new mysql();
        $this->club_id = $club_id;
        $this->id = $id;
        if ($id != null) {
            $this->club = $this->db->exec_select('club', [
                'name'
            ], [
                'club_id' => $this->club_id
            ], 1)[0]['name'];
            $this->department_id = $this->db->exec_select('application', [
                'department_id'
            ], [
                'id' => $this->id
            ], 1)[0]['department_id'];
            $this->department = $this->db->exec_select('department', [
                'name'
            ], [
                'id' => $this->department_id
            ]);
        }
    }

    function get_applications($limit = 500, $offset = 0)
    {
        $ids = $this->db->exec_select('application', ['id'], ['club_id' => $this->club_id], $limit, $offset);
        return json_encode($ids, JSON_UNESCAPED_UNICODE);
    }

    function get_current_details()
    {
        $basic = $this->db->exec_select('application', [
            'name',
            'student_id',
            'telephone',
            'email',
            'qq',
            'portrait',
            'grade',
            'subject',
            'class'
        ], [
            'id' => $this->id,
            'club_id' => $this->club_id
        ], 1)[0];
        $question_info = $this->db->exec_select('question', ['id', 'question'], ['club_id' => $this->club_id]);
        foreach ($question_info as $info) {
            $question_id = $info['id'];
            $question_question = $info['question'];
            $answer = $this->db->exec_select('question_application', [
                'answer'
            ], [
                'question_id' => $question_id,
                'application_id' => $this->id
            ], 1)[0]['answer'];
            $basic[$question_question] = $answer;
        }
        return json_encode($basic, JSON_UNESCAPED_UNICODE);
    }

    function accept()//accept the current application
    {
        if ($this->id != null) {
            $application_data = $this->db->exec_select('application', [
                'club_id',
                'department_id',
                'student_id',
                'name',
                'telephone',
                'email',
                'qq',
                'portrait',
                'grade',
                'subject',
                'class'
            ], [
                'id' => $this->id,
                'club_id' => $this->club_id
            ], 1)[0];
            return $this->db->exec_operation_safe(
                $this->db->gen_sql_insert('member', $application_data),
                $this->db->gen_sql_delete('question_application', [
                    'application_id' => $this->id
                ]),
                $this->db->gen_sql_delete('marks_application', [
                    'application_id' => $this->id
                ]),
                $this->db->gen_sql_delete('application', [
                    'id' => $this->id,
                    'club_id' => $this->club_id
                ])
            );
        } else {
            return false;
        }
    }

    function deny()//deny the current application
    {
        if ($this->id != null) {
            return $this->db->exec_operation_safe(
                $this->db->gen_sql_delete('question_application', [
                    'application_id' => $this->id
                ]),
                $this->db->gen_sql_delete('marks_application', [
                    'application_id' => $this->id
                ]),
                $this->db->gen_sql_delete('application', [
                    'id' => $this->id,
                    'club_id' => $this->club_id
                ])
            );
        } else {
            return false;
        }
    }

    function auto_handle_application()
    {
        if ($this->id != null) {
            $marks = $this->db->exec_select('marks_application', [
                'mark'
            ], [
                'application_id' => $this->id
            ]);
            $count = count($marks);
            if ($count > 0) {
                $sum = 0;
                foreach ($marks as $mark) {
                    $sum += $mark['mark'];
                }
                if ($sum / $count >= 5) {
                    //do accept
                    $this->accept();
                } else {
                    //do deny
                    $this->deny();
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function get_questions()
    {
        $questions = $this->db->exec_select('question', [
            'id',
            'question'
        ], [
            'club_id' => $this->club_id
        ]);
        return json_encode($questions, JSON_UNESCAPED_UNICODE);
    }

    function get_questions_internal()
    {
        return $this->db->exec_select('question', [
            'id',
            'question'
        ], [
            'club_id' => $this->club_id
        ]);
    }

    function add_application($department_id, $student_id, $name, $telephone, $email, $qq, $portrait, $grade, $subject, $class, $answer_json)
    {
        if ($this->db->exec_operation_safe(
            $this->db->gen_sql_insert('application', [
                'club_id' => $this->club_id,
                'department_id' => $department_id,
                'student_id' => $student_id,
                'name' => $name,
                'telephone' => $telephone,
                'email' => $email,
                'qq' => $qq,
                'portrait' => $portrait,
                'grade' => $grade,
                'subject' => $subject,
                'class' => $class
            ])
        )
        ) {
            $application_id = $this->db->exec_select('application', [
                'id'
            ], [
                'club_id' => $this->club_id,
                'student_id' => $student_id
            ])[0]['id'];
            $answer_array = json_decode($answer_json, true);
            foreach ($answer_array as $answer_set) {
                $question_id = $answer_set['id'];
                $answer = $answer_set['answer'];
                $this->db->exec_operation_safe(
                    $this->db->gen_sql_insert('question_application', [
                        'question_id' => $question_id,
                        'application_id' => $application_id,
                        'answer' => $answer
                    ])
                );
            }
            return true;
        } else {
            return false;
        }
    }


}