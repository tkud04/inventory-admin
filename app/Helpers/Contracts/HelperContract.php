<?php
namespace App\Helpers\Contracts;

Interface HelperContract
{
        public function sendEmailSMTP($data,$view,$type);
        public function createUser($data);
        public function createUserData($data);
        public function addSettings($data);
        public function getUser($email);
        public function getUsers();
        public function updateUser($data);
        public function hasKey($arr,$key);
        public function bomb($data);
        public function appLogin($data);
        public function appSignup($data);
        public function getUserProducts($user);
        public function getUserCustomers($user);
        public function getUserSales($user);
        public function appSync($data);
        public function appSyncSend($data);
        public function appSyncReceive($data);
        public function isValidUser($data);
}
 ?>