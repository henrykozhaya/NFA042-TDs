<?php
$json = '
[
    {
        "id": "1",
        "name": "Alice Johnson",
        "email": "alice.johnson@example.com"
    },
    {
        "id": "2",
        "name": "Bob Smith",
        "email": "bob.smith@example.com"
    },
    {
        "id": "3",
        "name": "Charlie Brown",
        "email": "charlie.brown@example.com"
    }
]';

$data = json_decode($json, true);
$html = "<table boder=1>";
$html .= "<thead>";
$html .= "<th>ID</th>";
$html .= "<th>Name</th>";
$html .= "<th>Email</th>";
$html .= "</thead>";
$html .= "<tbody>";

foreach($data as $user) {
    $html .= "<tr>";
    $html .= "<td>" . htmlspecialchars($user['id']) . "</td>";
    $html .= "<td>" . htmlspecialchars($user['name']) . "</td>";
    $html .= "<td>" . htmlspecialchars($user['email']) . "</td>";
    $html .= "</tr>";
}

$html .= "</tbody>";
$html .= "</table>";

echo $html;