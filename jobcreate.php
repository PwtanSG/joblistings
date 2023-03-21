<?php include './templates/inc/header.php'; ?>
<?php
require_once 'models/Job.php';

$job = new Job;
$categories = $job->getJobCategories();

// echo '<pre>';
// var_dump($_POST);
// echo '</pre>';


if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['submit'])) {
    //create POST data in array
    $data = array();
    $data['job_title'] = $_POST['job_title'];
    $data['company'] = $_POST['company'];
    $data['category_id'] = $_POST['category'];
    $data['description'] = $_POST['description'];
    $data['location'] = $_POST['location'];
    $data['salary'] = $_POST['salary'];
    $data['contact_user'] = $_POST['contact_user'];
    $data['contact_email'] = $_POST['contact_email'];

    // echo '<pre>';
    // var_dump($data);
    // echo '</pre>';

    if ($job->create_job($data)) {
        redirect('index.php', 'Your job has been created successfully.', 'success');
    } else {
        redirect('index.php', 'Error creating job listing!', 'error');
    }
}

?>
<h3>Create Job Listing</h3>
<form action="jobcreate.php" method="post">

    <div class="form-outline mb-4">
        <label class="form-label" for="Company">Company name</label>
        <input type="text" id="Company" name="company" class="form-control" required />
    </div>

    <div class="form-outline mb-4">
        <label class="form-label" for="job_title">Job Title</label>
        <input type="text" id="job_title" name="job_title" class="form-control" required />
    </div>

    <div class="form-outline mb-4">
        <label class="form-label" for="Company">Job Category</label>
        <select name="category" class="form-control" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $category) : ?>
                <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-outline mb-4">
        <label class="form-label" for="description">Job Description</label>
        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
    </div>

    <div class="form-outline mb-4">
        <label class="form-label" for="Company">Monthly Salary</label>
        <input type="text" id="salary" name="salary" class="form-control" required/>
    </div>

    <div class="form-outline mb-4">
        <label class="form-label" for="Company">Location</label>
        <input type="text" id="location" name="location" class="form-control" required />
    </div>

    <div class="form-outline mb-4">
        <label class="form-label" for="contact_user">Contact Person </label>
        <input type="text" id="contact_user" name="contact_user" class="form-control" required />
    </div>

    <div class="form-outline mb-8">
        <label class="form-label" for="contact_email">Email</label>
        <input type="email" id="contact_email" name="contact_email" class="form-control" required />
    </div>
    <br>
    <!-- Submit button -->
    <button type="submit" value="submit" name="submit" class="btn btn-primary btn-block">Submit</button>
    <br>
    <a href="index.php" class="btn btn-primary btn-block">Back</a>
</form>
<?php include './templates/inc/footer.php'; ?>