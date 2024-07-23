<?php
header("Content-type:text/plain");
header("Access-Control-Allow-Origin:*");
?>
<html>
<table border=1>
    <tr>
        <th>Name</th>
        <th>Matric No.</th>
        <th>Total Mark</th>
    </tr>
    <?php
    $students = json_decode($_REQUEST["data"]);
    //var_dump($students);

    foreach ($students as $student) {
        $total_mark = $student->{"marks"}->{"cw"} +
            $student->{"marks"}->{"fe"};
    ?>
        <tr>
            <td><?php echo $student->{"name"}; ?></td>
            <td><?php echo $student->{"matric_no"}; ?></td>
            <td><?php echo $total_mark; ?></td>
        <tr>
    <?php
    } ?>
</table>

</html>