<?php

class UserServices extends Users {

    /**
     * connection to database
     * @param string
     * @return PDO
     */
    private $connection;

    protected $user;
    protected $course;
    protected $subject;
    protected $exercise;
    protected $comment;

    public function __construct()
    {   
        $this->comment = new Users();
        $this->connection = Config::connect();
    }

    public function create($input){
        // if (!$input->isValid()) return false;
        try {
            $sql ="INSERT INTO ".$this->table." (`name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES (:name, :email, :password, :role, :createdAt, :updatedAt)";
            $stmt = $this->connection->prepare($sql);
            $data = [
                ':name' => $input->getName(),
                ':email' => $input->getEmail(),
                ':password' => md5($input->getPassword()),
                ':role' => $input->getRole(),
                ':createdAt' => $input->getCreatedAt(),
                ':updatedAt' => $input->getUpdatedAt(),
            ];
            if($stmt->execute($data)){
                return true;
            } else {
                return false;
            }

        } catch(Exception $e){
            Log::logError($e->getMessage());
            return false;
        }
    }
    
    public function index(){
        try {
            $sql = "SELECT * FROM ".$this->table;
            $stmt = $this->connection->prepare($sql);
            
            if($stmt->execute()){
               $data = $stmt->fetchAll();
            } else {
                $data = [];
            }
            return $data;
        } catch(Exception $e) {
             Log::logError($e->getMessage());
             return $data = [];
        }
    }

    public function getUser($id){
        try{
            $sql ="SELECT * FROM ".$this->table." WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            $data = [
                ':id' => $id
            ];
            $stmt->execute($data);
            $data = $stmt->fetch();
            if($stmt->rowCount() == 1) {
                return $data;
            }
        }
        
        catch(Exception $e){
             Log::logError($e->getMessage());
            return $data = [];
        } 
    }

    public function getUserWithRole($params = ''){
        try{
            $sql = "SELECT * FROM ".$this->table." WHERE role = :role";
            $stmt = $this->connection->prepare($sql);
            $data = [
                ':role' => $params
            ];
            $stmt->execute($data);
            $data = $stmt->fetchAll();        
            return $data;
        }
        
        catch(Exception $e){
             Log::logError($e->getMessage());
            return [];
        } 
    }

    public function logged($email,$password){
        try {
            $sql = "SELECT * FROM ".$this->table." WHERE email = :email AND password = :password";
            $stmt = $this->connection->prepare($sql);
            $data = [
                ':email' => $email,
                ':password' => md5($password)
            ];
            $stmt->execute($data);
            if($stmt->rowCount() == 1) {
                $result = $stmt->fetch();
                return $result;
            } else {
                return [];
            }
        
        } catch(Exception $e){
            Log::logError($e->getMessage());
            return [];
        }
    }

    public function getStudentList($id){
        try {
            $sql ="SELECT * FROM subject_list WHERE `subject_id` = :subject_id ORDER BY add_date DESC";
            $stmt = $this->connection->prepare($sql);
            $data = [
                ':subject_id' => $id,
            ];
                if($stmt->execute($data)){
                    $result = $stmt->fetchAll();
                    return $result;
                } else {
                    return [];
                }
        } catch(Exception $e){
            Log::logError($e->getMessage());
            return [];
        }
    }

    public function getStudents($id,$courseId){
        try {
            $sql =" SELECT * FROM subject_list WHERE `student_id` = :student_id AND `course_id` = :course_id ";
            $stmt = $this->connection->prepare($sql);
            $data = [
                ':student_id' => $id,
                ':course_id' => $courseId
            ];
                if($stmt->execute($data)){
                    $result = $stmt->fetchAll();
                    return $result;
                } else {
                    return [];
                }
        } catch(Exception $e){
            Log::logError($e->getMessage());
            return [];
        }
    }
    public function getStudentSubject($id,$courseId,$subjectId){
        try {
            $sql =" SELECT * FROM subject_list WHERE `student_id` = :student_id AND `course_id` = :course_id AND subject_id = :subject_id ";
            $stmt = $this->connection->prepare($sql);
            $data = [
                ':student_id' => $id,
                ':course_id' => $courseId,
                ':subject_id' => $subjectId
            ];
                if($stmt->execute($data)){
                    $result = $stmt->fetchAll();
                    return $result;
                } else {
                    return [];
                }
        } catch(Exception $e){
            Log::logError($e->getMessage());
            return [];
        }
    }

    public function isUserAlreadyVoted($input) {
        try {
            $sql ="SELECT * FROM `votes` WHERE `user_id` = :id AND `exercise_id` = :exercise_id ";
            $stmt = $this->connection->prepare($sql);
            $data = [
                ':id' => $input->getId(),
                ':exercise_id' => $input->getExerciseId()
            ];

            $stmt->execute($data);
            $result = $stmt->fetch();
            if(!empty($result)){
                return true;
            }  else {
                return false;
            }
        } catch(Exception $e){
            Log::logError($e->getMessage());
            return false;
        }
    }
    public function updateVote($input) {
        try {
            $sql ="UPDATE votes SET `status` = :status,`updated_at` = :updated_at  WHERE `user_id` = :id AND `exercise_id` = :exercise_id";
            $stmt = $this->connection->prepare($sql);
            $data = [
                ':status' => $input->getVoteStatus(),
                ':updated_at' => $input->getUpdatedAt(),
                ':id' => $input->getId(),
                ':exercise_id' => $input->getExerciseId(),
            ];

            if($stmt->execute($data)){
                return true;
            } else {
                return false;
            }
        } catch(Exception $e){
            Log::logError($e->getMessage());
            return false;
        }
    }
    public function vote($input) {
        try {
            $sql ="INSERT INTO `votes` (`user_id`, `exercise_id`, `status`,`created_at`, `updated_at`) VALUES (:user_id, :exercise_id, :status, :created_at, :updated_at)";
            $stmt = $this->connection->prepare($sql);
            $data = [
                ':user_id' => $input->getId(),
                ':exercise_id' => $input->getExerciseId(),
                ':status' => $input->getVoteStatus(),
                ':created_at' => $input->getCreatedAt(),
                ':updated_at' => $input->getUpdatedAt(),
            ];

            if($stmt->execute($data)){
                return true;
            } else {
                return false;
            }
        } catch(Exception $e){
            Log::logError($e->getMessage());
            return false;
        }
    }
    
    public function unVote($input) {
        try {
            $sql ="DELETE FROM votes WHERE `user_id`= :user_id AND `exercise_id` = :exercise_id ";
            $stmt = $this->connection->prepare($sql);
            $data = [
                ':user_id' => $input->getId(),
                ':exercise_id' => $input->getExerciseId()
            ];

            if($stmt->execute($data)){
                return true;
            } else {
                return false;
            }
        } catch(Exception $e){
            Log::logError($e->getMessage());
            return false;
        }
    }

    public  function userLike($input){
        try {
            $sql =" SELECT * FROM votes WHERE `user_id` = :user_id AND `exercise_id` = :exercise_id AND `status` = :status ";
            $stmt = $this->connection->prepare($sql);
            $data = [
                ':user_id' => $input->getId(),
                ':exercise_id' => $input->getExerciseId(),
                ':status' => 'like',
            ];
                if($stmt->execute($data)){
                    $result = $stmt->fetch();
                    if(!empty($result)) {
                        return true;
                    }
                } else {
                    return false;
                }
        } catch(Exception $e){
            Log::logError($e->getMessage());
            return false;
        }
    }

    public  function userDisLike($input){
        try {
            $sql =" SELECT * FROM votes WHERE `user_id` = :user_id AND `exercise_id` = :exercise_id AND `status` = :status ";
            $stmt = $this->connection->prepare($sql);
            $data = [
                ':user_id' => $input->getId(),
                ':exercise_id' => $input->getExerciseId(),
                ':status' => 'disLike',
            ];
                if($stmt->execute($data)){
                    $result = $stmt->fetch();
                    if(!empty($result)) {
                        return true;
                    }
                } else {
                    return false;
                }
        } catch(Exception $e){
            Log::logError($e->getMessage());
            return false;
        }
    }

    public  function getLikes($id){
        try {
            $sql =" SELECT COUNT(*) FROM votes WHERE `exercise_id` = :exercise_id AND `status` = :status ";
            $stmt = $this->connection->prepare($sql);
            $data = [
                ':exercise_id' => $id,
                ':status' => 'like'
            ];

            if($stmt->execute($data)){
               $count = $stmt->fetchColumn();
                return $count;
            } else {
                return 0;
            }
        } catch(Exception $e){
            Log::logError($e->getMessage());
            return 0;
        }
    }

    public  function getDisLikes($id){
        try {
            $sql =" SELECT COUNT(*) FROM votes WHERE `exercise_id` = :exercise_id AND `status` = :status ";
            $stmt = $this->connection->prepare($sql);
            $data = [
                ':exercise_id' => $id,
                ':status' => 'disLike'
            ];

            if($stmt->execute($data)){
                $count = $stmt->fetchColumn();
                return $count;
            } else {
                return 0;
            }
        } catch(Exception $e){
            Log::logError($e->getMessage());
            return 0;
        }
    }
}
