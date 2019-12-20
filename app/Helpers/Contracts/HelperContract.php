<?php
namespace App\Helpers\Contracts;

Interface HelperContract
{
        public function sendEmailSMTP($data,$view,$type);
        public function createUser($data);
        public function createUserData($data);
        public function createProduct($u,$data);
        public function createProductData($data);
        public function createSale($u,$data);
        public function createSalesItem($data);
        public function createCustomer($u,$data);
        public function addSettings($data);
        public function getUser($email);
        public function getUsers();
        public function updateUser($data);
        public function hasKey($arr,$key);
        public function bomb($data);
        public function appLogin($data);
        public function appSignup($data);
        public function appSync($data);
        public function appSyncSend($data);
        public function appSyncReceive($data);
        public function isValidUser($data);
        public function clearData($user);
        public function getProducts($user);
        public function getProductData($product);
        public function getSales($user);
        public function getSalesItems($sale);
        public function getCustomers($user);
}
 ?>