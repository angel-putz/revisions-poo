<?php

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

    public function findOneById (int $id): ?Product {
        global $pdo; // Assurez-vous que $pdo est votre instance PDO connectée à la base de données

        $stmt = $pdo->prepare('SELECT * FROM product WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if ($row) {
            return new Product($row['id'], $row['name'], json_decode($row['photos'], true), $row['price'], $row['description'], $row['quantity'], new DateTime($row['created_at']), new DateTime($row['updated_at']), Category::getById($row['category_id']));
        }

        return null;
    }

    public function findAll(): array {
        global $pdo; // Assurez-vous que $pdo est votre instance PDO connectée à la base de données

        $stmt = $pdo->query('SELECT * FROM product');
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

    abstract public function create ();

    abstract public function update ();

}

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

class Clothing extends AbstractProduct implements StockableInterface{
    private string $size ;
    private string $color;
    private string $type;
    private int $material_fee;

    public function __construct(int $id = null, string $name= null, array $photos= null, int $price= null, string $description= null, int $quantity= null, DateTime $createdAt= null, DateTime $updatedAt = null, Category $category_id= null, string $size= null, string $color= null, string $type= null, int $material_fee= null) {
        parent::__construct($id, $name, $photos, $price, $description, $quantity, $createdAt, $updatedAt, $category_id);
        $this->size = $size;
        $this->color = $color;
        $this->type = $type;
        $this->material_fee = $material_fee;
    }

    public function findOneById(int $id): ?Product
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

class Electronic extends AbstractProduct implements StockableInterface {
    private string $brand;
    private int $waranty_fee;

    public function __construct(int $id = null, string $name= null, array $photos= null, int $price= null, string $description= null, int $quantity= null, DateTime $createdAt= null, DateTime $updatedAt = null, Category $category_id= null, string $brand= null, int $waranty_fee= null) {
        parent::__construct($id, $name, $photos, $price, $description, $quantity, $createdAt, $updatedAt, $category_id);
        $this->brand = $brand;
        $this->waranty_fee = $waranty_fee;
    }

    public function findOneById(int $id): ?Product
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

interface StockableInterface {
    public function addStocks(int $stock): self;
    public function removeStocks(int $stock): self;
}