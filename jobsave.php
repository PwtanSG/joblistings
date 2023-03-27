<?php include './templates/inc/header.php'; ?>
<?php
require_once 'models/Job.php';

$job = new Job;
$categories = $job->getJobCategories();
$page_function = "Create";
$edit_id = null;
$job_details = [];
$form_data = array();
$save_job = false;
$validate_results = array();
$validate_errors = array();
// echo '<pre>';
// var_dump($_POST);
// echo '</pre>';


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $edit_id = null;

    // echo '<pre>';
    // var_dump($_POST);
    // echo '</pre>';

    if ($_POST['submit'] == 'Edit' && isset($_POST['edit_id'])) {
        $edit_id = $_POST['edit_id'];
        $page_function = "Update";
        $job_details = $job->getJobById($edit_id);
        $form_data['id'] = sanitize_input($job_details->id ?? '');
        $form_data['job_title'] = sanitize_input($job_details->job_title ?? '');
        $form_data['company'] = sanitize_input($job_details->company ?? '');
        $form_data['category_id'] = $job_details->category_id ?? '';
        // $form_data['description'] = sanitize_input($job_details->description  ?? '');
        $form_data['description'] = $job_details->description  ?? '';
        $form_data['location'] = sanitize_input($job_details->location ?? '');
        $form_data['salary'] = sanitize_input($job_details->salary  ?? '');
        $form_data['contact_user'] = sanitize_input($job_details->contact_user  ?? '');
        $form_data['contact_email'] = sanitize_input($job_details->contact_email  ?? '', 'email');
    } elseif ($_POST['submit'] == 'Create' || $_POST['submit'] == 'Update') {
        $validate_results = array();
        $validate_errors = array();
        if ($_POST['submit'] == 'Update') {
            $page_function = "Update";
        }

        //create POST form_data in array
        $form_data['job_title'] = sanitize_input($_POST['job_title'] ?? '');
        $form_data['company'] = sanitize_input($_POST['company'] ?? '');
        $form_data['category_id'] = $_POST['category'] ?? '';
        // $form_data['description'] = sanitize_input($_POST['description']  ?? '');
        $form_data['description'] = $_POST['description']  ?? '';
        $form_data['location'] = sanitize_input($_POST['location'] ?? '');
        $form_data['salary'] = sanitize_input($_POST['salary']  ?? '');
        $form_data['contact_user'] = sanitize_input($_POST['contact_user']  ?? '');
        $form_data['contact_email'] = sanitize_input($_POST['contact_email']  ?? '', 'email');

        $edit_id = isset($_POST['job_id']) ? (!empty($_POST['job_id']) ? $_POST['job_id'] : null) : null;

        $validate_results['job_title'] = validate_field($form_data['job_title'], "string", true);
        $validate_results['company'] = validate_field($form_data['company'], "string", true);
        $validate_results['category_id'] = validate_field($form_data['category_id'], "string", true);
        $validate_results['description'] = validate_field($form_data['description'], "string", true);
        $validate_results['location'] = validate_field($form_data['location'], "string", true);
        $validate_results['salary'] = validate_field($form_data['salary'], "string", true);
        $validate_results['contact_user'] = validate_field($form_data['contact_user'], "string", true);
        $validate_results['contact_email'] = validate_field($form_data['contact_email'], "email", true);
        $validate_errors = array_filter($validate_results, function ($v) {
            return $v != "";
        });

        // echo '<pre>';
        // var_dump($validate_errors);
        // var_dump(count($validate_errors));
        // var_dump($form_data);
        // var_dump($edit_id);
        // echo '</pre>';

        if (count($validate_errors) == 0) {
            if ($_POST['submit'] == 'Create') {
                $save_job = $job->create_job($form_data);
            } elseif ($_POST['submit'] == 'Update' && $edit_id) {
                $save_job = $job->update_job($edit_id, $form_data);
            } else {
                redirect('index.php', $page_function . ' job listing : Error', 'error');
            }

            if ($save_job) {
                redirect('index.php', $page_function . ' job listing successfully.', 'success');
            } else {
                redirect('index.php', $page_function . ' job listing : Error', 'error');
            }
        }
    }
}

?>
<h3><?php echo "$page_function Job Listing" ?></h3>
<form action="" method="post" novalidate>

    <div class="form-outline mb-4">
        <input type="hidden" id="job_id" name="job_id" class="form-control" value="<?php echo $form_data['id'] ?? ''; ?>" required />
    </div>
    <div class="form-outline mb-4">
        <label class="form-label" for="Company">Company name</label>
        <span class="text-danger">* <?php echo $validate_errors['company'] ?? "" ?></span>
        <input type="text" id="Company" name="company" class="form-control <?php echo isset($validate_errors['company']) ? 'is-invalid' : ''; ?>" value="<?php echo $form_data['company'] ?? ''; ?>" required />
    </div>

    <div class="form-outline mb-4">
        <label class="form-label" for="job_title">Job Title</label>
        <span class="text-danger">* <?php echo $validate_errors['job_title'] ?? "" ?></span>
        <input type="text" id="job_title" name="job_title" class="form-control <?php echo isset($validate_errors['job_title']) ? 'is-invalid' : ''; ?>" value="<?php echo $form_data['job_title'] ?? ''; ?>" required />
    </div>

    <div class="form-outline mb-4">
        <label class="form-label" for="Company">Job Category</label>
        <span class="text-danger">* <?php echo $validate_errors['category_id'] ?? "" ?></span>
        <select name="category" class="form-control <?php echo isset($validate_errors['category_id']) ? 'is-invalid' : ''; ?>" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $category) : ?>
                <?php $selected = isset($form_data['category_id']) ? (($form_data['category_id'] == $category->id) ? 'Selected' : '') : ''; ?>" ?>
                <option value="<?php echo $category->id; ?>" <?php echo $selected ?>><?php echo $category->name; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-outline mb-4">
        <label class="form-label" for="description">Job Description</label>
        <span class="text-danger">*<?php echo $validate_errors['description'] ?? "" ?></span>
        <textarea class="form-control <?php echo isset($validate_errors['description']) ? 'is-invalid' : ''; ?>" id="description" name="description" rows="4" required><?php echo $form_data['description'] ?? ''; ?></textarea>
    </div>

    <div class="form-outline mb-4">
        <label class="form-label" for="Company">Monthly Salary</label>
        <span class="text-danger">*<?php echo $validate_errors['salary'] ?? "" ?></span>
        <input type="text" id="salary" name="salary" class="form-control <?php echo isset($validate_errors['salary']) ? 'is-invalid' : ''; ?>" value="<?php echo $form_data['salary'] ?? ''; ?>" required />
    </div>

    <div class="form-outline mb-4">
        <label class="form-label" for="Company">Location</label>
        <span class="text-danger">* <?php echo $validate_errors['location'] ?? "" ?></span>
        <input type="text" id="location" name="location" class="form-control <?php echo isset($validate_errors['location']) ? 'is-invalid' : ''; ?>" value="<?php echo $form_data['location'] ?? ''; ?>" required />
    </div>

    <div class="form-outline mb-4">
        <label class="form-label" for="contact_user">Contact Person </label>
        <span class="text-danger">* <?php echo $validate_errors['contact_user'] ?? "" ?></span>
        <input type="text" id="contact_user" name="contact_user" class="form-control <?php echo isset($validate_errors['contact_user']) ? 'is-invalid' : ''; ?>" value="<?php echo $form_data['contact_user'] ?? ''; ?>" required />
    </div>

    <div class="form-outline mb-8">
        <label class="form-label" for="contact_email">Email</label>
        <span class="text-danger">* <?php echo $validate_errors['contact_email'] ?? "" ?></span>
        <input type="email" id="contact_email" name="contact_email" class="form-control <?php echo isset($validate_errors['contact_email']) ? 'is-invalid' : ''; ?>" value="<?php echo $form_data['contact_email'] ?? ''; ?>" required />
    </div>
    <br>
    <!-- buttons -->
    <button type="submit" value="<?php echo $page_function; ?>" name="submit" class="btn btn-primary btn-block"><?php echo $page_function; ?> </button>
    <br>
    <a href="index.php" class="btn btn-primary btn-block">Back</a>
</form>
<!-- Script -->
<script>
    ClassicEditor
        .create(document.querySelector('#description'), {
            toolbar: ['undo', 'redo', 'bold', 'italic', 'numberedList', 'bulletedList']
        })
        .then(editor => {
            editor.ui.view.editable.element.style.height = '100px';
        })
        .catch(error => {
            console.log(error);
        });
</script>
<?php include './templates/inc/footer.php'; ?>