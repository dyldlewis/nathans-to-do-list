<?php
    class Task
    {
        private $description;
        private $due_date;
        private $id;

        function __construct($description, $due_date, $id = null)
        {
            $this->description = $description;
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
            $executed = $GLOBALS['DB']->exec("INSERT INTO tasks (description, due_date) VALUES ('{$this->getDescription()}', '{$this->getDueDate()}')");
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
                $task_due_date = $task['due_date'];
                $task_id = $task['id'];
                $new_task = new Task($task_description, $task_due_date, $task_id);
                array_push($tasks, $new_task);
            }
            return $tasks;
        }

        static function deleteAll()
        {
            $executed = $GLOBALS['DB']->exec("DELETE FROM tasks;");
            if ($executed) {
                return true;
            } else {
                return false;
            }
        }

        static function find($search_id)
        {
            $returned_tasks = $GLOBALS['DB']->prepare("SELECT * FROM tasks WHERE id = :id");
            $returned_tasks->bindParam(':id', $search_id, PDO::PARAM_STR);
            $returned_tasks->execute();
            foreach ($returned_tasks as $task) {
                $task_description = $task['description'];
                $task_due_date = $task['due_date'];
                $task_id = $task['id'];
                if ($task_id == $search_id) {
                    $found_task = new Task($task_description, $task_due_date, $task_id);
                }
            }
            return $found_task;
        }

        function update($new_description)
        {
            $executed = $GLOBALS['DB']->exec("UPDATE tasks SET description = '{$new_description}' WHERE id = {$this->getId()};");
            if ($executed) {
               $this->setDescription($new_description);
               return true;
            } else {
               return false;
            }
        }

        function delete()
        {
            $executed = $GLOBALS['DB']->exec("DELETE FROM tasks WHERE id = {$this->getId()};");
             if ($executed) {
                return true;
            } else {
                return false;
            }
        }
    }
 ?>
