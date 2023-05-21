<?php
    session_start();
    require_once('pagetitles.php');
    $page_title = PAN_ENTER_WORK;
    require_once('header.php');
    require('nav.php');
    require_once('dbconnection.php');
    
    if(isset($_SESSION['user_id']) && (isset($_SESSION['admin'])))
    {
        $id = $_SESSION['user_id'];   

        if(isset($_POST['submit'], $_POST['title'], $_POST['composer'],
            $_POST['instrument'], $_POST['range'], $_POST['difficulty'],
            $_POST['publisher']))
        {
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                    or trigger_error('Error connecting to MySQL server');
            
            // with escapes
            $publish = mysqli_escape_string($dbc, $_POST['publisher']);
            $composer = mysqli_escape_string($dbc, $_POST['composer']);
            $title = mysqli_escape_string($dbc, $_POST['title']);
            $composer_note = mysqli_escape_string($dbc, $_POST['composerNote']);
            $annotation = mysqli_escape_string($dbc, $_POST['annotation']);
            $range = mysqli_escape_string($dbc, $_POST['range']);

            $site = $_POST['url'];
            $diff = $_POST['difficulty'];            
            $instrument = $_POST['instrument'];                    
            $date = $_POST['date'];
            $duration = $_POST['duration'];
            
            
            // $publish = mysqli_real_escape_string($dbc, $_POST['publisher']);
            // $composer = mysqli_real_escape_string($dbc, $_POST['composer']);
            // $title = mysqli_real_escape_string($dbc, $_POST['title']);
            // $composer_note = mysqli_real_escape_string($dbc, $_POST['composerNote']);
            // $annotation = mysqli_real_escape_string($dbc, $_POST['annotation']);
            // $range = mysqli_real_escape_string($dbc, $_POST['range']);
            // $site = mysqli_real_escape_string($dbc, $_POST['url']);           

            ?>
            <div class="row mx-auto">
                <div class="col-9">
                    <h2 class="text-primary">The following details have been added:</h2>                    
                    <table class="table">
                        <tr>                        
                            <th>Title</th> <!-- add composition date next to title -->
                            <th>Composer</th> 
                            <th>Instrument</th> <!-- add range next to the instrument, no need for an extra column -->                            
                            <th>Duration</th>
                            <th>Publisher</th>
                            <th>Difficulty</th>
                            <th>Composer/Publisher Note</th>
                            <th>Annotation</th>
                            <th>Links</th>
                        </tr>
                        <tr>
                            <td><?= $title?> (<?= $date?>)</td>
                            <td><?= $composer?></td>
                            <td><?= $instrument?> <?= $range?></td>
                            <td><?= $duration?></td>
                            <td><?= $publish?></td>
                            <td><?= $diff?></td>
                            <td><?= $composer_note?></td>
                            <td><?= $annotation?></td>
                            <td><?= $site?></td>
                        </tr>                    
                    </table>
                </div>                
            </div>
            <?php                                                   
            
            $query = "INSERT INTO composition "
                    . "(difficulty_id, publisher, instruments_id, title, date, duration, composerNote, annotation, composer, ins_range, url) "
                    . "VALUES ('$diff', '$publish', '$instrument', '$title', '$date', '$duration', '$composer_note', '$annotation', '$composer', '$range', '$site')";
            
            $result = mysqli_query($dbc, $query) 
                    or trigger_error("Could not add exercise info into DB.", E_USER_ERROR);

            mysqli_close($dbc);

        } 
        else 
        {
        ?>
            <h2>Add a new steel pan work to the database!</h2>
            <div class="col-9">
                <hr>
                <form enctype="multipart/form-data" method="POST" class="needs-validation" novalidate
                    action="<?= $_SERVER['PHP_SELF']?>">
                    <!-- $title = $_POST['title']; -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label-md m-2 p-2">Title:</label>
                        <div class="col-sm-6">
                            <input type="text" name="title" class="form-control" required>
                            <div class="invalid-feedback">
                                Please provide a valid title of the work to be added.
                            </div>
                        </div>
                    </div> 
                    <!-- $composer = $_POST['composer']; -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label-md m-2 p-2">Composer:</label>
                        <div class="col-sm-6">
                            <input type="text" name="composer" class="form-control" required>
                            <div class="invalid-feedback">
                                Please provide a valid composer of the work to be added.
                            </div>
                        </div>
                    </div> 
                    <!-- $instrument = $_POST['instrument']; -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label-md m-2 p-2">Instrument:</label>
                        <div class="col-sm-6">
                        <select name="instrument" id="instrument" class="form-select" required>
                                <option value="" disabled selected>Select...</option>
                                <option value="1">Tenor</option>
                                <option value="2">Double Tenor</option>
                                <option value="3">Double Seconds</option>
                                <option value="4">Cello</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select an instrument the piece features.
                            </div>
                        </div>
                    </div> 
                    <!-- $range = $_POST['range']; -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label-md m-2 p-2">Range:</label>
                        <div class="col-sm-6">
                            <input type="text" name="range" class="form-control" required>
                            <div class="invalid-feedback">
                                Please provide a valid range for the instrument of the work to be added.
                            </div>
                        </div>
                    </div> 
                    <!-- $duration = $_POST['duration']; -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label-md m-2 p-2">Duration:</label>
                        <div class="col-sm-6">
                            <input type="number" name="duration" min=1 class="form-control" >
                            <!-- <div class="invalid-feedback">
                                Please provide a valid number of work duration.
                            </div> -->
                        </div>
                    </div>
                    <!-- $diff = $_POST['difficulty']; -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label-md m-2 p-2">Difficulty:</label>
                        <div class="col-sm-6">
                        <select name="difficulty" id="difficulty" class="form-select" required>
                                <option value="" disabled selected>Select...</option>
                                <option value="1">I</option>
                                <option value="2">II</option>
                                <option value="3">III</option>
                                <option value="4">IV</option>
                                <option value="5">V</option>
                                <option value="6">VI</option>
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
                            <input type="text" name="date" maxlength="4" pattern="\d{4}" class="form-control" >
                            <div class="invalid-feedback">
                                Please provide a valid year of the composition. 
                            </div>
                        </div>
                    </div>   
                    <!-- $publish = $_POST['publisher']; -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label-md m-2 p-2">Publisher:</label>
                        <div class="col-sm-6">
                            <input type="text" name="publisher" class="form-control" required>
                            <div class="invalid-feedback">
                                Please provide a valid name of the publisher of the work.
                            </div>
                        </div>
                    </div>   
                    <!-- $composer_note = $_POST['composerNote']; -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label-md m-2 p-2">Composer / Publisher Note:</label>
                        <div class="col-sm-9">
                            <textarea name="composerNote" id="composerNote"></textarea>    
                            <!-- <input type="text" name="composerNote" class="form-control" > -->
                            <!-- <div class="invalid-feedback">
                                Please provide a valid note from the composer or publisher. 
                            </div> -->
                        </div>
                    </div>   
                    <!-- $annotation = $_POST['annotation']; -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label-md m-2 p-2">Annotation:</label>
                        <div class="col-sm-9">
                            <textarea name="annotation" id="annotation"></textarea>
                            <!-- <input type="text" name="annotation" class="form-control" > -->
                            <!-- <div class="invalid-feedback">
                                Please provide a valid annotation of the work.
                            </div> -->
                        </div>
                    </div>
                    <!-- $site = $_POST['url']; -->
                    <div class="form-group row">                        
                        <label class="col-sm-3 col-form-label-md m-2 p-2">Link:</label>
                        <div class="col-sm-6">
                            <input type="url" name="url" class="form-control" >
                            <!-- <div class="invalid-feedback">
                                Please provide a valid url.
                            </div> -->
                        </div>
                    </div>
                    <button class="btn btn-primary m-2" type="submit" name="submit">Submit New Work!</button> 
                                  
                </form>    
            </div>            
        <?php
        }
    } else {
        echo "You need to log in with admin privileges.<br />";
        echo '<a href="login.php">Log In</a>';
    }
    require_once('footer.php');
    ?>    
    
