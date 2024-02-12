<?php

namespace App;
use DateTime;

class Clothing extends AbstractProduct implements StockableInterface , EntityInterface{
    private string $size ;
    private string $color;
    private string $type;
    private int $material_fee;
    private int $id;

    public function __construct(int $id = null, string $name= null, array $photos= null, int $price= null, string $description= null, int $quantity= null, DateTime $createdAt= null, DateTime $updatedAt = null, Category $category_id= null, string $size= null, string $color= null, string $type= null, int $material_fee= null) {
        parent::__construct($id, $name, $photos, $price, $description, $quantity, $createdAt, $updatedAt, $category_id);
        $this->size = $size;
        $this->color = $color;
        $this->type = $type;
        $this->material_fee = $material_fee;
    }

    public function findOneById(int $id): ?AbstractProduct
    {
        global $pdo; // Assurez-vous que $pdo est votre instance PDO connectée à la base de données

        $stmt = $pdo->prepare('SELECT * FROM product WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if ($row) {
            return new Clothing($row['id'], $row['name'], json_decode($row['photos'], true), $row['price'], $row['description'], $row['quantity'], new DateTime($row['created_at']), new DateTime($row['updated_at']), Category::getById($row['category_id']), $row['size'], $row['color'], $row['type'], $row['material_fee']);
        }

        return null;
    }

    public function findAll(): array
    {
        global $pdo; // Assurez-vous que $pdo est votre instance PDO connectée à la base de données

        $stmt = $pdo->query('SELECT * FROM product');
        $row = $stmt->fetchAll();

        if ($row) {
            $products = [];
            foreach ($row as $product) {
                $products[] = new Clothing($product['id'], $product['name'], json_decode($product['photos'], true), $product['price'], $product['description'], $product['quantity'], new DateTime($product['created_at']), new DateTime($product['updated_at']), Category::getById($product['category_id']), $product['size'], $product['color'], $product['type'], $product['material_fee']);
            }
            return $products;
        }

        return null;
    

    }

    public function create () {
        global $pdo; // Assurez-vous que $pdo est votre instance PDO connectée à la base de données

        $stmt = $pdo->prepare('INSERT INTO product (name, photos, price, description, quantity, created_at, updated_at, category_id, size, color, type, material_fee) VALUES (:name, :photos, :price, :description, :quantity, :created_at, :updated_at, :category_id, :size, :color, :type, :material_fee)');
        $stmt->execute([
            'size' => $this->size,
            'color' => $this->color,
            'type' => $this->type,
            'material_fee' => $this->material_fee
        ]);    
    }

    public function update () {
        global $pdo; // Assurez-vous que $pdo est votre instance PDO connectée à la base de données

        $stmt = $pdo->prepare('UPDATE product SET size = :size, color = :color, type = :type, material_fee = :material_fee WHERE id = :id');
        $stmt->execute([
            'size' => $this->size,
            'color' => $this->color,
            'type' => $this->type,
            'material_fee' => $this->material_fee
        ]);

    }

    public function save () {
        if ($this->id === null) {
            $this->create();
        } else {
            $this->update();
        }
    }

    public function addStocks(int $stock): self {
        global $pdo; // Assurez-vous que $pdo est votre instance PDO connectée à la base de données

        $stmt = $pdo->prepare('UPDATE product SET quantity = quantity + :stock WHERE id = :id');
        $stmt->execute(['stock' => $stock, 'id' => $this->getId()]);

        return $this;
    }

    public function removeStocks(int $stock): self {
        global $pdo; // Assurez-vous que $pdo est votre instance PDO connectée à la base de données

        $stmt = $pdo->prepare('UPDATE product SET quantity = quantity - :stock WHERE id = :id');
        $stmt->execute(['stock' => $stock, 'id' => $this->getId()]);

        return $this;
    }
}