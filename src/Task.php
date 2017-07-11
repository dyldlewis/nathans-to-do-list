<?php
    class Task
    {
        private $description;
        private $category_id;
        private $id;
        private $due_date;

        function __construct($description, $category_id, $id = null, $due_date)
        {
            $this->description = $description;
            $this->category_id = $category_id;
            $this->id = $id;
            $this->due_date = $due_date;
        }

        function setDescription($new_description)
        {
            $this->description = (string) $new_description;
        }

        function getDescription()
        {
            return $this->description;
        }

        function getID()
        {
            return $this->id;
        }

        function getCategoryId()
        {
            return $this->category_id;
        }

        function setDueDate($new_due_date)
        {
            $this->due_date = (string) $new_due_date;
        }

        function getDueDate()
        {
            return $this->due_date;
        }

        function save()
        {
            $executed = $GLOBALS['DB']->exec("INSERT INTO tasks (description, category_id, due_date) VALUES ('{$this->getDescription()}', {$this->getCategoryId()}, '{$this->getDueDate()}')");
            if ($executed) {
                $this->id = $GLOBALS['DB']->lastInsertID();
                return true;
            } else {
                return false;
            }
        }

        static function getAll()
        {
            $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks;");
            $tasks = array();
            foreach($returned_tasks as $task) {
                $task_description = $task['description'];
                $category_id = $task['category_id'];
                $task_id = $task['id'];
                $task_due_date = $task['due_date'];
                $new_task = new Task($task_description, $category_id, $task_id, $task_due_date);
                array_push($tasks, $new_task);
            }
            return $tasks;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM tasks;");
        }

        static function find($search_id)
        {
            $returned_tasks = $GLOBALS['DB']->prepare("SELECT * FROM tasks WHERE id = :id");
            $returned_tasks->bindParam(':id', $search_id, PDO::PARAM_STR);
            $returned_tasks->execute();
            foreach ($returned_tasks as $task) {
                $task_description = $task['description'];
                $category_id = $task['category_id'];
                $task_id = $task['id'];
                $task_due_date = $task['due_date'];
                if ($task_id == $search_id) {
                    $found_task = new Task($task_description, $category_id, $task_id, $task_due_date);
                }
            }
            return $found_task;
        }
    }
 ?>
