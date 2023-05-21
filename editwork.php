<?php
    session_start();

    require_once('pagetitles.php');
    $page_title = PAN_EDIT_WORK;
    require('header.php');
    require_once('nav.php'); 
    require_once('dbconnection.php');

    if(isset($_SESSION['user_id']) && (isset($_SESSION['admin']))) { // user is logged in
        
        $id = $_SESSION['user_id'];
        
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                    or trigger_error('Error connecting to MySQL server for DB_NAME.',
                            E_USER_ERROR);
        
        if (isset($_GET['id_to_edit']))
        {
            $id_to_edit = $_GET['id_to_edit'];

            $query = "SELECT * FROM `composition` as c 
                JOIN instruments as i ON c.instruments_id = i.id 
                JOIN difficulty as d ON c.difficulty_id = d.id 
                WHERE c.id = ?";

            $stmt = mysqli_prepare($dbc, $query);
            mysqli_stmt_bind_param($stmt, "i", $id_to_edit);
            mysqli_stmt_execute($stmt);
            
            // Using prepared statments for query
            $result = mysqli_stmt_get_result($stmt)
                or trigger_error('Error querying database SteePanDB', E_USER_ERROR);

            if (mysqli_num_rows($result) == 1) 
            {
                $row = mysqli_fetch_assoc($result);
                $pan_id = $row['id'];
                $pan_diff = $row['difficulty'];
                $pan_publish = $row['publisher'];
                $pan_instrument = $row['instrument'];
                $pan_composer = $row['composer'];
                $pan_title = $row['title'];
                $pan_date = $row['date'];
                $pan_duration = $row['duration'];
                $pan_composer_note = $row['composerNote'];
                $pan_annotation = $row['annotation'];
                $pan_range = $row['ins_range'];
                $pan_site = $row['url'];   

            }
        }
        elseif (isset($_POST['do_not_edit_work_submission'])) 
        {            
            header("Location: index.php"); 
        }
        // elseif (isset($_POST['delete_work']))
        // {
        //     header(`Location: deleteComposition.php?id_to_delete=$pan_id`);
        // }

        elseif (isset($_POST['edit_work_submission'],$_POST['pan_difficulty'], $_POST['pan_publisher'], 
            $_POST['pan_instrument'], $_POST['pan_composer'], $_POST['pan_title'], $_POST['pan_date'], 
            $_POST['pan_duration'], $_POST['pan_composer_note'], $_POST['pan_annotation'], 
            $_POST['pan_range'], $_POST['pan_url'], $_POST['id_to_update'] ))
        {
            $pan_diff = $_POST['pan_difficulty'];
            $pan_publish = $_POST['pan_publisher'];
            $pan_instrument = $_POST['pan_instrument'];
            $pan_composer = $_POST['pan_composer'];
            $pan_title = $_POST['pan_title'];
            $pan_date = $_POST['pan_date'];
            $pan_duration = $_POST['pan_duration'];
            $pan_composer_note = $_POST['pan_composer_note'];
            $pan_annotation = $_POST['pan_annotation'];
            $pan_range = $_POST['pan_range'];
            $pan_site = $_POST['pan_url'];
            $id_to_update = $_POST['id_to_update'];                       

            // udpate query $nav_link to the work with the $id_to_update

            $query = "UPDATE composition SET difficulty_id = $pan_diff, publisher = '$pan_publish', "
                . "instruments_id = $pan_instrument, title = '$pan_title', date = $pan_date, "
                . "duration = $pan_duration, composerNote = '$pan_composer_note', annotation = '$pan_annotation', "
                . "composer = '$pan_composer', ins_range = '$pan_range', url = '$pan_site' "
                . "WHERE id = $id_to_update";            
        
            mysqli_query($dbc, $query) or trigger_error(
                'Error querying db steelpanDB: failed to update composition table'
                . mysqli_error($dbc), E_USER_ERROR);
            
            $nav_link = 'workdetails.php?id=' . $id_to_update;
            header("Location: $nav_link");            

        }
        else 
        {
            header("Location: index.php");
        }
        ?>
        <!-- Form with filled in fields -->
        <h2>You are editing this steel pan work:</h2>
            <div class="col-9">
                <hr>
                <form enctype="multipart/form-data" method="POST" class="needs-validation" novalidate
                    action="<?= $_SERVER['PHP_SELF']?>">                   
                    <!-- $title = $_POST['title']; -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label-md m-2 p-2">Title:</label>
                        <div class="col-sm-6">
                            <input type="text" name="pan_title" class="form-control" 
                            value='<?= $pan_title?>' placeholder= "Title" required>
                            <div class="invalid-feedback">
                                Please provide a valid title of the work to be added.
                            </div>
                        </div>
                    </div> 
                    <!-- $composer = $_POST['composer']; -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label-md m-2 p-2">Composer:</label>
                        <div class="col-sm-6">
                            <input type="text" name="pan_composer" class="form-control" 
                            value='<?= $pan_composer?>' placeholder="Composer Name" required>
                            <div class="invalid-feedback">
                                Please provide a valid composer of the work to be added.
                            </div>
                        </div>
                    </div> 
                    <!-- $instrument = $_POST['instrument']; -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label-md m-2 p-2">Instrument:</label>                        
                        <div class="col-sm-6">
                        <select name="pan_instrument" id="instrument" class="form-select" required>
                                <option value="" disabled selected>Select...</option>
                                <option value="1" <?= $pan_instrument == 'Tenor' ? 'selected' : '' ?>>Tenor</option>
                                <option value="2" <?= $pan_instrument == 'Double Tenor' ? 'selected' : '' ?>>Double Tenor</option>
                                <option value="3" <?= $pan_instrument == 'Double Seconds' ? 'selected' : '' ?>>Double Seconds</option>
                                <option value="4" <?= $pan_instrument == 'Cello' ? 'selected' : '' ?>>Cello</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select a type instrument.
                            </div>
                        </div>
                    </div> 
                    <!-- $range = $_POST['range']; -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label-md m-2 p-2">Range:</label>
                        <div class="col-sm-6">
                            <input type="text" name="pan_range" class="form-control" 
                            value='<?= $pan_range?>' placeholder="i.e., C4 - E6" required>
                            <div class="invalid-feedback">
                                Please provide a valid range for the instrument of the work to be added.
                            </div>
                        </div>
                    </div> 
                    <!-- $duration = $_POST['duration']; -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label-md m-2 p-2">Duration:</label>
                        <div class="col-sm-6">
                            <input type="number" name="pan_duration" min=1 class="form-control" 
                            value = '<?= $pan_duration?>'>
                            <!-- <div class="invalid-feedback">
                                Please provide a valid number of work duration.
                            </div> -->
                        </div>
                    </div>
                    <!-- $diff = $_POST['difficulty']; -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label-md m-2 p-2">Difficulty:</label>
                        <div class="col-sm-6">
                        <select name="pan_difficulty" id="difficulty" class="form-select" required>
                                <option value="" disabled selected>Select...</option>
                                <option value="1" <?= $pan_diff == 'I' ? 'selected' : '' ?>>I</option>
                                <option value="2" <?= $pan_diff == 'II' ? 'selected' : '' ?>>II</option>
                                <option value="3" <?= $pan_diff == 'III' ? 'selected' : '' ?>>III</option>
                                <option value="4" <?= $pan_diff == 'IV' ? 'selected' : '' ?>>IV</option>
                                <option value="5" <?= $pan_diff == 'V' ? 'selected' : '' ?>>V</option>
                                <option value="6" <?= $pan_diff == 'VI' ? 'selected' : '' ?>>VI</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select a difficulty level for the work.
                            </div>
                        </div>
                    </div> 
                    <!-- $date = $_POST['date']; -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label-md m-2 p-2">Composition Year:</label>
                        <div class="col-sm-6">
                            <input type="text" name="pan_date" maxlength="4" pattern="\d{4}" 
                            class="form-control" value='<?= $pan_date?>'>
                            <!-- <div class="invalid-feedback">
                                Please provide a valid year of the composition. 
                            </div> -->
                        </div>
                    </div>   
                    <!-- $publish = $_POST['publisher']; -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label-md m-2 p-2">Publisher:</label>
                        <div class="col-sm-6">
                            <input type="text" name="pan_publisher" class="form-control" 
                            value='<?= $pan_publish?>' placeholder="Name of publisher" required>
                            <div class="invalid-feedback">
                                Please provide a valid name of the publisher of the work.
                            </div>
                        </div>
                    </div>   
                    <!-- $composer_note = $_POST['composerNote']; -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label-md m-2 p-2">Composer / Publisher Note:</label>
                        <div class="col-sm-9">
                            <textarea name="pan_composer_note" id="composerNote"><?= $pan_composer_note?></textarea>                        
                        </div>                        
                    </div>   
                    <!-- $annotation = $_POST['annotation']; -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label-md m-2 p-2">Annotation:</label>                        
                        <div class="col-sm-9">
                            <textarea name="pan_annotation" id="annotation"><?= $pan_annotation?></textarea>
                        </div>                        
                    </div>
                    <!-- $site = $_POST['url']; -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label-md m-2 p-2">Link:</label>
                        <div class="col-sm-9">
                            <input type="url" name="pan_url" class="form-control" 
                            value='<?= $pan_site?>'>                            
                        </div>
                    </div>
                    <button class="btn btn-primary m-2" type="submit" name="edit_work_submission">Update Work</button>                
                    <input type="hidden" name="id_to_update" value="<?= $id_to_edit ?>">
                    <button class="btn btn-dark m-2" type="submit" name="do_not_edit_work_submission">Cancel</button>
                    <input type="hidden" name="id_to_update" value="<?= $id_to_edit ?>">                    
                    <a type="submit" class="btn btn-danger m-2" href="deleteComposition.php?id_to_delete=<?= $id_to_edit ?>">Delete</a>
                    <input type="hidden" name="id_to_update" value="<?= $id_to_edit ?>">
                </form>
            </div>

    <?php
    }
    else // session id
    {
        echo "You must be logged with admin privileges to view this. ";
        echo '<a href="login.php">Log In</a>';
    }
    require_once('footer.php');
    ?>

    