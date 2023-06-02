<?php
    session_start();
    require_once('pagetitles.php');
    $page_title = "Solo Steel Pan Repertoire Database";
    require_once('dbconnection.php');
    require_once('index_header.php');
    require_once('nav.php');    
    ?>    
    <?php

    // logged in with Admin 
    // try without 'user_id', but just 'admin'

    if (isset($_SESSION['admin']) && (isset($_SESSION['user_id'])))
    {
        $id = $_SESSION['user_id'];
        $admin = $_SESSION['admin'];

        ?>
            <h2>Welcome <span class="text-success"><?= $_SESSION['user_name']?></span>!</h2>
        <?php        
           
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or trigger_error('Error connecting to MySQL server', E_USER_ERROR);                       
            
            $query = "SELECT c.id, c.composer, c.title, c.date, c.duration, i.instrument FROM composition as c JOIN instruments as i ON instruments_id=i.id ORDER BY date DESC;";

            $results = mysqli_query($dbc, $query)
                or trigger_error('Error querying database.', E_USER_ERROR);

            $data = mysqli_num_rows($results);

            if ($data < 1) 
            {
            ?>
                <h4>No Pan Music Posted Yet</h4>                
            <?php
            } 
            else // at least 1 log
            {                
            ?>

            <h2>Solo Pan Compositions</h2>
                <table id="adminTable" class="display" style="width: 95%">
                    <thead>
                        <th>Title</th>
                        <th>Composer</th>
                        <th>Date</th>
                        <th>Duration (min)</th>
                        <th>Instrument</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
            <?php // get results
            }
            while ($row = mysqli_fetch_array($results))
            {
            ?>                    
                        <tr>
                            <td><a href='workdetails.php?id=<?=$row['id']?>'> <?= $row['title']?></a></td>                        
                            <td><?= $row['composer'] ?></td>
                            <td><?= $row['date'] ?></td>
                            <td><?= $row['duration'] ?></td>
                            <td><?= $row['instrument'] ?></td>
                            <td>
                                <a class="edit" title="Edit" data-toggle="tooltip" href="editwork.php?id_to_edit=<?=$row['id'] ?>"><i class="bi bi-pencil"></i></a>
                                <a class="add" title="Add" data-toggle="tooltip" href="enterComposition.php"><i class="bi bi-plus-circle" style="color: green;"></i></a>
                                <a class="delete" title="Delete" data-toggle="tooltip" href="deleteComposition.php?id_to_delete=<?=$row['id']?>"><i class="bi bi-trash3" style="color: red;"></i></a>
                            </td>
                        </tr>                       
              
            <?php
            }
            ?>
                </tbody>   
                </table>              

        <?php
            mysqli_close($dbc);
    }

    // not logged in?
    elseif (isset($_SESSION['user_id']))
    
    {
        $id = $_SESSION['user_id'];

        ?>
            <h2>Welcome <span class="text-success"><?= $_SESSION['user_name']?></span>!</h2>            
            <hr>
        <?php
            
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or trigger_error('Error connecting to MySQL server', E_USER_ERROR);                       
            
            $query = "SELECT c.id, c.composer, c.title, c.date, c.duration, i.instrument FROM composition as c JOIN instruments as i ON instruments_id=i.id ORDER BY date DESC;";

            $results = mysqli_query($dbc, $query)
                or trigger_error('Error querying database.', E_USER_ERROR);

            $data = mysqli_num_rows($results);

            if ($data < 1) 
            {
            ?>
                <h4>No Pan Music Posted Yet</h4>                
                
            <?php
            } 
            else // at least 1 log
            {
            ?>

            <h2>Solo Pan Compositions</h2>
                <table id="userTable" class="display" style="width: 95%">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Composer</th>
                            <th>Date</th>
                            <th>Duration (min)</th>
                            <th>Instrument</th>
                        </tr>                        
                    </thead>
                    <tbody>
            <?php // get results
            }
            while ($row = mysqli_fetch_array($results))
            {
            ?>                    
                        <tr>
                            <td><a href='workdetails.php?id=<?=$row['id']?>'> <?= $row['title']?></a></td>                        
                            <td><?= $row['composer'] ?></td>
                            <td><?= $row['date'] ?></td>
                            <td><?= $row['duration'] ?></td>
                            <td><?= $row['instrument'] ?></td>                            
                        </tr> 
              
            <?php
            }
            ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Title</th>
                            <th>Composer</th>
                            <th>Date</th>
                            <th>Duration (min)</th>
                            <th>Instrument</th>
                        </tr>
                    </tfoot>
                </table>
        
            <?php
            mysqli_close($dbc);            
    }
        
    else  // not logged in
    {
    
    ?>
        <div class="col-8 mx-auto">
            <h2 class="text-primary text-center">Welcome to the Steel Pan Solo Database!</h2>
            <p class="text-center">Please <a href="signup.php">register</a> for an account or <a href="login.php">sign in</a></p>
        </div>
    <?php
    }
    
    ?>
    
<?php
    require_once('footer.php');
?>
