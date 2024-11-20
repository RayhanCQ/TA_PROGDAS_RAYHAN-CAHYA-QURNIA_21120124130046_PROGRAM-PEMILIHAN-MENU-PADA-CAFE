<?php
class Order {
    private $id;
    private $name;
    private $snacks = [];
    private $drinks = [];

    public function __construct($id, $name, $snacks, $drinks) {
        $this->id = $id;
        $this->name = $name;
        $this->snacks = $snacks;
        $this->drinks = $drinks;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getSnacks() {
        return $this->snacks;
    }

    public function getDrinks() {
        return $this->drinks;
    }
    //fungsi ini dipakai di file edit order
    public function updateOrder($snacks, $drinks) {
        $this->snacks = $snacks;
        $this->drinks = $drinks;
    }
}
?>