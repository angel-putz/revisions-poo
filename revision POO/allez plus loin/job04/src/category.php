<?php

namespace App;
use DateTime;
use PDO;

class Category extends AbstractProduct implements EntityInterface , EntityInterface {
    private int $id;
    private string $name;
    private DateTime $createdAt;
    private DateTime $updatedAt;
    private $products;

    public function __construct(int $id, string $name, DateTime $createdAt, DateTime $updatedAt) {
        $this->id = $id;
        $this->name = $name;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->products = new EntityCollection();
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
                $products[] = new AbstractProduct($product['id'], $product['name'], json_decode($product['photos'], true), $product['price'], $product['description'], $product['quantity'], new DateTime($product['created_at']), new DateTime($product['updated_at']), Category::getById($product['category_id']));
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

    public function getProducts(): EntityCollection {
        return $this->products;
    }

    public function save () {
        if ($this->id === null) {
            $this->create();
        } else {
            $this->update();
        }
    }

    public function create () {
        global $pdo; // Assurez-vous que $pdo est votre instance PDO connectée à la base de données

        $stmt = $pdo->prepare('INSERT INTO categories (name, created_at, updated_at) VALUES (:name, :created_at, :updated_at)');
        $stmt->execute(['name' => $this->name, 'created_at' => $this->createdAt->format('Y-m-d H:i:s'), 'updated_at' => $this->updatedAt->format('Y-m-d H:i:s')]);
        $this->id = $pdo->lastInsertId();
    }

    public function update () {
        global $pdo; // Assurez-vous que $pdo est votre instance PDO connectée à la base de données

        $stmt = $pdo->prepare('UPDATE categories SET name = :name, updated_at = :updated_at WHERE id = :id');
        $stmt->execute(['name' => $this->name, 'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'), 'id' => $this->id]);
    }
}