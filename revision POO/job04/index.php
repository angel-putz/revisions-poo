<?php

// Inclure le fichier Product.php
require_once 'job04.php';

// Connexion à la base de données (à remplacer avec vos propres informations de connexion)
$pdo = new PDO('mysql:host=localhost;dbname=draft-shop', 'root', '');

// Requête SQL pour récupérer le produit avec l'ID 7
$sql = "SELECT * FROM product WHERE id = 7";
$stmt = $pdo->query($sql);

$sql2 = "SELECT * FROM category WHERE id = 7";
$stmt2 = $pdo->query($sql2);

// Récupération des données sous forme de tableau associatif
$productData = $stmt->fetch(PDO::FETCH_ASSOC);

$categoryData = $stmt2->fetch(PDO::FETCH_ASSOC);
// Création d'une instance de la classe Product
$product = new Product(
    $productData['name'],
    json_decode($productData['photos'], true), // Si les photos sont stockées sous forme JSON
    $productData['price'],
    $productData['description'],
    $productData['quantity'],
    new DateTime($productData['created_at']),
    new DateTime($productData['updated_at']),
    $productData['category_id'],
);

// Vous pouvez maintenant utiliser l'instance de Product pour accéder aux données du produit récupéré

?>