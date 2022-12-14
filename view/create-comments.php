<?php
session_start();
include_once dirname(__DIR__)."./app/Services/StudentServices.php";

$user = new StudentServices();
$courses = $user->getExercises();
if($user->loggedIn()) {
if (isset($_POST['save'])) {
    $data = $user->createComments();
    if(!empty($data)){
        $message = 'Comment created successfully!';
    } else {
        $error = 'Comment created failed!';
    }
}
} else {
    header("Location:index.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?=BASE_PATH?>./public/css/style.css" />
    <link rel="stylesheet/less" type="text/css" href="<?=BASE_PATH?>./public/css/sources/styles.less" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Alkalami&family=Roboto&display=swap" rel="stylesheet">
    <script rel="preload" as="script" crossorigin="anonymous" src="https://cdnjs.cloudflare.com/ajax/libs/less.js/4.1.3/less.min.js"></script>
    <title>Courses Management</title>
</head>
<body>
    
    <div class="wrap wrap-fluid exercise">
        <?php include  __DIR__."/header.php" ?>
        <div class="wrap__inner exercise__create-comment">
            <div class="wrap__title">
                <h1>Courses</h1>
            </div>
            <div class="wrap__content">
                <section class="mb-4 subject__register">
                    <h2 class="h1-responsive font-weight-bold text-center my-4">Comments</h2>
                    <p class="text-center w-responsive mx-auto mb-5">Do you have any questions? Please do not hesitate to contact us directly. Our team will come back to you within a matter of hours to help you.</p>
                    <div class="row">
                        <div class="col-md-12 mb-md-0 mb-5">
                            <form id="comment-form" name="comment-form" action="" method="POST">
                                <?php if(isset($message)){
                                    echo '<div class="alert alert-success" role="alert">'.$message.'</div>';
                                    } else if(isset($error)){
                                        echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
                                    }
                                ?>
                                <div class="row comment__content">
                                    <div class="col-md-12">
                                        <div class="md-form mb-0">
                                            <label for="name" class="font-weight-bold">Your Comment</label>
                                            <input type="hidden" id="exercise_id" name="exercise_id" class="form-control" value ="<?= $user->getCurrentParams('exercise_id') ?>">
                                           <textarea type="text" id="comment-content" name="content" rows="2" class="form-control md-textarea" required="required"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center text-md-left">
                                    <input type="submit" class="btn btn-primary" name="save" value="save"/> 
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <?php include __DIR__."/footer.php" ?>
</body>
</html>
