<?php
$employees = json_decode(file_get_contents("data.json")); // 1 point
?>
<!-- 1 point pour l'HTML en général -->
<table border="1" cellpadding="5" cellspacing="0"> 
    <thead>
        <th>Nom</th>
        <th>Position</th>
        <th>Salaire</th>
    </thead>
    <tbody>
        <?php foreach($employees as $employee){ // 1 point
        ?>
        <tr>
            <td><?= $employee["nom"] ?></td>
            <td><?= $employee["positioj"] ?></td>
            <td><?= $employee["salaire"] ?></td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>