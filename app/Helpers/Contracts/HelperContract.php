<?php
namespace App\Helpers\Contracts;

Interface HelperContract
{
        public function sendEmailSMTP($data,$view,$type);
        public function createUser($data);
        public function createUserData($data);
        public function createBankAccount($data);
        public function addSettings($data);
        public function getSetting($i);
        public function getUser($email);
        public function getUsers();
        public function updateUser($data);
        public function updateProfile($user, $data);
        public function getBankAccount($user);
        public function updateBankAccount($user,$data);
        public function updateConfig($data);
        public function getDashboard($user);
        public function addSmtpConfig($data);
        public function getSmtpConfig();
        public function hasKey($arr,$key);
        public function updateSmtpConfig($data);
        public function bomb($data);
        public function getConfigNumber();
        public function getConfig($id,$config);
        public function getConfigs($id);
        public function addConfig($data);
		
}
 ?>