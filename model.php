<?php
require_once('db.php');

class Job
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function job_save(array $data)
    {
        try {
            foreach ($data as $key => $value) {
                if (!empty($key)) {
                    $topic_id = $this->_return_create_topic($key);
                    $results = $this->_save_options($value, $topic_id);
                } else {
                    throw new Exception('Empty section.');
                }

            }
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    private function _save_options($array, $t_id)
    {

        $options = array();
        foreach ($array as $option) {
            $options[] = array('topic_id' => $t_id,
                'name' => $option[0],
                'value' => $option[1]);
        }

        return $this->create_options($options);
    }

    public function create_options($array)
    {
        return $this->db->multi_insert("INSERT INTO options (topic_id, name, value ) VALUE (:topic_id, :name, :value)", $array);
    }

    public function create_topic($title)
    {
        $this->db->query("INSERT INTO topics (name) VALUE (?)", $title);
        return $this->db->getLastId();
    }

    public function get_topic_id($title)
    {
        $statement = $this->db->query("SELECT id FROM topics WHERE name = ?", $title);
        $result = $statement->fetch(PDO::FETCH_NUM);
        return $result[0];
    }

    private function _return_create_topic($title)
    {
        $topic_id = $this->get_topic_id($title);

        if ($topic_id) {
            return $topic_id;
        } else {
            return $this->create_topic($title);
        }

    }

    private function _extract_values($arr)
    {
        $result = array();
        $result['options'] = array();

        for ($i = 0; $i < sizeof($arr); $i++) {
            //get predefined params
            if ($arr[$i][1] == 'Должность') {
                $result['position'] = $arr[$i][2];
                unset($arr[$i]);
            } elseif ($arr[$i][1] == 'Дата регистрации вакансии') {
                $result['date'] = $arr[$i][2];
                unset($arr[$i]);
            } else {
                //changed array structure to make one topic as parent for options
                $result['sections'][$arr[$i][3]][] = array($arr[$i][1], $arr[$i][2], $arr[$i][0]);
            }
        }

        return $result;
    }

    public function get_job()
    {
        $statement = $this->db->query("SELECT options.id, options.name, options.value, topics.name FROM options
                                      LEFT JOIN topics ON topics.id = options.topic_id;");
        $result = $statement->fetchAll(PDO::FETCH_NUM);
        $extracted = $this->_extract_values($result);

        return $extracted;
    }


    private function _prepare_arry($arr)
    {
        $new_arr = array();
        foreach ($arr as $key => $value) {
            preg_match_all('/^([^\d]+)(\d+)/', $key, $match);
            //$text = $match[1][0];
            //$num = $match[2][0];

            //id, name, value
            $new_arr[$match[2][0]]['id'] = $match[2][0];
            $new_arr[$match[2][0]][$match[1][0]] = $value;
        }

        return $new_arr;
    }

    public function save_job()
    {
        //TODO: add validation
        $prepared = $this->_prepare_arry($_POST);
        return $this->db->multi_insert("UPDATE options SET name = :name, value = :value WHERE id = :id", $prepared);
    }


}