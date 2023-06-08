<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $con = mysqli_connect("localhost", "root", "");
    if(!$con){
        die('Could not connect: '.mysqli_error());
    }
    ////echo "Halo guys<br>";

/*      if (mysqli_query($con, "CREATE DATABASE my_db"))
    {
        echo "Database created";
    }
    else
    {
        echo "Error creating database: ". mysqli_error($con);
    }
  */
    mysqli_select_db($con, "my_db");

 /*    $sql = "CREATE TABLE Persons(
    FirstName varchar(15),
    LastName varchar(15),
    Age int
     )"; 

     $sql = "INSERT INTO Persons
     VALUES ('Taufiq', 'Kacak', 0165653191); "; 

    mysqli_query($con, $sql);
 */

    $result = mysqli_query($con, "SELECT * FROM persons");
    echo "<table border = '1'>
    <tr>
    <th>FirstName</th>
    <th>LastName</th>
    </tr>";
    while($row=mysqli_fetch_array($result))
    {
        echo "<tr>";
        echo "<td>".$row['FirstName']."</td>";
        echo "<td>".$row['LastName']."</td>";
        echo "</tr>";
    }
    echo "</table>";

 mysqli_close($con);
    ?>
    
</body>
</html>