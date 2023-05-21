<?php
    session_start();
    require_once('pagetitles.php');    
    $page_title = PAN_VIEW_PROFILE;
    require_once('header.php');
    require('nav.php');    

    

    // logged in?
    if (isset($_SESSION['user_id'])) 
    {
        // personal data -- exercise data
        require_once('dbconnection.php');
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or trigger_error('Error connecting to MySQL DB', E_USER_ERROR);
        
        $id = $_SESSION['user_id'];        

        $query = "SELECT * FROM user WHERE id = '$id'";

        $results = mysqli_query($dbc, $query)
            or trigger_error('Error querying the DB', E_USER_ERROR);

        $row = mysqli_fetch_array($results);
        
        ?>
        <div class="row">
            <div id="profile-info" class="col-4">
                <h2><?= $_SESSION['user_name'] ?> Information</h2>
                <table class="table table-striped">
                    <tr><th>First Name</th><td><?= $row['first_name'] ?></td></tr>
                    <tr><th>Last Name</th><td><?= $row['last_name'] ?></td></tr>                                        
                </table>                
                <?php
                if (isset($_SESSION['admin']))
                {
                    ?>
                    <p class="text-success">You are marked as an admin</p>
                    <?php
                }
                ?>
                <p>Would you like to <a href="editprofile.php">edit your profile</a>?</p>
            </div>
        </div>
    <?php
    }
    else 
    {
        echo "You must be logged in to view this.";
        echo '<a href="login.php">Log in</a>';
    }
    require_once('footer.php');