<div class="row">
    <div class="col">
        <hr>
        <?php
            // NavMenu
            if (isset($_SESSION['user_name'])) 
            {
                $userName = $_SESSION['user_name'];
        ?>
            <ul class="nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="enterComposition.php">Add New Composition</a></li>
                <li class="nav-item"><a class="nav-link" href="viewprofile.php">View Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="editprofile.php">Edit Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Log out (<?= $userName?>) </a></li>                
            </ul>
        <?php
            }
            else 
            {
        ?>
            <ul class="nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="login.php">Log In</a></li>
                <li class="nav-item"><a class="nav-link" href="signup.php">Sign Up</a></li>                
            </ul>
        <?php
            }
        ?>
    </div>    
</div>
<hr>