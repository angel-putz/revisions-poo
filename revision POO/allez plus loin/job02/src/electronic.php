<?php
/*
namespace App;

class Electronic extends AbstractProduct implements StockableInterface {
    private string $brand;
    private int $waranty_fee;

    public function __construct(int $id = null, string $name= null, array $photos= null, int $price= null, string $description= null, int $quantity= null, DateTime $createdAt= null, DateTime $updatedAt = null, Category $category_id= null, string $brand= null, int $waranty_fee= null) {
        parent::__construct($id, $name, $photos, $price, $description, $quantity, $createdAt, $updatedAt, $category_id);
        $this->brand = $brand;
        $this->waranty_fee = $waranty_fee;
    }

    public function findOneById(int $id): ?AbstractProduct
        {
        global $pdo; // Assurez-vous que $pdo est votre instance PDO connectée à la base de données

        $stmt = $pdo->prepare('SELECT * FROM product WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if ($row) {
            return new Electronic($row['id'], $row['name'], json_decode($row['photos'], true), $row['price'], $row['description'], $row['quantity'], new DateTime($row['created_at']), new DateTime($row['updated_at']), Category::getById($row['category_id']), $row['brand'], $row['waranty_fee']);
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
                $products[] = new Electronic($product['id'], $product['name'], json_decode($product['photos'], true), $product['price'], $product['description'], $product['quantity'], new DateTime($product['created_at']), new DateTime($product['updated_at']), Category::getById($product['category_id']), $product['brand'], $product['waranty_fee']);
            }
            return $products;
        }

        return null;
    }

    public function create () {

        global $pdo; // Assurez-vous que $pdo est votre instance PDO connectée à la base de données

        $stmt = $pdo->prepare('INSERT INTO product (name, photos, price, description, quantity, created_at, updated_at, category_id, brand, waranty_fee) VALUES (:name, :photos, :price, :description, :quantity, :created_at, :updated_at, :category_id, :brand, :waranty_fee)');
        $stmt->execute([
            'brand' => $this->brand,
            'waranty_fee' => $this->waranty_fee
        ]);    
    }

    public function update () {
        global $pdo; // Assurez-vous que $pdo est votre instance PDO connectée à la base de données

        $stmt = $pdo->prepare('UPDATE product SET brand = :brand, waranty_fee = :waranty_fee WHERE id = :id');
        $stmt->execute([
            'brand' => $this->brand,
            'waranty_fee' => $this->waranty_fee
        ]);

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

*/



namespace App;

use PDO;
use DateTime;
use Category;
use StockableInterface;




class Electronic extends AbstractProduct implements StockableInterface , EntityInterface{
    private string $brand;
    private int $warrantyFee;

    public function __construct(
        ?int $id = null,
        ?string $name = null,
        ?array $photos = null,
        ?int $price = null,
        ?string $description = null,
        ?int $quantity = null,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null,
        ?Category $category = null,
        ?string $brand = null,
        ?int $warrantyFee = null
    ) {
        parent::__construct($id, $name, $photos, $price, $description, $quantity, $createdAt, $updatedAt, $category);
        $this->brand = $brand;
        $this->warrantyFee = $warrantyFee;
    }

    public function findOneById (int $id): ?AbstractProduct {
        global $pdo; // Assurez-vous que $pdo est votre instance PDO connectée à la base de données
        $stmt = $pdo->prepare('SELECT * FROM product WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if ($row) {
            // Vous devrez ajuster la façon dont vous créez l'instance Category ici

            global $pdo; // Assurez-vous que $pdo est votre instance PDO connectée à la base de données
            $category = Category::getById($pdo, $row['category_id']);
            return new self(
                $row['id'],
                $row['name'],
                json_decode($row['photos'], true),
                $row['price'],
                $row['description'],
                $row['quantity'],
                new DateTime($row['created_at']),
                new DateTime($row['updated_at']),
                $category,
                $row['brand'],
                $row['warranty_fee']
            );
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
                $products[] = new Electronic($product['id'], $product['name'], json_decode($product['photos'], true), $product['price'], $product['description'], $product['quantity'], new DateTime($product['created_at']), new DateTime($product['updated_at']), Category::getById($product['category_id']), $product['brand'], $product['waranty_fee']);
            }
            return $products;
        }

        return null;
    }

    public function create () {

        global $pdo; // Assurez-vous que $pdo est votre instance PDO connectée à la base de données

        $stmt = $pdo->prepare('INSERT INTO product (name, photos, price, description, quantity, created_at, updated_at, category_id, brand, waranty_fee) VALUES (:name, :photos, :price, :description, :quantity, :created_at, :updated_at, :category_id, :brand, :waranty_fee)');
        $stmt->execute([
            'brand' => $this->brand,
            'waranty_fee' => $this->warrantyFee
        ]);    
    }

    public function update () {
        global $pdo; // Assurez-vous que $pdo est votre instance PDO connectée à la base de données

        $stmt = $pdo->prepare('UPDATE product SET brand = :brand, waranty_fee = :waranty_fee WHERE id = :id');
        $stmt->execute([
            'brand' => $this->brand,
            'waranty_fee' => $this->warrantyFee
        ]);

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

