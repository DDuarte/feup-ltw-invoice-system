<?php

require_once 'xml_utils.php';

class User
{
    public function queryDbById($userId)
    {
        if (empty($userId) || !is_numeric($userId))
        {
            return 400;
        }

        $db = new PDO('sqlite:../sql/OIS.db');

        $userStmt = $db->prepare('SELECT username, name FROM user JOIN role ON user.role_id = role.id WHERE user.id = :id;');
        $userStmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $userStmt->execute();

        $userResult = $userStmt->fetch();

        if ($userResult == null)
        {
            return 404;
        }

        $this->_username = $userResult['username'];
        $this->_role = $userResult['name'];

        return 0;
    }

    public function toArray()
    {
        return [
            'Username'  => $this->_username,
            'Role'      => $this->_role
        ];
    }

    public function encode($type)
    {
        if ($type != "xml" && $type != "json")
            return "";

        $array = $this->toArray();

        if ($type == "xml")
            return xml_encode(["User" => $array]);
        else
            return json_encode($array);
    }

    private $_username;
    private $_role;
}
