<?php

class SubjectServices extends Subjects {

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
        $this->comment = new Subjects();
        $this->connection = Config::connect();
    }

    public function create($input){
        try {
            $sql1 ="INSERT INTO ".$this->table." (`title`, `teacher_id`,`created_by`, `created_at`, `updated_at`) VALUES (:title, :teacher_id, :created_by, :created_at, :updated_at)";
            $stmt = $this->connection->prepare($sql1);
            $data1 = [
                ':title' => $input->getTitle(),
                ':teacher_id' => $input->getTeacherId(),
                ':created_by' => $input->getCreatedBy(),
                ':created_at' => $input->getCreatedAt(),
                ':updated_at' => $input->getUpdatedAt(),
            ];
            if($stmt->execute($data1)){
                $sql2 = "INSERT INTO course_subjects (`course_id`,`subject_id`,`created_at`,`updated_at`) VALUES (:course_id,:subject_id,:createdAt,:updateAt)";
                $stmt = $this->connection->prepare($sql2);
                $data2 = [
                    ':course_id' => $input->getCourseId(),
                    ':subject_id' => $this->connection->lastInsertId(),
                    ':createdAt' => $input->getCreatedAt(),
                    ':updateAt' => $input->getUpdatedAt(),
                ];
                if($stmt->execute($data2)){
                    return true;
                } else {
                    return false;
                }
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
            $stmt = $this->connection->prepare("SELECT * FROM course_list  ORDER BY subject_id");
            
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
    public function getCurrentCourseSubjects($courseId){
        try {
            $stmt = $this->connection->prepare("SELECT * FROM course_list WHERE course_id = :course_id ORDER BY subject_id");
            
            $data = [
                'course_id' => $courseId,
            ];
            if($stmt->execute($data)){
                $result = $stmt->fetchAll();
            } else {
                $result = [];
            }
            return $result;
        } catch(Exception $e) {
             Log::logError($e->getMessage());
             return $result = [];
        }
    }
    
    public function getTeacherSubjects($teacherId,$courseId){
        try{
            $stmt = $this->connection->prepare("SELECT * FROM course_list WHERE (teacher_id = :teacher_id AND course_id = :course_id)");
            $data = [
                ':teacher_id' => $teacherId,
                ':course_id' => $courseId,
            ];
            $stmt->execute($data);
            $data = $stmt->fetchAll();
            if(!empty($data)) {
                return $data;
            } else {
                return $data = [];
            }
        }
        
        catch(Exception $e){
             Log::logError($e->getMessage());
            return $data = [];
        } 
    }
    public function get($id,$courseId){
        try{
            $stmt = $this->connection->prepare("SELECT * FROM course_list WHERE (id = :id AND course_id = :course_id)");
            $data = [
                ':id' => $id,
                ':course_id' => $courseId,
            ];
            $stmt->execute($data);
            $data = $stmt->fetch();
            if(!empty($data)) {
                return $data;
            } else {
                return $data = [];
            }
        }
        
        catch(Exception $e){
             Log::logError($e->getMessage());
            return $data = [];
        } 
    }

    public function updateTeacher($input){
        try {
            $sql1 ="UPDATE ".$this->table." SET `teacher_id` = :teacher_id,`created_by` = :created_by , `updated_at` = :updated_at WHERE `id` = :id";
            $stmt = $this->connection->prepare($sql1);
            $data = [
                ':teacher_id' => $input->getTeacherId(),
                ':created_by' => $input->getCreatedBy(),
                ':updated_at' => $input->getUpdatedAt(),
                ':id' => $input->getId(),
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

    public function updateSubject($input){
        try {
            $sql1 ="UPDATE ".$this->table." SET `content` = :content,`updated_at` = :updated_at WHERE `id` = :id ";
            $stmt = $this->connection->prepare($sql1);
            $data1 = [
                ':content' => $input->getContent(),
                ':updated_at' => $input->getUpdatedAt(),
                ':id' => $input->getId(),
            ];
            if($stmt->execute($data1)){
                $sql2 = "INSERT INTO student_subjects (`student_id`,`subject_id`,`created_at`,`updated_at`) VALUES (:student_id,:subject_id,:created_at,:updated_at)";
                $stmt = $this->connection->prepare($sql2);
                $data2 = [
                    ':student_id' => $input->getStudentId(),
                    ':subject_id' => $input->getId(),
                    ':created_at' => $input->getCreatedAt(),
                    ':updated_at' => $input->getUpdatedAt(),
                ];
                if($stmt->execute($data2)){
                    return true;
                } else {
                    Log::logError("Cannot update subject content!");
                    return false;
                }
            } else {
                Log::logError("Cannot update subject!");
                return false;
            }
        } catch(Exception $e){
            Log::logError($e->getMessage());
            return false;
        }
    }
    
    public function getNumberOfLikes($id,$courseId){
        try{
            $sql = "SELECT COUNT(*) FROM vote_list WHERE subject_id = :subject_id AND course_id = :course_id AND vote_status = :vote_status ";  
            $stmt = $this->connection->prepare($sql);
            $data = [
                ':subject_id' => $id,
                ':course_id' => $courseId,
                ':vote_status' => "like",
            ];
            $stmt->execute($data);
            $result = $stmt->fetch(PDO::FETCH_COLUMN);
            if(!empty($result)) {
                return $result;
            } else {
                return $result = 0;
            }
        } catch(Exception $e){
            Log::logError($e->getMessage());
            return [];
        }
    }
    public function getMostSubmitted($courseId) {
        try{
            $sql = "SELECT s.id, s.title, s.teacher_id, count(e.id) AS total_submitted FROM subjects s 
            INNER JOIN exercises e ON s.id = e.subject_id 
            INNER JOIN courses c ON e.course_id = c.id 
            WHERE c.id = :course_id
            GROUP BY s.id ORDER BY total_submitted DESC LIMIT 1";  
            $stmt = $this->connection->prepare($sql);
            
            $data = [
                ':course_id' => $courseId,
            ];
            $stmt->execute($data);
            $result = $stmt->fetch();
            if(!empty($result)) {
                return $result;
            } else {
                return $result = [];
            }
        } catch(Exception $e){
            Log::logError($e->getMessage());
            return [];
        }
    }
    public function getMostRating($courseId) {
        try{
            $sql = "SELECT s.id, s.title, s.teacher_id, count(v.status) AS total_rate FROM subjects s 
            INNER JOIN exercises e ON s.id = e.subject_id 
            INNER JOIN votes v ON e.id = v.exercise_id 
            WHERE v.status = 'like' AND e.course_id = :course_id 
            GROUP BY s.id ORDER BY total_rate DESC LIMIT 1;";  
            $stmt = $this->connection->prepare($sql);
            
            $data = [
                ':course_id' => $courseId,
            ];
            $stmt->execute($data);
            $result = $stmt->fetch();
            if(!empty($result)) {
                return $result;
            } else {
                return $result = [];
            }
        } catch(Exception $e){
            Log::logError($e->getMessage());
            return [];
        }
    }
    public function getMostComments($courseId){
        try{
            $sql = "SELECT s.id, s.title, s.teacher_id, count(ce.comment_id) AS total_comment FROM subjects AS s 
            INNER JOIN exercises AS e ON s.id = e.subject_id 
            INNER JOIN comment_exercises AS ce ON e.id = ce.exercise_id
            WHERE  e.course_id = :course_id 
            GROUP BY s.id ORDER BY total_comment DESC LIMIT 1";  
            $stmt = $this->connection->prepare($sql);
            
            $data = [
                ':course_id' => $courseId,
            ];
            $stmt->execute($data);
            $result = $stmt->fetch();
            if(!empty($result)) {
                return $result;
            } else {
                return $result = [];
            }
        } catch(Exception $e){
            Log::logError($e->getMessage());
            return [];
        }
    }
    
    public function getNumberOfComments($id,$courseId){
        try{
            $sql = "SELECT COUNT(*) FROM comment_list WHERE subject_id = :subject_id AND course_id = :course_id";  
            $stmt = $this->connection->prepare($sql);
            $data = [
                ':subject_id' => $id,
                ':course_id' => $courseId,
            ];
            $stmt->execute($data);
            $result = $stmt->fetch(PDO::FETCH_COLUMN);
            if(!empty($result)) {
                return $result;
            } else {
                return $result = 0;
            }
        } catch(Exception $e){
            Log::logError($e->getMessage());
            return [];
        }
    }
    
}