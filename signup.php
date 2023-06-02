<?php
    session_start();
    include('pagetitles.php');
    $page_title = PAN_SIGNUP_PAGE;    
    include('header.php');
    include('nav.php');

    $show_sign_up_form = true;

    if (isset($_POST['signup_submission']))
    {
        require_once('dbconnection.php');

        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) 
            or trigger_error("error connecting to DB", E_USER_ERROR);
        
        // get username & pass (escape)
        $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
        $password = mysqli_real_escape_string($dbc, trim($_POST['password']));       
        
        if (!empty($username) && !empty($password))
        {    
            // query to see if the username is already in DB
            $query = "SELECT * FROM user WHERE username = '$username'";            

            $results = mysqli_query($dbc, $query)
                or trigger_error("Problem with querying the DB");

            $num = mysqli_num_rows($results);            

            if ($num == 0) // username not found, create one
            {
                $salted_hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $query = "INSERT INTO user (username, password) " 
                    . "VALUES ('$username', '$salted_hashed_password')";

                mysqli_query($dbc, $query) 
                    or trigger_error('Problem inserting values into the DB.');
                
                // Direct to the login page...
                echo "<p class='text-success'>Your new account for user <b>$username</b> has been successfully created.</p>"
                    . "<p>You are now ready to <a href='login.php'>log in</a></p>";
                
                $show_sign_up_form = false;
            }
            else {
                echo "<p class='text-danger'>An account already exists for this username: "
                . "<span class='font-weight-bold'> ($username)</span></p>"
                . "<p>Please try a different user name.</p>";
            }
            
            /*


                if (mysqli_fetch_column($results) >= 1) 
            {
                echo "<p class='text-danger'>An account already exists for this username: "
                    . "<span class='font-weight-bold'> ($username)</span></p>"
                    . "<p>Please try a different user name.</p>";
            }
            elseif (mysqli_fetch_row($results) == 0) 
            {
                $salted_hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $query = "INSERT INTO user (username, password) " 
                    . "VALUES ('$username', '$salted_hashed_password')";

                mysqli_query($dbc, $query) 
                    or trigger_error('Problem inserting values into the DB.');
                
                // Direct to the login page...
                echo "<p class='text-success'>Your new account for user <b>$username</b> has been successfully created.</p>"
                    . "<p>You are now ready to <a href='login.php'>log in</a></p>";
                
                $show_sign_up_form = false;
            }
            else {
                echo "<p class='text-danger'>There has been an error, please try again with a different user name.</p>";
            }

            /*
            if (mysqli_num_rows($results) >= 1) // account found, no duplicate usernames allowed
            {
                echo "<p class='text-danger'>An account already exists for this username: "
                    . "<span class='font-weight-bold'> ($username)</span></p>"
                    . "<p>Please try a different user name.</p>";
            }

            if (mysqli_num_rows($results) == 0 ) // account not found, create one and insert into DB
            {
                $salted_hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $query = "INSERT INTO user (username, password) " 
                    . "VALUES ('$username', '$salted_hashed_password')";

                mysqli_query($dbc, $query) 
                    or trigger_error('Problem inserting values into the DB.');                                
                
                // Direct to the login page...
                echo "<p class='text-success'>Your new account for user <b>$username</b> has been successfully created.</p>"
                    . "<p>You are now ready to <a href='login.php'>log in</a></p>";
                
                $show_sign_up_form = false;               
            }
            else // account already exists
            {
                echo "<p class='text-danger'>There has been an error, please try again with a different user name.</p>";
            }
            */

        }
        else 
        {
            echo '<p class="text-danger">All fields are required</p>';
        }
    }    
    if ($show_sign_up_form):        
    ?>
    <!-- Form -->    
    <h2 class="text-primary text-center p-2">Register for the Solo Steel Pan Database!</h2>
    <form class="needs-validation" novalidate method="POST"
        action="<?= $_SERVER['PHP_SELF']?>">    
        <div class="form-group row">
            <label for="username" class="col-sm-2 col-form-label-lg">User Name</label>
            <div class="col-sm-4">
                <input type="text" class="form-control"
                    id="username" name="username"
                    placeholder="Enter a user name" required>
                <div class="invalid-feedback">
                    Please provide a valid user name.
                </div>    
            </div>
        </div>
        <div class="form-group row">
            <label for="password" class="col-sm-2 col-form-label-lg">Password</label>
            <div class="col-sm-4">
                <input type="password" class="form-control"
                    id="password" name="password"
                    placeholder="Enter a password" required>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input"
                        id="show_password_check"
                        onclick="togglePassword()">
                        <label class="form-check-label"
                        for="show_password_check">Show Password</label>
                    </div>
                    <div class="invalid-feedback">
                        Please provide a valid password.
                    </div>    
            </div>
        </div>
        <button class="btn btn-primary" type="submit" name="signup_submission">Sign Up</button>
    </form>
    <?php
        endif;

    include('footer.php');
?>

<!-- JS for form -->
<script>
    // JavaScript for disabling form submissions if there are invalid fields
    (function () {
        'use strict';
        window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                  if (form.checkValidity() === false) {
                      event.preventDefault();
                      event.stopPropagation();
                  }
                  form.classList.add('was-validated');
                }, false);
            });
          }, false);
      })();

    function togglePassword() {
        var password_entry = document.getElementById("password");
        if (password_entry.type === "password") {
            password_entry.type = "text";
        } else {
            password_entry.type = "password";
        }
    }
</script>