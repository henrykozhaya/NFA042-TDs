<?php
// Fonction pour ajouter un employé au fichier data.csv
function addEmployee($name, $position, $salary) {
    $file = 'data.csv';
    $newLine = [$name, $position, $salary];
    
    // Ouvrir le fichier en mode écriture/ajout
    $csvFile = fopen($file, 'a');
    if($csvFile !== false) {
        // Ajouter une nouvelle ligne au fichier avec escape explicit
        fputcsv($csvFile, $newLine);
        fclose($csvFile);
    }
}

// Fonction pour afficher les employés dans un tableau HTML
function displayEmployees() {
    $file = 'data.csv';
    
    // Vérifier si le fichier existe
    if (!file_exists($file)) {
        die("Erreur : Le fichier data.csv n'existe pas.");
    }
    
    // Lire le fichier CSV
    $csvFile = fopen($file, 'r');
    
    // Afficher les données dans un tableau HTML
    $html = "";
    $html .= '<table border="1">';
    $html .= '<thead><tr><th>Nom</th><th>Position</th><th>Salaire</th></tr></thead>';
    $html .= '<tbody>';
    
    while (($row = fgetcsv($csvFile)) !== false) {
        // if (empty($row) || (count($row) === 1 && $row[0] === null)) {
        //     continue; // Ignore empty lines
        // }
        $html .=  '<tr>';
        foreach ($row as $cell) {
            $html .= '<td>' . htmlspecialchars($cell) . '</td>';
        }
        $html .= '</tr>';
    }
    
    $html .= '</tbody>';
    $html .= '</table>';
    
    fclose($csvFile);

    echo $html;
}

// Exemple d'utilisation
// Ajouter un employé
addEmployee("Marc", "Analyste", "1400");

// Afficher les employés
displayEmployees();
?>