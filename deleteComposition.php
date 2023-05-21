<?php
    session_start();

    require_once('pagetitles.php');
    $page_title = PAN_REMOVE_WORK;
    require('header.php');
    require_once('nav.php'); 
    require_once('dbconnection.php');

    if(isset($_SESSION['user_id']) && (isset($_SESSION['admin']))) { // user is logged in
        
        $id = $_SESSION['user_id'];
        
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                    or trigger_error('Error connecting to MySQL server for DB_NAME.',
                            E_USER_ERROR);        
        
        if (isset($_POST['delete_work_submit']) && (isset($_POST['id']))):
            $id = $_POST['id'];

            // here is the query to delete
            $query = "DELETE FROM composition WHERE id = $id";

            $result = mysqli_query($dbc, $query) 
                or trigger_error('Error querying db steelpan DB', E_USER_ERROR);

            header("Location: index.php");
        
        elseif (isset($_GET['do_not_delete_work_submit'])):

            header("Location: index.php");

        elseif (isset($_GET['id_to_delete'])):        
            $id = $_GET['id_to_delete'];

            // query the listing based on id_to_delete
            // show what is going to be deleted and offer an option 
            $query = "SELECT * FROM `composition` as c 
            JOIN instruments as i ON c.instruments_id = i.id 
            JOIN difficulty as d ON c.difficulty_id = d.id 
            WHERE c.id = ?";
            
            $stmt = mysqli_prepare($dbc, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            
            // Using prepared statments for query
            $result = mysqli_stmt_get_result($stmt)
                or trigger_error('Error querying database SteePanDB', E_USER_ERROR);

            if (mysqli_num_rows($result) == 1) 
            {
                $row = mysqli_fetch_assoc($result);
                // table showing all from the entry
            ?>
            <h2><?=$row['title']?> <span class="font-italic"><?=$row['date']?></span></h2>
            <div class="row">
                <div class="col-4">
                    <table class="table table-bordered">
                        <tr>
                            <th scope="row">Composer</th>
                            <td><?= $row['composer']?></td>
                        </tr>
                        <tr>
                            <th scope="row">Instrument</th>
                            <td><?= $row['instrument']?> <span class="font-italic"><?=$row['ins_range']?></span></td>
                        </tr>
                        <tr>
                            <th scope="row">Duration</th>
                            <td><?= $row['duration']?> minutes</td>
                        </tr>
                        <tr>
                            <th scope="row">Publisher</th>
                            <td><?= $row['publisher']?> </td>
                        </tr>
                        <tr>
                            <th scope="row">Difficulty</th>
                            <td><?= $row['difficulty']?> </td>
                        </tr>
                    </table>                    
                </div>
                <div class="col-8">
                    <table class="table table-bordered">
                        <tr>
                            <th scope="row">Annotation</th>
                            <td><?= $row['annotation']?></td>
                        </tr>
                        <tr>
                            <th scope="row">Composer Note</th>
                            <td><?= $row['composerNote']?></td>
                        </tr>
                    </table>
                </div>                
            </div>
            <br>
            <!-- form -->
            <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
                <div class="form-group row">
                    <div class="col-sm-2">
                        <button class="btn btn-danger" type="submit"
                            name="delete_work_submit">Delete Work Entry</button>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-success" type="submit"
                            name="do_not_delete_work_submit">Don't Delete</button>
                    </div>
                    <input type="hidden" name="id" value="<?= $id ?>;">
                </div>
            </form>
            <?php             
            }
            else {
                ?>
                <h3>No Composition Details</h3>
                <?php
            }
        else:
            header("Location: index.php");
            exit();
        endif;
        ?>

        
  
    <?php
    }
    else // session id
    {
        echo "You must be logged with admin privileges to view this. ";
        echo '<a href="login.php">Log In</a>';
    }
    ?>

    