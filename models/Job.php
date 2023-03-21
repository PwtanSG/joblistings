<?php
require_once 'Database.php';

class Job
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllJobs()
    {
        $this->db->query("
            SELECT jobs.*, categories.name AS cname
            FROM jobs
            INNER JOIN categories
            ON jobs.category_id = categories.id
            ORDER BY post_date DESC
        ");

        //result set
        $results = $this->db->resultSet();
        return $results;
    }

    public function getJobCategories()
    {
        $this->db->query("SELECT * FROM categories");
        $results = $this->db->resultSet();
        return $results;
    }

    public function getJobsByCategory($category_id)
    {
        $this->db->query("
        SELECT jobs.*, categories.name AS cname
        FROM jobs
        INNER JOIN categories
        ON jobs.category_id = categories.id
        WHERE jobs.category_id = $category_id
        ORDER BY post_date DESC
    ");

        //result set
        $results = $this->db->resultSet();
        return $results;
    }

    public function getJobById($id)
    {
        $this->db->query("SELECT * FROM jobs WHERE id=:id");
        $this->db->bind(":id", $id);
        $row = $this->db->single();
        return $row;
    }

    public function create_job($data)
    {
        $this->db->query("
            INSERT INTO jobs (category_id, job_title, company, description, location, salary, contact_user, contact_email)
            VALUES (:category_id, :job_title, :company, :description, :location, :salary, :contact_user, :contact_email)
        ");
        $this->db->bind(":category_id", $data['category_id']);
        $this->db->bind(":job_title", $data['job_title']);
        $this->db->bind(":company", $data['company']);
        $this->db->bind(":description", $data['description']);
        $this->db->bind(":location", $data['location']);
        $this->db->bind(":salary", $data['salary']);
        $this->db->bind(":contact_user", $data['contact_user']);
        $this->db->bind(":contact_email", $data['contact_email']);
        return $this->db->execute() ? true : false;
    }

    public function update_job($edit_id, $data)
    {
        $this->db->query("
            UPDATE jobs
            SET
            category_id = :category_id, 
            job_title = :job_title, 
            company = :company, 
            description = :description, 
            location = :location, 
            salary = :salary, 
            contact_user = :contact_user, 
            contact_email = :contact_email
            WHERE id = :edit_id
        ");
        $this->db->bind(":category_id", $data['category_id']);
        $this->db->bind(":job_title", $data['job_title']);
        $this->db->bind(":company", $data['company']);
        $this->db->bind(":description", $data['description']);
        $this->db->bind(":location", $data['location']);
        $this->db->bind(":salary", $data['salary']);
        $this->db->bind(":contact_user", $data['contact_user']);
        $this->db->bind(":contact_email", $data['contact_email']);
        $this->db->bind(":edit_id", $edit_id);
        return $this->db->execute() ? true : false;
    }

    public function deleteJobById($id)
    {
        if (!$id || !is_numeric($id)) {
            return false;
        }
        $this->db->query("DELETE FROM jobs WHERE id=:id");
        $this->db->bind(":id", $id);
        return $this->db->execute() ? true : false;
    }

    public function getCategory($category_id)
    {
        $this->db->query("SELECT * FROM categories WHERE id= :category_id");
        $this->db->bind(":category_id", $category_id);
        $row = $this->db->single();
        return $row;
    }
}
