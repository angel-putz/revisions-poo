<?php

namespace App;

use DateTime;
use PDO;
use Category;

abstract class AbstractProduct {
    private int $id;
    private string $name;
    private array $photos;
    private int $price;
    private string $description;
    private int $quantity;
    private DateTime $createdAt;
    private DateTime $updatedAt;
    private Category $category_id;

    public function __construct(int $id = null, string $name= null, array $photos= null, int $price= null, string $description= null, int $quantity= null, DateTime $createdAt= null, DateTime $updatedAt = null, Category $category_id= null) {
        $this->id = $id;
        $this->category_id = $category_id;
        $this->name = $name;
        $this->photos = $photos;
        $this->price = $price;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;

        
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getCategory_id(): Category {
        return $this->category_id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPhotos(): array {
        return $this->photos;
    }

    public function getPrice(): int {
        return $this->price;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }

    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime {
        return $this->updatedAt;
    }

    public function getCategory(): Category {
        if ($this->category_id !== null) {
            return Category::getById($this->category_id->getId());
        }
        return null;
    }

    // Setters
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setCategory_id(Category $category_id): void {
        $this->category_id = $category_id;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setPhotos(array $photos): void {
        $this->photos = $photos;
    }

    public function setPrice(int $price): void {
        $this->price = $price;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function setQuantity(int $quantity): void {
        $this->quantity = $quantity;
    }

    public function setCreatedAt(DateTime $createdAt): void {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void {
        $this->updatedAt = $updatedAt;
    }

    public function findOneById (int $id): ?AbstractProduct {
        global $pdo; // Assurez-vous que $pdo est votre instance PDO connectée à la base de données

        $stmt = $pdo->prepare('SELECT * FROM product WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if ($row) {
            return new AbstractProduct($row['id'], $row['name'], json_decode($row['photos'], true), $row['price'], $row['description'], $row['quantity'], new DateTime($row['created_at']), new DateTime($row['updated_at']), Category::getById($row['category_id']));
        }

        return null;
    }

    public function save () {
        if ($this->id === null) {
            $this->create();
        } else {
            $this->update();
        }
    }

    public function findAll(): array {
        global $pdo; // Assurez-vous que $pdo est votre instance PDO connectée à la base de données

        $stmt = $pdo->query('SELECT * FROM product');
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

    abstract public function create ();

    abstract public function update ();

}