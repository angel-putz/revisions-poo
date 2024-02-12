<?php

namespace App;
use DateTime;
use PDO;
use Product;

class Category {
    private int $id;
    private string $name;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(int $id, string $name, DateTime $createdAt, DateTime $updatedAt) {
        $this->id = $id;
        $this->name = $name;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
    public function getId(): int {
        return $this->id;
    }

    public static function getById(int $id): ?Category {
        global $pdo; // Assurez-vous que $pdo est votre instance PDO connectée à la base de données

        $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if ($row) {
            return new Category($row['id'], $row['name'] , new DateTime($row['created_at']), new DateTime($row['updated_at']));
        }

        return null;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getProduct (): array {

        global $pdo; // Assurez-vous que $pdo est votre instance PDO connectée à la base de données

        $stmt = $pdo->prepare('SELECT * FROM product WHERE category_id = :id');
        $stmt->execute(['id' => $this->id]);
        $row = $stmt->fetchAll();

        if ($row) {
            $products = [];
            foreach ($row as $product) {
                $products[] = new Product($product['id'], $product['name'], json_decode($product['photos'], true), $product['price'], $product['description'], $product['quantity'], new DateTime($product['created_at']), new DateTime($product['updated_at']), Category::getById($product['category_id']));
            }
            return $products;
        }

        return null;
    }

    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime {
        return $this->updatedAt;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setCreatedAt(DateTime $createdAt): void {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void {
        $this->updatedAt = $updatedAt;
    }
}
