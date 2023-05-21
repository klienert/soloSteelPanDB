<?php
    session_start();   
    $page_title = "Composition Details";
    require_once('header.php');
    require('nav.php');
    require_once('dbconnection.php');
    
    if(isset($_GET['id'])):
    
        $id = $_GET['id'];   

        // get details of work via ID
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or trigger_error('Error connecting to MySQL server for '
        . DB_NAME, E_USER_ERROR);
        
        $query = "SELECT * FROM `composition` as c JOIN instruments as i ON c.instruments_id = i.id WHERE c.id = $id;";

        $result = mysqli_query($dbc, $query)
            or trigger_error('Error querying database studentListing',
            E_USER_ERROR);

        if (mysqli_num_rows($result) == 1):
            $row = mysqli_fetch_assoc($result);            

        ?>
            <!-- display the row details -->
            <h1><?= $row['title']?> (<?= $row['date']?>)</h1>
            <div class="row">
                <div class="col-9">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th scope="row">Composer</th>
                                <td><?= $row['composer']?></td>
                            </tr>
                            <tr>
                                <th scope="row">Instrument</th>
                                <td><?= $row['instrument']?> <span class="font-italic">(<?= $row['ins_range']?>)</span></td>
                            </tr>
                            <tr>
                                <th scope="row">Duration</th>
                                <td><?= $row['duration']?></td>
                            </tr>
                            <tr>
                                <th scope="row">Publisher</th>
                                <td><?= $row['publisher']?></td>
                            </tr>
                            <tr>
                                <th scope="row">Difficulty</th>
                                <td><?= $row['difficulty_id']?></td>
                            </tr>
                            <tr>
                                <th scope="row">Composer Note</th>
                                <td><?= $row['composerNote']?></td>
                            </tr>
                            <tr>
                                <th scope="row">Annotation</th>
                                <td><?= $row['annotation']?></td>
                            </tr>
                            <tr>
                                <th scope="row">Link</th>
                                <td><a href="<?= $row['url']?>"><?= $row['url']?></a></td>
                            </tr>                        
                        </tbody>
                    </table>
                </div>
            </div>
            <p>Any edits to this can be made <a href="editwork.php?id_to_edit=<?=$id?>">here</a> (need to have admin permission)</p>
    
        <?php                
            else:
        ?>
            <h3>No Composition Details Found</h3>            
        <?php
            endif;
        else:
        ?>
            <h3>No Composition Found in the Database.</h3>
        <?php
            endif;
            require_once('footer.php');
        ?>
    </body>
    </html>
    
