<?php include './templates/inc/header.php'; ?>

<script>
    $(document).ready(function() {
        $("#modal-confirm").click(function() {
            $('#deleteModal').modal('toggle');
        });
    });
</script>


<?php
require_once 'models/Job.php';
$job_id = $_GET['id'] ?? null;
$job = new Job;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Delete' && isset($_POST['delete_id'])) {
        $job_id = $_POST['delete_id'];
        if ($job->deleteJobById($job_id)) {
            redirect('index.php', 'Job record deleted.', 'success');
        } else {
            redirect('index.php', 'Error : Job record not deleted.', 'error');
        }
    }
}
$job_details = $job->getJobById($job_id);
// echo '<pre>';
// var_dump($job_details);
// var_dump($job_details->id);
// echo '</pre>';
?>
<h2 class="page-header"><?php echo $job_details->job_title ?? ''; ?></h2>
<p>Posted : <?php echo $job_details->post_date ?? ''; ?></p>
<p>Category : <?php echo $job->getCategory($job_details->category_id)->name; ?></p>
<p>Company : <?php echo $job_details->company ?? ''; ?></p>
<p>Description : </p>
<p class=""><?php echo $job_details->description ?? ''; ?></p>
<ul class="list-group">
    <li class="list-group-item">Location : <?php echo $job_details->location ?? ''; ?></li>
    <li class="list-group-item">Salary : SGD$<?php echo $job_details->salary ?? ''; ?></li>
    <li class="list-group-item">Contact Person : <?php echo $job_details->contact_user ?? ''; ?></li>
    <li class="list-group-item">Contact Email : <?php echo $job_details->contact_email ?? ''; ?></li>
</ul>
<br>
<div class="">
    <a href="index.php" class="btn btn-primary">Back</a>
    <!-- Edit button -->
    <form id="edit" style="display:inline;" action="jobsave.php" method="post">
        <input type="hidden" name="edit_id" value="<?php echo $job_details->id ?>">
        <input type="submit" class="btn btn-warning" name='submit' value="Edit">
    </form>
    <!-- Button trigger delete modal -->
    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
        Delete
    </button>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="deleteModalLabel">Delete Job Listing</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Confirm delete record?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="delete" style="display:inline;" action="jobdetails.php" method="post">
                    <input type="hidden" name="delete_id" value="<?php echo $job_details->id ?? '' ?>">
                    <input type="submit" id="modal-confirm" class="btn btn-danger" name='submit' value="Delete">
                </form>
            </div>
        </div>
    </div>
</div>

<br><br>
<!-- Bootstrap JS Bundle with Popper -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->
<?php include './templates/inc/footer.php'; ?>