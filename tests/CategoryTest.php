<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Category.php";
    require_once "src/Task.php";

    $server = 'mysql:host=localhost:8889;dbname=to_do_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CategoryTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
          Category::deleteAll();
          Task::deleteAll();
        }

        function getIdetName()
        {
            //Arrange
            $name = "Work stuff";
            $test_category = new Category($name);

            //Act
            $result = $test_category->getName();

            //Assert
            $this->assertEquals($name, $result);
        }

       function testSave()
        {
            //Arrange
            $name = "Work stuff";
            $test_category = new Category($name);

            //Act
            $executed = $test_category->save();

            // Assert
            $this->assertTrue($executed, "Category not successfully saved to database");
        }

        function testGetId()
        {
            //Arrange
            $name = "Work stuff";
            $test_category = new Category($name);
            $test_category->save();

            //Act
            $result = $test_category->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function testGetAll()
        {
            //Arrange
            $name = "Work stuff";
            $name_2 = "Home stuff";
            $test_category = new Category($name);
            $test_category->save();
            $test_category_2 = new Category($name_2);
            $test_category_2->save();

            //Act
            $result = Category::getAll();

            //Assert
            $this->assertEquals([$test_category, $test_category_2], $result);
        }

        function testDeleteAll()
        {
            //Arrange
            $name = "Wash the dog";
            $name_2 = "Home stuff";
            $test_category = new Category($name);
            $test_category->save();
            $test_category_2 = new Category($name_2);
            $test_category_2->save();

            //Act
            Category::deleteAll();
            $result = Category::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function testFind()
        {
            //Arrange
            $name = "Wash the dog";
            $name2 = "Home stuff";
            $test_category = new Category($name);
            $test_category->save();
            $test_category_2 = new Category($name2);
            $test_category_2->save();

            //Act
            $result = Category::find($test_category->getId());

            //Assert
            $this->assertEquals($test_category, $result);
        }

        function testGetTasks()
        {
            //Arrange
            $name = "Work stuff";
            $test_category = new Category($name);
            $test_category->save();

            $description = "Email client";
            $due_date = "12-23-4456";
            $completed = false;
            $id = null;
            $test_task = new Task($description, $due_date, $completed);
            $test_task->save();

            $description2 = "Meet with boss";
            $due_date2 = "94-24-2142";
            $completed2 = false;
            $id = null;
            $test_task2 = new Task($description2, $due_date2, $completed2);
            $test_task2->save();

            //Act
            $test_category->addTask($test_task);
            $test_category->addTask($test_task2);

            //Assert
            $this->assertEquals($test_category->getTasks(), [$test_task, $test_task2]);
        }

        function testUpdate()
        {
            $name = "Work stuff";
            $test_category = new Category($name);
            $test_category->save();

            $new_name = "Home stuff";

            $test_category->update($new_name);

            $this->assertEquals("Home stuff", $test_category->getName());
        }

        function testDelete()
        {
            //Arrange
            $name = "Work stuff";
            $test_category = new Category($name);
            $test_category->save();

            $name_2 = "Home stuff";
            $test_category_2 = new Category($name_2);
            $test_category_2->save();


            //Act
            $test_category->delete();

            //Assert
            $this->assertEquals([$test_category_2], Category::getAll());
        }

        function testAddTask()
        {
            $name = "work stuff";
            $test_category = new Category($name);
            $test_category->save();

            $description = "File reports";
            $due_date = "04-12-1030";
            $completed = false;
            $test_task = new Task($description, $due_date, $completed);
            $test_task->save();

            $test_category->addTask($test_task);

            $this->assertEquals($test_category->getTasks(), [$test_task]);
        }
    }

?>
