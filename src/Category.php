<?php
    class Category
    {
        private $name;
        private $id;

        function __construct($name, $id = null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        function getName()
        {
            return $this->name;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {

            $executed = $GLOBALS['DB']->exec("INSERT INTO categories (name) VALUES ('{$this->getName()}')");
            if ($executed) {
                 $this->id= $GLOBALS['DB']->lastInsertId();
                 return true;
            } else {
                 return false;
            }
        }

        static function getAll()
        {
            $returned_categories = $GLOBALS['DB']->query("SELECT * FROM categories;");
            $categories = array();
            foreach($returned_categories as $category) {
                $name = $category['name'];
                $id = $category['id'];
                $new_category = new Category($name, $id);
                array_push($categories, $new_category);
            }
            return $categories;
        }

        static function deleteAll()
        {
          $executed = $GLOBALS['DB']->exec("DELETE FROM categories;");
          if ($executed) {
              return true;
          } else {
              return false;
          }
        }

        static function find($search_id)
        {
            $found_category = null;
            $returned_categories = $GLOBALS['DB']->prepare("SELECT * FROM categories WHERE id = :id");
            $returned_categories->bindParam(':id', $search_id, PDO::PARAM_STR);
            $returned_categories->execute();
            foreach($returned_categories as $category) {
                $category_name = $category['name'];
                $id = $category['id'];
                if ($id == $search_id) {
                  $found_category = new Category($category_name, $id);
                }
            }
            return $found_category;
        }

        function update($new_name)
        {
            $executed = $GLOBALS['DB']->exec("UPDATE categories SET name = '{$new_name}' WHERE id = {$this->getId()};");
            if ($executed) {
               $this->setName($new_name);
               return true;
            } else {
               return false;
            }
        }

        function addTask($task)
        {
            $executed = $GLOBALS['DB']->exec("INSERT INTO categories_tasks (category_id, task_id) VALUES ({$this->getId()}, {$task->getId()});");
            if ($executed) {
                return true;
            } else {
                return false;
            }
        }

        function getTasks()
        {
            $returned_tasks = $GLOBALS['DB']->query("SELECT tasks.* FROM categories
                JOIN categories_tasks ON (categories_tasks.category_id = categories.id)
                JOIN tasks ON (tasks.id = categories_tasks.task_id)
                WHERE categories.id = {$this->getId()};");
            $tasks = array();
            foreach($returned_tasks as $task) {
                $description = $task['description'];
                $id = $task['id'];
                $due_date = $task['due_date'];
                $new_task = new Task($description, $due_date, $id);
                array_push($tasks, $new_task);
            }
            return $tasks;
        }

        function delete()
        {
            $executed = $GLOBALS['DB']->exec("DELETE FROM categories WHERE id = {$this->getId()};");
             if (!$executed) {
                 return false;
             }
             $executed = $GLOBALS['DB']->exec("DELETE FROM categories_tasks WHERE category_id = {$this->getId()};");
             if (!$executed) {
                 return false;
             } else {
                 return true;
             }
        }


    }
?>
