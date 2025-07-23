<?php
class User{
    private $id;
    private $name;
    private $age;

    function __construct($id, $name, $age){
        $this->id = $id;
        $this->name = $name;
        $this->setAge($age);
    }

    public function setAge($age){
        if($age >= 18){
            $this->age = $age;
        }
    }

    public function getDetails(){
        return "ID: {$this->id}, Name: {$this->name}, Age: {$this->age}";
    }

}

$user = new User(1, "Alice Johnson", 25);
echo $user->getDetails();
$user->setAge(24);