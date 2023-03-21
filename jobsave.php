<?php include './templates/inc/header.php'; ?>
<?php
require_once 'models/Job.php';

$job = new Job;
$categories = $job->getJobCategories();
$page_function = "Create";
$edit_id = null;
$job_details = [];
$save_job = false;
// echo '<pre>';
// var_dump($_POST);
// echo '</pre>';


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $edit_id = null;

    // echo '<pre>';
    // var_dump($data);
    // var_dump($_POST);
    // echo '</pre>';

    if ($_POST['submit'] == 'Edit' && isset($_POST['edit_id'])) {
        $edit_id = $_POST['edit_id'];
        $page_function = "Update";
        $job_details = $job->getJobById($edit_id);
    } elseif ($_POST['submit'] == 'Create' || $_POST['submit'] == 'Update') {

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

        $edit_id = isset($_POST['job_id']) ? (!empty($_POST['job_id']) ? $_POST['job_id'] : null) : null;

        if ($_POST['submit'] == 'Create') {
            $save_job = $job->create_job($data);
        } elseif ($_POST['submit'] == 'Update' && $edit_id) {
            $save_job = $job->update_job($edit_id, $data);
        } else {
            redirect('index.php', $page_function . ' job listing : Error1', 'error');
        }

        if ($save_job) {
            redirect('index.php', $page_function . ' job listing successfully.', 'success');
        } else {
            redirect('index.php', $page_function . ' job listing : Error', 'error');
        }
    }
}

?>
<h3><?php echo "$page_function Job Listing" ?></h3>
<form action="jobsave.php" method="post">

    <div class="form-outline mb-4">
        <!-- <label class="form-label" for="Company">Job Id</label> -->
        <input type="hidden" id="job_id" name="job_id" class="form-control" value="<?php echo ($job_details->id) ?? ''; ?>" required />
    </div>
    <div class="form-outline mb-4">
        <label class="form-label" for="Company">Company name</label>
        <input type="text" id="Company" name="company" class="form-control" value="<?php echo ($job_details->company) ?? ''; ?>" required />
    </div>

    <div class="form-outline mb-4">
        <label class="form-label" for="job_title">Job Title</label>
        <input type="text" id="job_title" name="job_title" class="form-control" value="<?php echo ($job_details->job_title) ?? ''; ?>" required />
    </div>

    <div class="form-outline mb-4">
        <label class="form-label" for="Company">Job Category</label>
        <select name="category" class="form-control" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $category) : ?>
                <?php $selected = isset($job_details->category_id) ? (($job_details->category_id == $category->id) ? 'Selected' : '') : ''; ?>" ?>
                <option value="<?php echo $category->id; ?>" <?php echo $selected ?>><?php echo $category->name; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-outline mb-4">
        <label class="form-label" for="description">Job Description</label>
        <textarea class="form-control" id="description" name="description" rows="4" required><?php echo ($job_details->description) ?? ''; ?></textarea>
    </div>

    <div class="form-outline mb-4">
        <label class="form-label" for="Company">Monthly Salary</label>
        <input type="text" id="salary" name="salary" class="form-control" value="<?php echo ($job_details->salary) ?? ''; ?>" required />
    </div>

    <div class="form-outline mb-4">
        <label class="form-label" for="Company">Location</label>
        <input type="text" id="location" name="location" class="form-control" value="<?php echo ($job_details->location) ?? ''; ?>" required />
    </div>

    <div class="form-outline mb-4">
        <label class="form-label" for="contact_user">Contact Person </label>
        <input type="text" id="contact_user" name="contact_user" class="form-control" value="<?php echo ($job_details->contact_user) ?? ''; ?>" required />
    </div>

    <div class="form-outline mb-8">
        <label class="form-label" for="contact_email">Email</label>
        <input type="email" id="contact_email" name="contact_email" class="form-control" value="<?php echo ($job_details->contact_email) ?? ''; ?>" required />
    </div>
    <br>
    <!-- Submit button -->
    <button type="submit" value="<?php echo $page_function; ?>" name="submit" class="btn btn-primary btn-block"><?php echo $page_function; ?> </button>
    <br>
    <a href="index.php" class="btn btn-primary btn-block">Back</a>
</form>
<?php include './templates/inc/footer.php'; ?>