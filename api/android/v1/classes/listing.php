<?php
namespace Listing;

use Guslists\Db;
use PDO;

class Listing
{
    private $conn;
    private $newListing;
    private $image;

    public function __construct($newListing = null)
    {
        require_once realpath(__DIR__ . '/../..') . '/include/db_connect.php';
        $db = new Db\DbConnect();
        $this->conn = $db->connect();
        $this->newListing = $newListing;
        $this->image = null;
    }

    public function retrieveLatestListings()
    {
        $listings = [];
        $result = ["error" => false];

        $sql = "SELECT 
                    i.dt_pub_date AS `publish_date`,
                    i.fk_i_category_id AS `category_id`,
                    i.pk_i_id AS `listing_id`, 
                    tu.pk_i_id AS `user_id`,
                    id.s_title AS `title`,
                    id.s_description AS `description`,
                    i.f_price,
                    i_price AS `price`,
                    i.fk_c_currency_code AS `currency`,
                    il.s_address AS `address`,
                    il.s_city AS `city`,
                    il.s_country AS `country`,
                    il.s_region AS `region`,
                    i.s_contact_name AS `seller_name`,
                    i.s_contact_email AS `seller_email`,
                    tu.s_phone_mobile AS `seller_mobile_no`,
                    tu.s_website AS `website`,
					group_concat(concat(ir.s_path ,ir.pk_i_id,'.',ir.s_extension)) AS images                  
                FROM bf_t_item i
                JOIN bf_t_item_description id ON id.fk_i_item_id = i.pk_i_id
                JOIN bf_t_user tu ON tu.pk_i_id = i.fk_i_user_id
                JOIN bf_t_item_location il ON il.fk_i_item_id = i.pk_i_id
                JOIN bf_t_item_resource ir ON ir.fk_i_item_id = i.pk_i_id
                GROUP BY i.pk_i_id
                ORDER BY i.dt_pub_date DESC;";
        $sth = $this->conn->prepare($sql);
        $sth->execute();

        $tempListings = $sth->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tempListings as $listing) {
            $listing['price'] = $listing['price'] / 1000000;
            $listing['images'] = $this->generateFullImageUrls($listing['images']);
            $listings [] = $listing;
        }

        if (count($listings) > 0) {
            $result['listings'] = $listings;
            return $result;
        } else {
            $result['error'] = true;
            $result['listings'] = $listings;
            return $result;
        }
    }

    public function retrieveUserLatestListings($userId)
    {
        $listings = [];
        $result = ["error" => false];

        $sql = "SELECT 
                    i.dt_pub_date AS `publish_date`,
                    i.fk_i_category_id AS `category_id`,
                    i.pk_i_id AS `listing_id`, 
                    tu.pk_i_id AS `user_id`,
                    id.s_title AS `title`,
                    id.s_description AS `description`,
                    i.f_price,
                    i_price AS `price`,
                    i.fk_c_currency_code AS `currency`,
                    il.s_address AS `address`,
                    il.s_city AS `city`,
                    il.s_country AS `country`,
                    il.s_region AS `region`,
                    i.s_contact_name AS `seller_name`,
                    i.s_contact_email AS `seller_email`,
                    tu.s_phone_mobile AS `seller_mobile_no`,
                    tu.s_website AS `website`,
					group_concat(concat(ir.s_path ,ir.pk_i_id,'.',ir.s_extension)) AS images                  
                FROM bf_t_item i
                JOIN bf_t_item_description id ON id.fk_i_item_id = i.pk_i_id
                JOIN bf_t_user tu ON tu.pk_i_id = i.fk_i_user_id
                JOIN bf_t_item_location il ON il.fk_i_item_id = i.pk_i_id
                JOIN bf_t_item_resource ir ON ir.fk_i_item_id = i.pk_i_id
                WHERE tu.pk_i_id = :user_id
                GROUP BY i.pk_i_id
                ORDER BY i.dt_pub_date DESC;";
        $sth = $this->conn->prepare($sql);
        $sth->bindParam(":user_id", $userId);
        $sth->execute();

        $tempListings = $sth->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tempListings as $listing) {
            $listing['price'] = $listing['price'] / 1000000;
            $listing['images'] = $this->generateFullImageUrls($listing['images']);
            $listings [] = $listing;
        }

        if (count($listings) > 0) {
            $result['listings'] = $listings;
            return $result;
        } else {
            $result['error'] = true;
            $result['listings'] = $listings;
            return $result;
        }
    }


    public function postNewListing()
    {
        $result = ["error" => false,
                    "message"=>"Listing was posted successfully"];

        $this->newListing['secret'] = $this->genRandomPassword();

        //Step 1: Insert general listing details
        $itemId = $this->saveListingDetails();

        //Step 2 : Insert title and description locales
        $this->insertListingLocale($itemId);

        //Step 3: Insert listing geo location
        $this->insertListingLocation($itemId);

        //Step 4: Save images
        $this->uploadItemResources($itemId);

        return $result;
    }

    private function uploadItemResources($itemId)
    {
        $count = count($_FILES);
        $lastInsertImageCounter = 0;

        for ($i = 1; $i <= $count; $i++) {
            $imageKey = 'image' . $i;

            $this->load($_FILES[$imageKey]['tmp_name']);
            $this->resize(640, 580);
            $this->save($lastInsertImageCounter, $_FILES[$imageKey]['name'], $itemId);
        }
    }

    private function load($filename)
    {
        $imageInfo = getimagesize($filename);
        $imageType = $imageInfo[2];
        if ($imageType == IMAGETYPE_JPEG) {

            $this->image = imagecreatefromjpeg($filename);
        } elseif ($imageType == IMAGETYPE_GIF) {

            $this->image = imagecreatefromgif($filename);
        } elseif ($imageType == IMAGETYPE_PNG) {

            $this->image = imagecreatefrompng($filename);
        }
    }

    private function resize($width, $height)
    {
        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }

    function getWidth()
    {

        return imagesx($this->image);
    }

    function getHeight()
    {

        return imagesy($this->image);
    }


    private function save(&$lastInsertImageCounter, $filename, $itemId, $compression = 100)
    {

        $target_path1 = realpath(__DIR__ . '/../../../..')."/bf/oc-content/uploads/0/";
        $s_content_type = 'image/jpeg';
        $path = 'oc-content/uploads/0/';

        $img = explode('.', $filename);

        if($lastInsertImageCounter == 0)
        {
            $imageItemId = $this->getLastImageResourceId() + 1;
        }
        else
        {
            $imageItemId = ++$lastInsertImageCounter;
        }

        $name1 = $imageItemId . '_' . 'thumbnail' . '.' . end($img);
        $name11 = $imageItemId . '.' . end($img);
        $name12 = $imageItemId . '_' . 'preview' . '.' . end($img);
        $extension = end($img);

        $target_path = $target_path1 . $name1;
        $target_pathh = $target_path1 . $name11;
        $target_pathhh = $target_path1 . $name12;

        imagejpeg($this->image, $target_path, $compression);
        imagejpeg($this->image, $target_pathh, $compression);
        imagejpeg($this->image, $target_pathhh, $compression);

        $sql = "INSERT INTO bf_t_item_resource(fk_i_item_id, 
                                                s_name,
                                                s_extension,
                                                s_content_type,
                                                s_path) 
                                        VALUES (:itemId, 
                                                :secret,
                                                :extension,
                                                :contentType,
                                                :path)";
        $sth = $this->conn->prepare($sql);
        $sth->bindParam(":itemId", $itemId);
        $sth->bindParam(":secret", $this->newListing['secret']);
        $sth->bindParam(":extension", $extension);
        $sth->bindParam(":contentType", $s_content_type);
        $sth->bindParam(":path", $path);

        $sth->execute();

        $lastInsertImageCounter = $this->conn->lastInsertId();
    }

    private function insertListingLocation($itemId)
    {
        $countryCode = '';
        $regionId = '';
        $cityId = '';
        $latitude = '';
        $longitude = '';
        $zip = '';

        $sql = "INSERT INTO bf_t_item_location (fk_i_item_id, 
                                                fk_c_country_code,
                                                s_country,
                                                fk_i_region_id,
                                                s_region,
                                                fk_i_city_id,
                                                s_city,
                                                s_city_area,
                                                s_address,
                                                d_coord_lat,
                                                d_coord_long,                                                
                                                s_zip) 
                                        VALUES (:itemId, 
                                                :countryCode,
                                                :country,
                                                :regionId,
                                                :region,
                                                :cityId,
                                                :city,
                                                :cityArea,
                                                :address,
                                                :latitude,
                                                :longitude,
                                                :zip)";
        $sth = $this->conn->prepare($sql);

        $sth->bindParam(":itemId", $itemId);
        $sth->bindParam(":countryCode", $countryCode);
        $sth->bindParam(":country", $this->newListing['country']);
        $sth->bindParam(":regionId", $regionId);
        $sth->bindParam(":region", $this->newListing['region']);
        $sth->bindParam(":cityId", $cityId);
        $sth->bindParam(":city", $this->newListing['city']);
        $sth->bindParam(":cityArea", $this->newListing['cityArea']);
        $sth->bindParam(":address", $this->newListing['address']);
        $sth->bindParam(":latitude", $latitude);
        $sth->bindParam(":longitude", $longitude);
        $sth->bindParam(":zip", $zip);

        $this->conn->exec("SET foreign_key_checks = 0");
        $sth->execute();
        $this->conn->exec("SET foreign_key_checks = 1");
    }

    private function insertListingLocale($id)
    {
        $locale = 'en_US';
        $sql = "INSERT INTO bf_t_item_description (fk_i_item_id, 
                                                   fk_c_locale_code,
                                                   s_title,
                                                   s_description) 
                                        VALUES (:itemId, 
                                                :localeCode,
                                                :title,
                                                :description)";
        $sth = $this->conn->prepare($sql);

        $sth->bindParam(":itemId", $id);
        $sth->bindParam(":localeCode", $locale);
        $sth->bindParam(":title", $this->newListing['title']);
        $sth->bindParam(":description", $this->newListing['description']);

        $sth->execute();
    }

    private function saveListingDetails()
    {
        $isSpam = false;
        $enabled = true;
        $active = true;
        $showPublisherEmail = true;
        $ip = "1.1.1.1";
        $now = date('Y-m-d H:i:s');
        $priceCorrection = (int) $this->newListing['price'] * 1000000;
        $sql = "INSERT INTO bf_t_item (fk_i_user_id, 
                                        dt_pub_date,
                                        fk_i_category_id,
                                        i_price, 
                                        fk_c_currency_code,
                                        s_contact_name,
                                        s_contact_email,
                                        s_secret,
                                        b_active,
                                        b_enabled,
                                        b_show_email,
                                        b_spam,
                                        s_ip) 
                                VALUES (:userId, 
                                        :pudDate,
                                        :catId,
                                        :price,
                                        :currency,
                                        :publisherName,
                                        :publisherEmail,
                                        :secret,
                                        :isActive,
                                        :isEnabled,
                                        :showEmail,
                                        :isSpam,
                                        :ip)";
        $sth = $this->conn->prepare($sql);

        $sth->bindParam(":userId", $this->newListing['userId']);
        $sth->bindParam(":pudDate", $now);
        $sth->bindParam(":catId", $this->newListing['categoryId']);
        $sth->bindParam(":price", $priceCorrection);
        $sth->bindParam(":currency", $this->newListing['currency']);
        $sth->bindParam(":publisherName", $this->newListing['contactName']);
        $sth->bindParam(":publisherEmail", $this->newListing['contactEmail']);
        $sth->bindParam(":secret", $this->newListing['secret']);
        $sth->bindParam(":isActive", $active);
        $sth->bindParam(":isEnabled", $enabled);
        $sth->bindParam(":showEmail", $showPublisherEmail);
        $sth->bindParam(":isSpam", $isSpam);
        $sth->bindParam(":ip", $ip);

        $sth->execute();

        return $this->conn->lastInsertId();
    }

    private function getLastImageResourceId()
    {
        $sql = "select pk_i_id from bf_t_item_resource ORDER BY pk_i_id DESC LIMIT 1";
        $sth = $this->conn->prepare($sql);
        $sth->execute();
        $result = $sth->fetch();
        return $result['pk_i_id'];
    }

    public function getAvailableCategories()
    {
        $result = [];
        $sql = "SELECT c.pk_i_id AS `categoryId`,
                     coalesce(c.fk_i_parent_id, 0) AS `categoryParentId`, 
                     cd.s_name AS `categoryName`
                     FROM bf_t_category c
                     JOIN bf_t_category_description cd ON cd.fk_i_category_id = c.pk_i_id";
        $sth = $this->conn->prepare($sql);
        $sth->execute();
        $tempResult = $sth->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tempResult as $category)
        {
            if($category['categoryParentId'] == 0)
            {
                $category['subCategories'] = $this->getSubCategories($tempResult, $category['categoryId']);
                $result[] = $category;
            }
        }

        return $result;
    }

    private function getSubCategories($allCategories, $parentId)
    {
        $result = [];
        foreach ($allCategories as $category)
        {
            if($category['categoryParentId']== $parentId)
            {
                $result[]= $category;
            }
        }

        return $result;
    }

    /**
     * Creates a random password.
     * @param int password $length. Default to 8.
     * @return string
     */
    private function genRandomPassword($length = 8)
    {
        $dict = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
        shuffle($dict);

        $pass = '';
        for ($i = 0; $i < $length; $i++)
            $pass .= $dict[rand(0, count($dict) - 1)];

        return $pass;
    }


    private function generateFullImageUrls($imagesLocation)
    {
        $source = "http://devguslists.com/bf/";
        $temp = explode(',', $imagesLocation);
        $image = [];
        foreach ($temp as $path) {
            $image[] = $source . $path;
        }

        return $image;
    }

}