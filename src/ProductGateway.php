<?php

class ProductGateway
{
    private PDO $conn;
    
    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }
    
    public function getAll(): array
    {
        $sql = "SELECT *
                FROM products";
                
        $stmt = $this->conn->query($sql);
        
        $data = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
            
            $data[] = $row;
        }
        
        return $data;
    }
    
    public function create(array $data): string
    {
        $sql = "INSERT INTO products (sku, name, size, category, price)
                VALUES (:sku, :name, :size, :category, :price)";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":sku", $data["sku"], PDO::PARAM_STR);
        $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);
        $stmt->bindValue(":category", $data["category"], PDO::PARAM_STR);
        $stmt->bindValue(":price", $data["price"] ?? 0, PDO::PARAM_INT);
        $stmt->bindValue(":size", $data["size"], PDO::PARAM_STR);
        
        $stmt->execute();
        
        return $this->conn->lastInsertId();
    }
    
    public function get(string $id): array | false
    {
        $sql = "SELECT *
                FROM products
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        
        return $data;
    }
    
    public function delete(array $data): string
    {

        $result = [];

        foreach ($data["ids"] as $id) {
            $result[] =  $id;
        };

        $sql = 'DELETE
          FROM `products` 
         WHERE `id` IN (' . implode(',', array_map('intval', $result)) . ')';

        $stmt = $this->conn->prepare($sql);
        
        $stmt->execute();
        
        return $stmt->rowCount();
    }
}











