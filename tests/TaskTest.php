<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Task.php";
    require_once "src/Category.php";

    $server = 'mysql:host=localhost:8889;dbname=to_do_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class TaskTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Task::deleteAll();
            Category::deleteAll();
        }

        function testGetDescription()
        {
            //Arrange
            $description = "Do dishes.";
            $due_date = "24-13-1521";
            $completed = false;
            $test_task = new Task($description, $due_date, $completed);

            //Act
            $result = $test_task->getDescription();

            //Assert
            $this->assertEquals($description, $result);
        }

        function testSetDescription()
        {
            //Arrange
            $description = "Do dishes.";
            $due_date = "32-21-3134";
            $completed = false;
            $test_task = new Task($description, $due_date, $completed);

            //Act
            $test_task->setDescription("Drink coffee.");
            $result = $test_task->getDescription();

            //Assert
            $this->assertEquals("Drink coffee.", $result);
        }


        function testGetId()
        {
            //Arrange
            $name = "Home stuff";
            $test_category = new Category($name);
            $test_category->save();

            $description = "Wash the dog";
            $due_date = "10-12-3456";
            $completed = false;
            $id = null;
            $test_task = new Task($description, $due_date, $completed, $id);
            $test_task->save();

            //Act
            $result = $test_task->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function testSave()
        {
            //Arrange
            $name = "Home stuff";
            $test_category = new Category($name);
            $test_category->save();

            $description = "Wash the dog";
            $due_date = "7-23-2014";
            $completed = false;
            $id = null;
            $test_task = new Task($description, $due_date, $completed, $id);

            //Act
            $executed = $test_task->save();

            //Assert
            $this->assertTrue($executed, "Task not successfully saved to database");
        }

        function testGetAll()
        {
            //Arrange
            $name = "Home stuff";
            $test_category = new Category($name);
            $test_category->save();

            $description = "Wash the dog";
            $due_date = "12-23-2345";
            $completed = false;
            $id = null;
            $test_task = new Task($description, $due_date, $completed, $id);
            $test_task->save();

            $description_2 = "Water the lawn";
            $due_date_2 = "12-25-2004";
            $completed_2 = false;
            $id_2 = null;
            $test_task_2 = new Task($description_2, $due_date_2, $completed_2, $id_2);
            $test_task_2->save();

            //Act
            $result = Task::getAll();

            //Assert
            $this->assertEquals([$test_task, $test_task_2], $result);
        }

        function testDeleteAll()
        {
            //Arrange
            $name = "Home stuff";
            $test_category = new Category($name);
            $test_category->save();

            $description = "Wash the dog";
            $due_date = "12-21-2005";
            $completed = false;
            $id = null;
            $test_task = new Task($description, $due_date, $completed, $id);
            $test_task->save();

            $description_2 = "Water the lawn";
            $due_date_2 = "12-21-2305";
            $completed_2 = false;
            $id_2 = null;
            $test_task_2 = new Task($description_2, $due_date_2, $completed_2, $id_2);
            $test_task_2->save();

            //Act
            Task::deleteAll();

            //Assert
            $result = Task::getAll();
            $this->assertEquals([], $result);
        }

        function testFind()
        {
            //Arrange
            $name = "Home stuff";
            $test_category = new Category($name);
            $test_category->save();

            $description = "Wash the dog";
            $due_date = "11-20-2345";
            $completed = false;
            $id = null;
            $test_task = new Task($description, $due_date, $completed, $id);
            $test_task->save();

            $description_2 = "Water the lawn";
            $due_date_2 = "10-20-3456";
            $completed_2 = false;
            $id_2 = null;
            $test_task_2 = new Task($description_2, $due_date_2, $completed_2, $id_2);
            $test_task_2->save();

            //Act
            $result = Task::find($test_task->getId());

            //Assert
            $this->assertEquals($test_task, $result);
        }

        function testUpdate()
        {
            //Arrange
            $description = "Wash the dog";
            $due_date = "01-23-1531";
            $completed = false;
            $test_task = new Task($description, $due_date, $completed);
            $test_task->save();

            $new_description = "Clean the dog";

            //Act
            $test_task->update($new_description);

            //Assert
            $this->assertEquals("Clean the dog", $test_task->getDescription());
        }

        function test_deleteTask()
        {
            //Arrange
            $description = "Wash the dog";
            $due_date = "09-21-1523";
            $completed = false;
            $test_task = new Task($description, $due_date, $completed);
            $test_task->save();

            $description2 = "Water the lawn";
            $due_date2 = "94-14-5252";
            $completed_2 = false;
            $test_task2 = new Task($description2, $due_date2, $completed_2);
            $test_task2->save();


            //Act
            $test_task->delete();

            //Assert
            $this->assertEquals([$test_task2], Task::getAll());
        }

        function testAddCategory()
        {
            //Arrange
            $name ="Work stuff";
            $test_category = new Category($name);
            $test_category->save();

            $description = "File reports";
            $due_date = "08-23-1234";
            $completed = false;
            $test_task = new Task($description, $due_date, $completed);
            $test_task->save();

            //Act
            $test_task->addCategory($test_category);

            //Assert
            $this->assertEquals($test_task->getCategories(), [$test_category]);
        }

        function test_getCategories()
        {
            //Arrange
            $name = "Work stuff";
            $test_category = new Category($name);
            $test_category->save();

            $name2 = "Volunteer stuff";
            $test_category2 = new Category($name2);
            $test_category2->save();


            $description = "File reports";
            $due_date = "12-13-1234";
            $completed = false;
            $test_task = new Task($description, $due_date, $completed);
            $test_task->save();

            //Act
            $test_task->addCategory($test_category);
            $test_task->addCategory($test_category2);

            //Assert
            $this->assertEquals($test_task->getCategories(), [$test_category, $test_category2]);
        }

        function testDelete()
        {
            //Arrange
            $name = "Work stuff";
            $test_category = new Category($name);
            $test_category->save();

            $description = "File reports";
            $due_date = "04-2-1423";
            $completed = false;
            $test_task = new Task($description, $due_date, $completed);
            $test_task->save();

            //Act
            $test_category->addTask($test_task);
            $test_category->delete();

            //Assert
            $this->assertEquals([], $test_task->getCategories());
        }
    }
?>
