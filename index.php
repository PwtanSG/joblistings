<?php include './templates/inc/header.php'; ?>

<?php
require_once 'models/Job.php';

$category = $_GET['category'] ?? null;

$title = 'Latest Jobs';
$job = new Job;
$categories = $job->getJobCategories();
if ($category) {
    $category_id = $_GET['category'];
    if (is_numeric($category_id)) {
        $title = 'Jobs in ' . $job->getCategory($category_id)->name;
        $jobs = $job->getJobsByCategory($category_id);
    } else {
        $jobs = $job->getAllJobs();
    }
} else {
    $jobs = $job->getAllJobs();
    // var_dump($jobs);
}

?>
<div class="jumbotron">
    <h1>Find jobs</h1>
    <form method="GET" action="index.php">
        <select name="category" class="form-control">
            <option value="">Select Category</option>
            <?php foreach ($categories as $category) : ?>
                <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <input type="submit" class="btn btn-success" value="Find">
    </form>
</div>

<h3><?php echo $title; ?> <span class="badge"><?php echo count($jobs) ?></span></h3>
<?php foreach ($jobs as $job) : ?>
    <?php
    $last = new DateTime($job->post_date);
    $now = new DateTime("now");
    $diff = $last->diff($now);
    $days_diff = $diff->format('%a');
    $tag = (intval($days_diff) < 3) ? '&nbsp<span class="label label-warning">New</span>' : '';
    ?>
    <div class="row marketing">
        <div class="col-md-10">
            <h4><?php echo $job->job_title;
                echo $tag; ?></h4>
            <p><?php echo "Company : $job->company"; ?></p>
            <p><?php echo "Salary : SGD$ $job->salary"; ?></p>
            <p><?php echo "Posted : $job->post_date"; ?></p>
        </div>
        <div class="col-md-2">
            <a href="jobdetails.php?id=<?php echo $job->id; ?>" class="btn btn-primary">View</a>
        </div>
    </div>
<?php endforeach; ?>

<?php include './templates/inc/footer.php'; ?>