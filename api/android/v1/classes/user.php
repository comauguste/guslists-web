<?php

namespace User;

use Error\Error;
use Guslists\Db;
use PDO;

class User
{

    private $conn;
    private $response;
    private $errorHandler;

    public function __construct()
    {
        require_once realpath(__DIR__ . '/../..') . '/include/db_connect.php';
        $db = new Db\DbConnect();
        $this->conn = $db->connect();
        //$this->errorHandler = new Error();
        $this->response = ["statusCode" => 200];
    }

    public function getUserByPhoneNumber($phone)
    {
        $sql = "SELECT u.pk_i_id AS `userId`,
		u.dt_reg_date AS `registrationDate`,
		u.s_name AS `fullName`,
		u.s_email AS `email`,
		u.s_phone_mobile AS `phoneNumber`,
		concat(u.s_country,', ',u.s_region,', ',u.s_city) AS `address`,
		u.s_day_of_birth AS `dayOfBirth`,
        u.s_gender AS `gender`
         FROM bf_t_user u 
         WHERE u.b_enabled = 1 AND  u.s_phone_mobile = :phone
         LIMIT 1";
        $sth = $this->conn->prepare($sql);
        $sth->bindParam(":phone", $phone);
        $sth->execute();

        if ($sth->rowCount() == 1) {
            $this->response["user"] = $sth->fetchAll(PDO::FETCH_ASSOC);;
            $this->response["user"][0]["profilePicture"] = $this->getProfileImagePath($this->response["user"][0]["userId"]);
        } else {
            // user not fount
            $this->response['statusCode'] = 404;
            $this->response['message'] = "User not found";
        }

        return $this->response;
    }

    public function getUserByEmail($email)
    {
        $sql = "SELECT u.pk_i_id AS `userId`,
		u.dt_reg_date AS `registrationDate`,
		u.s_name AS `fullName`,
		u.s_email AS `email`,
		u.s_phone_mobile AS `phoneNumber`,
		concat(u.s_country,', ',u.s_region,', ',u.s_city) AS `address`,
		u.s_day_of_birth AS `dayOfBirth`,
        u.s_gender AS `gender`
         FROM bf_t_user u 
         WHERE u.b_enabled = 1 AND  u.s_email = :email
         LIMIT 1";
        $sth = $this->conn->prepare($sql);
        $sth->bindParam(":email", $email);
        $sth->execute();

        if ($sth->rowCount() == 1) {
            $this->response["user"] = $sth->fetchAll(PDO::FETCH_ASSOC);;
            $this->response["user"][0]["profilePicture"] = $this->getProfileImagePath($this->response["user"][0]["userId"]);
        } else {
            // user not fount
            $this->response['statusCode'] = 404;
            $this->response['message'] = "User not found";
        }

        return $this->response;
    }

    public function getUserByFacebookId($facebookId)
    {
        $sql = "SELECT u.pk_i_id AS `userId`,
		u.dt_reg_date AS `registrationDate`,
		u.s_name AS `fullName`,
		u.s_email AS `email`,
		u.s_phone_mobile AS `phoneNumber`,
		concat(u.s_country,', ',u.s_region,', ',u.s_city) AS `address`,
		u.s_day_of_birth AS `dayOfBirth`,
        u.s_gender AS `gender`
         FROM bf_t_user u 
         WHERE u.b_enabled = 1 AND  u.facebook_id = :facebook_id
         LIMIT 1";
        $sth = $this->conn->prepare($sql);
        $sth->bindParam(":facebook_id", $facebookId);
        $sth->execute();

        if ($sth->rowCount() == 1) {
            $this->response["user"] = $sth->fetchAll(PDO::FETCH_ASSOC);;
            $this->response["user"][0]["profilePicture"] = $this->getProfileImagePath($this->response["user"][0]["userId"]);
        } else {
            // user not fount
            $this->response['statusCode'] = 404;
            $this->response['message'] = "User not found";
        }

        return $this->response;
    }

    public function getUserByEmailOrPhoneNumber($email, $phone)
    {
        $sql = "SELECT u.pk_i_id AS `userId`,
		u.dt_reg_date AS `registrationDate`,
		u.s_name AS `fullName`,
		u.s_email AS `email`,
		u.s_phone_mobile AS `phoneNumber`,
		concat(u.s_country,', ',u.s_region,', ',u.s_city) AS `address`,
		u.s_day_of_birth AS `dayOfBirth`,
        u.s_gender AS `gender`
         FROM bf_t_user u 
         WHERE u.b_enabled = 1 AND u.s_email = :email OR u.s_phone_mobile = :phone
         LIMIT 1";
        $sth = $this->conn->prepare($sql);
        $sth->bindParam(":email", $email);
        $sth->bindParam(":phone", $phone);
        $sth->execute();

        return $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    }

    private function getUserByUserId($userId)
    {
        $sql = "SELECT u.pk_i_id AS `userId`,
		u.dt_reg_date AS `registrationDate`,
		u.s_name AS `fullName`,
		u.s_email AS `email`,
		u.s_phone_mobile AS `phoneNumber`,
		u.s_biography AS `biography`,
		concat(u.s_country,', ',u.s_region,', ',u.s_city) AS `address`,
		u.s_day_of_birth AS `dayOfBirth`,
        u.s_gender AS `gender`
         FROM bf_t_user u 
         WHERE u.pk_i_id = :userId
         LIMIT 1";
        $sth = $this->conn->prepare($sql);
        $sth->bindParam(":userId", $userId);
        $sth->execute();

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        $result[0]['profilePicture'] = $this->getProfileImagePath($userId);

        return $result[0];
    }

    private function isUserExists($email, $phone)
    {
        $sql = "SELECT u.pk_i_id AS `user_id`
         FROM bf_t_user u 
         WHERE u.b_enabled = 1 AND u.b_active = 1 AND u.s_email = :email OR u.s_phone_mobile = :phone
         LIMIT 1";

        $sth = $this->conn->prepare($sql);
        $sth->bindParam(":email", $email);
        $sth->bindParam(":phone", $phone);
        $sth->execute();

        if ($sth->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function createNewUser($fullName, $email, $phone, $profileImage, $facebookId)
    {
        if ($this->isUserExists($email, $phone) == true) {
            // user already existed
            $this->response['statusCode'] = 409;
            $this->response['message'] = "User already existed";
            return $this->response;
        }

        $sql = "INSERT INTO bf_t_user (dt_reg_date,dt_mod_date,s_name,s_email,s_phone_mobile,facebook_id, b_enabled,b_active) 
                VALUES (NOW(), NOW(),:fullName, :email, :phone, :facebookId, 1 ,1)";
        $sth = $this->conn->prepare($sql);

        $sth->bindParam(":fullName", $fullName);
        $sth->bindParam(":email", $email);
        $sth->bindParam(":phone", $phone);
        $sth->bindParam(":facebookId", $facebookId);
        $sth->execute();

        $this->response["user"] = $this->getUserByEmailOrPhoneNumber($email, $phone);

        if ($profileImage != '') {
            $this->response["user"][0]["profilePicture"] = $this->uploadUserProfilePicture($this->response["user"][0]["userId"], $profileImage);
        } else {
            $this->response["user"][0]["profilePicture"] = $this->getProfileImagePath($this->response["user"][0]["userId"]);
        }

        return $this->response;

    }

    public function retrieveUserDetails($userId)
    {
        $this->response["user"] = $this->getUserByUserId($userId);
        return $this->response;
    }

    public function updateUserInformation($userId, $fullName, $email, $phone, $profileImage, $dayOfBirth, $address, $gender)
    {
        $addressMap = $this->getAddressMap($address);

        $sql = "UPDATE bf_t_user SET
                s_name = :fullName,
                S_email = :email,
                s_phone_mobile = :phone,
                s_country = :country,
                s_region = :region,
                s_city = :city,
                s_day_of_birth = :dayOfBirth,
                s_gender = :gender
                WHERE pk_i_id = :userId ;";

        $sth = $this->conn->prepare($sql);
        $sth->bindParam(":userId", $userId);
        $sth->bindParam(":fullName", $fullName);
        $sth->bindParam(":email", $email);
        $sth->bindParam(":phone", $phone);
        $sth->bindParam(":country", $addressMap['country']);
        $sth->bindParam(":region", $addressMap['region']);
        $sth->bindParam(":city", $addressMap['city']);
        $sth->bindParam(":dayOfBirth", $dayOfBirth);
        $sth->bindParam(":gender", $gender);
        $sth->execute();

        $this->response["user"] = $this->getUserByUserId($userId);
        $this->response["user"][0]["profilePicture"] = $this->uploadUserProfilePicture($this->response["user"][0]["userId"],
            $profileImage);

        return $this->response;
    }

    public function updateUserName($userId, $fullName)
    {
        $sql = "UPDATE bf_t_user SET
                s_name = :fullName
                WHERE pk_i_id = :userId ;";

        $sth = $this->conn->prepare($sql);
        $sth->bindParam(":userId", $userId);
        $sth->bindParam(":fullName", $fullName);
        $sth->execute();

        $this->response["user"] = $this->getUserByUserId($userId);

        return $this->response;
    }

    public function updateUserEmail($userId, $email)
    {

        $sql = "UPDATE bf_t_user SET
                S_email = :email
                WHERE pk_i_id = :userId ;";

        $sth = $this->conn->prepare($sql);
        $sth->bindParam(":userId", $userId);
        $sth->bindParam(":email", $email);;
        $sth->execute();

        $this->response["user"] = $this->getUserByUserId($userId);

        return $this->response;
    }

    public function updateUserPhoneNumber($userId, $phone)
    {
        $sql = "UPDATE bf_t_user SET               
                s_phone_mobile = :phone                
                WHERE pk_i_id = :userId ;";

        $sth = $this->conn->prepare($sql);
        $sth->bindParam(":userId", $userId);
        $sth->bindParam(":phone", $phone);
        $sth->execute();

        $this->response["user"] = $this->getUserByUserId($userId);

        return $this->response;
    }

    public function updateUserBirthday($userId, $dayOfBirth)
    {

        $sql = "UPDATE bf_t_user SET
                s_day_of_birth = :dayOfBirth
                WHERE pk_i_id = :userId ;";

        $sth = $this->conn->prepare($sql);
        $sth->bindParam(":userId", $userId);
        $sth->bindParam(":dayOfBirth", $dayOfBirth);
        $sth->execute();

        $this->response["user"] = $this->getUserByUserId($userId);

        return $this->response;
    }

    public function updateUserAddress($userId, $address)
    {
        $addressMap = $this->getAddressMap($address);

        $sql = "UPDATE bf_t_user SET
                s_country = :country,
                s_region = :region,
                s_city = :city
                WHERE pk_i_id = :userId ;";

        $sth = $this->conn->prepare($sql);
        $sth->bindParam(":userId", $userId);
        $sth->bindParam(":country", $addressMap['country']);
        $sth->bindParam(":region", $addressMap['region']);
        $sth->bindParam(":city", $addressMap['city']);
        $sth->execute();

        $this->response["user"] = $this->getUserByUserId($userId);

        return $this->response;
    }

    public function updateUserGender($userId, $gender)
    {
        $sql = "UPDATE bf_t_user SET
                s_gender = :gender
                WHERE pk_i_id = :userId ;";

        $sth = $this->conn->prepare($sql);
        $sth->bindParam(":userId", $userId);
        $sth->bindParam(":gender", $gender);
        $sth->execute();

        $this->response["user"] = $this->getUserByUserId($userId);

        return $this->response;
    }

    public function updateUserBiography($userId, $biography)
    {
        $sql = "UPDATE bf_t_user SET
                s_biography = :biography
                WHERE pk_i_id = :userId ;";

        $sth = $this->conn->prepare($sql);
        $sth->bindParam(":userId", $userId);
        $sth->bindParam(":biography", $biography);
        $sth->execute();

        $this->response["user"] = $this->getUserByUserId($userId);

        return $this->response;
    }

    public function uploadProfileImage($userId, $profileImage)
    {

        $this->uploadUserProfilePicture($userId, $profileImage);
        $this->response["user"] = $this->getUserByUserId($userId);
        return $this->response;
    }


    private function getAddressMap($address)
    {
        $tempAddress = explode(",", $address);
        $size = count($tempAddress);

        $country = $size > 0 ? $tempAddress [0] : "";
        $region = $size > 1 ? $tempAddress [1] : "";
        $city = $size > 2 ? $tempAddress [2] : "";

        return $address = ['city' => $city, 'region' => $region, 'country' => $country];
    }

    private function uploadUserProfilePicture($userId, $profilePicture)
    {
        $imagePath = $this->getProfileImagePath($userId);
        $internalUploadDir = realpath(__DIR__ . '/../../../..') . "/bf/oc-content/plugins/profile_picture/images/profile" . $userId . ".jpg";
        $success = file_put_contents($internalUploadDir, base64_decode($profilePicture));

        return $imagePath;
    }

    private function getProfileImagePath($userId)
    {
        $path = WEB_PATH . "/bf/oc-content/plugins/profile_picture/images/profile" . $userId . ".jpg";
        return $path;
    }
}