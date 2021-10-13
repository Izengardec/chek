<?php
class CreateTest{
    // подключение к базе данных и таблице 'products'
    private $conn;
    private $table_name = "products";

    // свойства объекта
    public $subj_id;
    public $title;
    public $user_id;
    public $reason;
    public $ins_id;

    // конструктор для соединения с базой данных
    public function __construct($db){
        $this->conn = $db;
    }

    function create(){
        
        // запрос для вставки (создания) записей
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                subj_id=:subj_id, title=:title, user_id=:user_id;";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // очистка
        $this->subj_id=htmlspecialchars(strip_tags($this->subj_id));
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));

        // привязка значений
        $stmt->bindParam(":subj_id", $this->subj_id);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":user_id", $this->user_id);

        // выполняем запрос
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function get_last_id(){
        // запрос для вставки (создания) записей
        $query = "SELECT 
                " . $this->table_name . "
            SET
                subj_id=:subj_id, title=:title, user_id=:user_id;
                SELECT id
                FROM " . $this->table_name . "
                ORDER BY id DESC
                LIMIT 1";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);
        // выполняем запрос
        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC))
            extract($row);
            $this->ins_id = $id;
            return true;
        }
        return false;
    }
}