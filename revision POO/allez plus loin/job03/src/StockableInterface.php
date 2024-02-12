<?php

namespace App;

interface StockableInterface {
    public function addStocks(int $stock): self;
    public function removeStocks(int $stock): self;
}