<?php 
require_once dirname(__DIR__)."./Config.php";
require_once dirname(__DIR__)."./Helper/Log.php";

class Exercises {
    
    protected $table = 'exercises';
    
    public $id;
    public $name;
    public $summary;
    public $content;
    public $fileName;
    public $filePath;
    public $courseId;
    public $studentId;
    public $subjectId;
    public $commentId;
    public $userId;
    public $status;
    public $createdAt;
    public $updatedAt;

    public function setId($id) {
        $this->id = $id;
    }
    public function getId() {
        return $this->id;
    }
    public function setName($name) {
        $this->name = $name;
    }
    public function getName() {
        return $this->name;
    }
    public function setSummary($summary) {
        $this->summary = $summary;
    }
    public function getSummary() {
        return $this->summary;
    }
    public function setContent($content) {
        $this->content = $content;
    }
    public function getContent() {
        return $this->content;
    }
    public function setFileName($fileName) {
        $this->fileName = $fileName;
    }
    public function getFileName() {
        return $this->fileName;
    }
    public function setFilePath($filePath) {
        $this->filePath = $filePath;
    }
    public function getFilePath() {
        return $this->filePath;
    }
    public function setCourseId($courseId) {
        $this->courseId = $courseId;
    }
    public function getCourseId() {
        return $this->courseId;
    }
    public function setStudentId($studentId) {
        $this->studentId = $studentId;
    }
    public function getStudentId() {
        return $this->studentId;
    }
    public function setSubjectId($subjectId) {
        $this->subjectId = $subjectId;
    }
    public function getSubjectId() {
        return $this->subjectId;
    }
    public function setCommentId($commentId) {
        $this->commentId = $commentId;
    }
    public function getCommentId() {
        return $this->commentId;
    }
    public function setUserId($userId) {
        $this->userId = $userId;
    }
    public function getUserId() {
        return $this->userId;
    }
    public function setVoteStatus($status) {
        $this->status = $status;
    }
    public function getVoteStatus() {
        return $this->status;
    }
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }
    public function getCreatedAt() {
        return $this->createdAt;
    }
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }
    public function getUpdatedAt() {
        return $this->updatedAt;
    }
}
