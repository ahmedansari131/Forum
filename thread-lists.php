<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>iDiscuss - Coding Forums</title>
</head>

<body>

    <?php include 'partials/_header.php';?>
    <?php include 'partials/_dbconnect.php';?>

    <?php
    $id = $_GET['catid'];
    $sql = "SELECT * FROM `categories` WHERE category_id='$id'";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)){
        $catname = $row['category_name'];
        $catdesc = $row['category_description'];
    }
    ?>

    <!-- Code for inserting the threads to the DB -->
    <?php 
    $showAlert = false;
    $id = $_GET['catid'];
    $method = $_SERVER['REQUEST_METHOD'];
    if($method == 'POST') {
        // Insert thread into DB
        $th_title = $_POST['title'];
        $th_desc = $_POST['desc'];
        $sno = $_POST['sno'];
        $sql = "INSERT INTO `threads` (`thread_id`, `thread_title`, `thread_desc`, `thread_cat_id`, `thread_user_id`, `timestamp`) VALUES (NULL, '$th_title', '$th_desc', '$id', '$sno', current_timestamp())";
        $result = mysqli_query($conn, $sql);
        $showAlert = true;

        if($showAlert) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Succes!</strong> Your thread has been added. Please wait for the community to response.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        }
    }

    
    ?>


    <!-- Category container starts here -->
    <div class="container my-3">
        <div class="jumbotron">
            <h1 class="display-4">Welcome to <?php echo $catname;?> Forums</h1>
            <p class="lead"><?php echo $catdesc; ?></p>
            <hr class="my-4">
            <h3>Here are the some rules for this forum</h3>
            <p>This is the peer to peer forum for sharing knowledge with each other.<br>No Spam / Advertising /
                Self-promote in the forums.<br>
                Do not post copyright-infringing material.<br>
                Do not post “offensive” posts, links or images.<br>
                Do not cross post questions.<br>
                Remain respectful of other members at all times.<br>
            </p>
        </div>
    </div>


    <!-- Thread form starts here -->
    <?php
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
   echo '<div class="container">
        <h1 class="pt-3">Start a Discussion</h1>
        <form class="my-4" action="' . $_SERVER["REQUEST_URI"] . '" method="POST">
            <div class="form-group">
                <label for="exampleInputEmail1">Problem Title</label>
                <input type="text" class="form-control" id="title" name="title" aria-describedby="title"
                    placeholder="Enter title">
                <small id="small" class="form-text text-muted">Keep your title as short and crisp as
                    possible.</small>
            </div>
            <div class="form-group">
                <label for="exampleFormControlTextarea1">Elaborate your problem</label>
                <textarea class="form-control" id="desc" name="desc" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-success ">Submit</button>
            <input type="hidden" name="sno" value="' . $_SESSION['sno'] . '">
        </form>
    </div>';
    } 
    else {
        echo '<div class="container">
        <h1 class="pt-3">Start a Discussion</h1>
                    <p class="lead" style="opacity:.7;"><b>You are not logged in. <br> Please login to proceed with the discussions.</b></p>
              </div>';
    }

    ?>


    <div class="container">
        <h1 class="pt-3">Browse Questions</h1>

        <?php
            $id = $_GET['catid'];   
            $sql = "SELECT * FROM `threads` WHERE thread_cat_id=$id";
            $result = mysqli_query($conn, $sql);
            $noResult = true;
            while($row = mysqli_fetch_assoc($result)){
                $noResult = false;
                $id = $row['thread_id'];
                $title = $row['thread_title'];
                $desc = $row['thread_desc'];
                $time = $row['timestamp'];
                $thread_user_id = $row['thread_user_id'];
                $sql2 = "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
                $result2 = mysqli_query($conn, $sql2);
                $row2 = mysqli_fetch_assoc($result2);
                $user = $row2['user_email'];
                

                echo '
                    <div class="media my-4">
                        <img src="img/userdefault.png" width="25px" class="mr-3" alt="user-image">
                        <div class="media-body mb-4">
                            <h5 class="mt-0">  <a class="text-dark" href="thread.php?threadid=' . $id . '">'
                           . $title .'</a> </h5>'.
                            $desc .'
                        </div> <p class="my-0 pb-1" style="font-size:1rem; opacity:.9;"> Asked by  <span style="opacity: 0.7;">' . $user . ' at ' . $time . '</span></p>
                    </div>';
            }
            
            // Code for writing default message if no questions are there
                if($noResult) {
                    echo '<div class="jumbotron">
                            <p class="lead" style="font-size:2rem;">No threads found for this category</p>
                            <hr class="my-4">
                            <p style="opacity:0.5;">Be the first person to ask the question.
                            </p
                        </div>';
                }
        ?>



    </div>
    <?php include 'partials/_footer.php'; ?>




    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>