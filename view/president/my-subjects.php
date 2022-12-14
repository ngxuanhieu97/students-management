<?php
session_start();
include_once dirname(__DIR__,2)."./app/Services/Services.php";

$president = new Services();
if ($president->role('president')) {
    $data = $president->list();
} else {
    $data = [];
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
    <title>Student Management</title>
</head>

<body>
    <div class="wrap wrap-fluid president">
        <?php include dirname(__DIR__)."/header.php" ?>
        <div class="wrap__inner president__subjects">
            <div class="wrap__title">
                <h1>My Subject List</h1>
            </div>
            <div class="wrap__content">
                <div class="subject__list">
                    <div class="col-12">
                        <table class="table table-bordered table-striped" style="margin-top:20px;">
                            <thead>
                                <th>ID</th>
                                <th>Course</th>
                                <th>Subject</th>
                                <th>Teacher</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($data)) {
                                    foreach ($data as $row) {
                                ?>
                                        <tr>
                                            <td><?= $row['id'] ?></td>
                                            <td><?= $row['name'] ?></td>
                                            <td><?= $row['title'] ?></td>
                                            <td><?= $user->getTeacherName($row['teacher_id']) ?></td>
                                            <td>
                                                <a href='edit-subject.php?subject_id=<?= $row['subject_id'] ?>&course_id=<?= $row['course_id'] ?>' class='btn btn-success btn-sm'>Change Teacher</a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    <?php include dirname(__DIR__)."/footer.php" ?>
</body>

</html>
