<?php
    session_start();
    
    require_once('pagetitles.php');
    $page_title = PAN_EDIT_PROFILE;
    require('header.php');
    require_once('nav.php');
    // require_once('authorizeaccess.php');

    if(isset($_SESSION['user_id'])) { // user is logged in

        $id = $_SESSION['user_id'];        

        require_once('dbconnection.php');
        require_once('queryutils.php');
        require_once('imagefileutil.php');
        require_once('image_fileconstants.php');

        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or trigger_error('Error connecting to MySQL server', E_USER_ERROR);

        $query = "SELECT * FROM user WHERE id = $id";
        
        $result = mysqli_query($dbc, $query)
            or trigger_error('error querying DB', E_USER_ERROR);        

        if (mysqli_num_rows($result) == 1) 
        {
            $row = mysqli_fetch_assoc($result);
            
            $user_username = $row['username'];
            $user_password = $row['password'];
            $user_firstname = $row['first_name'];
            $user_lastname = $row['last_name'];
            
        }                    

        // if form is submitted, sanitize string entries and update
        if (isset($_POST['edit_profile_submission']))                 
        {                            
            $user_firstname = mysqli_real_escape_string($dbc, trim($_POST['user_first_name']));
            $user_lastname = mysqli_real_escape_string($dbc, trim($_POST['user_last_name']));            
                
                $query = "UPDATE user SET first_name = '$user_firstname',"
                    . "last_name = '$user_lastname' " 
                    . "WHERE id = $id";

                mysqli_query($dbc, $query) 
                    or trigger_error('Error updating DB.' . mysqli_error($dbc), E_USER_ERROR);                   
                   
                    
                $nav_link = 'viewprofile.php';
                header("Location: " . $nav_link);
        }
        else 
        {
            echo "<h5><p class='text-danger'>" . $file_error_message . "</p></h5>";
        }            
        // }
    }
    else 
    {
        echo "You must be logged in to view this. ";
        echo '<a href="login.php">Log In</a>';
    }
?>
<!-- FORM -->
<h2>Update Profile</h2>
    <div class="row">
        <div class="col">
        <form enctype="multipart/form-data"
            class="needs-validation" novalidate method="POST" 
            action="<?= $_SERVER['PHP_SELF'] ?>">
            <!-- user_username -->
            <div class="form-group row">
                <label for="user_username"
                    class="col-sm-3 col-form-label-lg">Username</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="user_username"
                    name="user_username" value= '<?= $user_username?>'
                    placeholder="User Name" disabled>
                    <div class="invalid-feedback">
                        Please provide a valid username.
                    </div>
                </div>
            </div>
            <!-- user_password -->
            <div class="form-group row">
            <label for="password" class="col-sm-3 col-form-label-lg">Password</label>
                <div class="col-sm-8">
                    <input type="password" class="form-control"
                        id="user_password" name="user_password"                        
                        placeholder="Your Password :)" disabled>                        
                        <div class="invalid-feedback">
                            Please provide a valid password.
                        </div>    
                </div>
            </div>
            <!-- user_first_name -->
            <div class="form-group row">
                <label for="user_first_name"
                    class="col-sm-3 col-form-label-lg">First Name</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="user_first_name"
                    name="user_first_name" value= '<?= $user_firstname?>'
                    placeholder="First Name" >
                    <div class="invalid-feedback">
                        Please provide a valid username.
                    </div>
                </div>
            </div>
            <!-- user_last_name -->
            <div class="form-group row">
                <label for="user_last_name"
                    class="col-sm-3 col-form-label-lg">Last Name</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="user_last_name"
                    name="user_last_name" value= '<?= $user_lastname?>'
                    placeholder="Last Name" >
                    <div class="invalid-feedback">
                        Please provide a valid username.
                    </div>
                </div>
            </div>            
            <!-- Button -->
            <button class="btn btn-primary" type="submit" name="edit_profile_submission">Update Profile</button>
                <input type="hidden" name="id" value="<?= $id ?>">                
        </form>
        </div>        
    </div>
<!-- Footer -->
<?php
    require_once('footer.php');
?>