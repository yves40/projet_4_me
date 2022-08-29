<?php

namespace App\Repository;

use App\Core\Db;
use App\Core\Logger;
use App\Core\Main;
use PDO;
use PDOException;

class CommentsDB extends Db
{
    
    public const  COMMENT_REJECTED = 10;
    public const  COMMENT_SIGNALED = 20;
    public const  COMMENT_VISIBLE = 30;
    public const  COMMENT_ACCEPTED = 40;

    private $default = CommentsDB::COMMENT_VISIBLE;

    private Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger(__CLASS__);
    }

    public function createComment(array $params)
    {   
        $billetID = $params['billetID'];
        $content = $params['content'];
        $usersModel = Main::$main->getUsersModel();
        $userId = $usersModel->getId();
        date_default_timezone_set('Europe/Brussels');

        try
        {
            $this->db = Db::getInstance();

            $statement = $this->db->prepare('INSERT INTO comments (content, users_id, billet_id) 
                                                VALUES (:content, :users_id, :billet_id)');
            
            $statement->bindValue(':content', $content);
            $statement->bindValue(':users_id', $userId);
            $statement->bindValue(':billet_id', $billetID);

            $statement->execute();
            return true; 
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return false;
        }       
    }

    public function getComments($billetID)
    {
        try
        {   
            $this->db = Db::getInstance();

            $statement = $this->db->prepare('SELECT content, publish_at, users_id, pseudo, c.id, c.report, DATE_FORMAT(publish_at, "%W %d %m, %H:%i") formatted_date FROM comments c, users u 
                                            WHERE billet_id = :billetID AND users_id = u.id AND report >= 30 ORDER BY formatted_date DESC');
            
            $statement->bindValue(':billetID', $billetID);
            $statement->execute();
            $result = $statement->fetchAll();

            if(!empty($result))
            {
                return $this->dateConverter($result);
            }
            return array();
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return false;
        }
    }

    public function getSignaledComments($fetchflag = false)
    {
        try
        {
            $this->db = Db::getInstance();

            $statement = $this->db->prepare('SELECT content, c.publish_at, c.users_id, c.id, pseudo, billet_id, title, DATE_FORMAT(c.publish_at, "%W %d %m, %H:%i") formatted_date 
                                             FROM comments c, users u, billets b 
                                                WHERE c.users_id = u.id 
                                                        AND c.billet_id = b.id 
                                                        AND c.report = ' . CommentsDB::COMMENT_SIGNALED . 
                                                        ' ORDER BY c.publish_at DESC;');
                        
            $statement->execute();
            $result = $statement->fetchAll();

            if(!empty($result))
            {
                return $this->dateConverter($result);
            }
            return array();
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return array();
        }
    }

    /**Use to fix date format behaviour with PDO*/
    public function dateConverter($data)
    {
        $french_months = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
        foreach($data as $key => $value)
        {
            $elements = explode(" ", $value->formatted_date);
            $i = intval($elements[2]) - 1;
            $elements[2] = $french_months[$i];
            $data[$key]->formatted_date = implode(" ", $elements);
        }
        return $data;
    }

    public function signalComment($id)
    {
        try
        {
            $this->db = Db::getInstance();
            $statement = $this->db->prepare('UPDATE comments SET report = '. CommentsDB::COMMENT_SIGNALED .' WHERE id = :id');
            $statement->bindValue(':id', $id);
            return $statement->execute();
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return false;
        }
    }

    public function acceptComment($id)
    {
        try
        {
            $this->db = Db::getInstance();
            $statement = $this->db->prepare('UPDATE comments SET report = ' . CommentsDB::COMMENT_ACCEPTED . ' WHERE id = :id');

            $statement->bindValue(':id', $id);
            return $statement->execute();
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return false;
        }
    }

    public function rejectComment($id)
    {
        try
        {
            $this->db = Db::getInstance();
            $statement = $this->db->prepare('UPDATE comments SET report = '. CommentsDB::COMMENT_REJECTED .' WHERE id = :id');

            $statement->bindValue(':id', $id);
            return $statement->execute();
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return false;
        }
    }
}

?>