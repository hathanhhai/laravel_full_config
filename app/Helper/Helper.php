<?php

namespace App\Helper;

use Jenssegers\Agent\Agent;

class Helper
{
    public static function convertObjectName($name)
    {
        $word = ucfirst(strtolower($name));
        $prefixs = explode('-', $word);
        $method_name = "";
        foreach ($prefixs as $prefix) {
            $method_name .= ucfirst($prefix);
        }
        return $method_name;
    }

    public static function generatorModelCode($model)
    {

        $year = strtotime(date('Y-m') . '-01');
        $count = $model->where('created_at', '>', $year)->count() + 1;
        $increament = str_pad($count, 5, '0', STR_PAD_LEFT);
        $prefix = property_exists($model, 'prefix') ? $model->prefix : 'CODE';
        return $prefix . '-' . date('dmy') . "-" . $increament;
    }

    public static function filter($user, &$menu)
    {
        if ($user['admin']) {
            return;
        }
        $roles = !empty($user['roles']) ? $user['roles'] : [];
        foreach ($menu as $index => &$item) {
            $valid = self::validLinkRole($roles, $item);
            if (!$valid) {
                unset($item['name']);
                unset($item['link']);
            }
            if (!empty($item['childs'])) {
                foreach ($item['childs'] as $child_index => $child) {
                    $valid = self::validLinkRole($roles, $child);
                    if (!$valid) {
                        unset($item['childs'][$child_index]);
                    }
                }
            }
            if (empty($item['childs']) && empty($item['link'])) {
                unset($menu[$index]);
            }
        }
    }

    public static function covertStringNumberToFloat(&$str_val)
    {
        $str_val = floatval(str_replace([',', '.'], '', $str_val));
    }

    private static function validLinkRole($roles, $item)
    {
        $link = isset($item['link']) ? $item['link'] : '';
        if (empty($link)) {
            return true;
        }
        $links = explode('/', $link);
        $count = count($links);
        if ($count == 0) {
            return true;
        }
        $method = isset($links[2]) ? $links[2] : 'index';
        $controller = !empty($link[1]) ? $links[1] : $link[0];
        $app_role = config('route.admin');
        if (!empty($controller) && isset($app_role[$controller])) {
            $app_controller = $app_role[$controller];
            $controller_levels = explode("\\", $app_controller);
            $count_controller_level = count($controller_levels);
            if ($count_controller_level > 0) {
                $role_name = $controller_levels[$count_controller_level - 1];
                $role_name .= "@{$method}";
                return in_array($role_name, $roles) || substr($method, 0, 1) == '_';
            }
        }
    }

    public static function canAccess($controller, $method, $user)
    {
        $excepts = ['doAction', '_post', '_get'];
        foreach ($excepts as $except) {
            $pos = strpos($method, $except);
            if ($pos !== false && $pos == 0) {
                return true;
            }
        }
        if ($user && $user->admin) {
            return true;
        }
        $StringRole = "{$controller}@{$method}";
        $roles = $user ? isset($user['roles']) ? $user['roles'] : [] : [];
        if (!in_array($StringRole, $roles)) {
            return false;
        }
        return true;

    }

    public static function getClientIP()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public static function getDeviceInfo()
    {
        $agent = new Agent();
        $info = [
            'ip' => self::getClientIP(),
            'is_mobile' => $agent->isMobile(),
            'is_tablet' => $agent->isTablet(),
            'is_desktop' => $agent->isDesktop(),
            'device' => $agent->device(),
            'platform' => [
                'name' => $agent->platform(),
                'version' => $agent->version($agent->platform())
            ],
            'browser' => [
                'name' => $agent->browser(),
                'version' => $agent->version($agent->browser()),
            ],
            'is_robot' => $agent->isRobot(),
            'robot' => [
                'name' => $agent->robot()
            ]
        ];
        return $info;
    }

    public static function getDaysInMonth($month, $year)
    {
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
    }

    public static function getRemainDays($start, $stop)
    {
        $datediff = $stop - $start;
        return round($datediff / (60 * 60 * 24));
    }

    public static function getCurrentDomain($state = false)
    {
        $url = url('');
        $url = explode('://', $url, 2);
        $url = str_replace('www.', '', $url[1]);
        // $url = str_replace('.', '_', $url);
        return explode('.', $url)[0];
    }

    public static function randFileName($length,$filename)
    {
        return str_random($length).'_'.str_slug($filename->getClientOriginalName()) . '.' . $filename->extension();
    }


    static function getCurrentHost($state = false)
    {
        $url = url('');
        return $url;
        $url = explode('://', $url, 2);
        $url = str_replace('www.', '', $url[1]);
        // $url = str_replace('.', '_', $url);
        return explode('.', $url);
    }

    public
    static function copyData($source, &$copy_to, $keys = [])
    {
        try {
            if (!empty($keys)) {
                foreach ($keys as $key) {
                    if (isset($source[$key])) {
                        $copy_to[$key] = $source[$key];
                    }
                }
            } else {
                foreach ($source as $key => $value) {
                    $copy_to[$key] = $value;
                }
            }
        } catch (\Exception $e) {
        }
    }

    public
    static function getConnection()
    {
        return 'mongodb';
    }

    public
    static function convertBase64ToImage($path, $imageBase64)
    {
        list($type, $data) = explode(';', $imageBase64);
        list(, $data) = explode(',', $imageBase64);
        $data = base64_decode($data);
        file_put_contents($path, $data);
    }

    public
    static function randFileNameImage($extension)
    {
        return str_random(35) . '.' . $extension;
    }

    public
    static function setConnectionByStore()
    {
        $connection = Helper::getCurrentDomain();
        if ($connection != '') {
            if (!Helper::isPrimaryDomain($connection)) {
                $path = config_path();
                if (file_exists($path . '/store_access/' . $connection . '.json')) {
                    $db = 'm_' . $connection;
                    \Config::set('database.connections.mongodb', ['driver' => 'mongodb',
                        'host' => env('DB_HOST'),
                        'port' => env('DB_PORT'),
                        'database' => $db,
                        'username' => env('DB_USERNAME'),
                        'password' => env('DB_PASSWORD'),
                        'charset' => 'utf8',
                        'collation' => 'utf8_unicode_ci',
                        'options' => [
                            'database' => $db
                        ]]);
                    return true;
                }
            } else {
                $db = 'msale';
                \Config::set('database.connections.mongodb', ['driver' => 'mongodb',
                    'host' => env('DB_HOST'),
                    'port' => env('DB_PORT'),
                    'database' => $db,
                    'username' => env('DB_USERNAME'),
                    'password' => env('DB_PASSWORD'),
                    'charset' => 'utf8',
                    'collation' => 'utf8_unicode_ci',
                    'options' => [
                        'database' => $db
                    ]]);
                return true;
            }
        }
        return false;
    }

    public
    static function checkAccessStore()
    {
        $store = Helper::getCurrentDomain();
        if (!Helper::isPrimaryDomain($store)) {
            if ($store != '') {
                $path = config_path();
                return file_exists($path . '/store_access/' . $store . '.json');
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public
    static function isPrimaryDomain($store = '')
    {
        if ($store == '') {
            $store = Helper::getCurrentDomain();
        }
        $store = sha1($store);
        $arr = array("4b148a9c4f32d25449146c3ca18a37432464b6f5", "c1855fc9029af2173748bd2fa3048ed2211b6026");
        return in_array($store, $arr);
    }

    public
    static function convertStringToBoolean($str_arr = [])
    {
        if (!empty($str_arr)) {
            $data = array();
            foreach ($str_arr as $key => $value) {
                $data[$key] = ($value == "true") ? true : false;
            }
            return $data;
        }
    }

    public
    static function uploadFileToServer($listFile = [], $destinationPath = '')
    {
        $random = rand(1, 10000);
        if ($destinationPath == '') {
            $destinationPath = 'upload/unknow';
        }
        if (!is_dir(public_path($destinationPath))) {
            mkdir(public_path($destinationPath), 0777, true);
        }
        $countListFile = count($listFile);
        if ($countListFile == 0) return false;
        if ($countListFile == 1) {

            $nameFile = $random . '_' . time() . '.jpg';
            move_uploaded_file($listFile[0]->tmp_name, public_path($destinationPath . '/' . $nameFile));
            return $destinationPath . '/' . $nameFile;
        }
        if ($countListFile > 1) {
            foreach ($listFile as $file) {
                dd($file);
            }
        }
    }

    public
    static function dump($data = null)
    {
        echo "<pre>";
        print_r($data);
        die();
    }

    public
    static function convertStringToInt($string = "")
    {
        if (!empty($string)) {
            if (strpos($string, ',') !== false) {
                $string = (int)str_replace(',', '', $string);
            } else {
                $string = (int)$string;
            }
        } else {
            $string = (int)$string;
        }
        return $string;
    }

    public
    static function convertStringToFloat($string = "")
    {
        if (!empty($string)) {
            if (strpos($string, ',') !== false) {
                $string = (float)str_replace(',', '', $string);
            } else {
                $string = (float)$string;
            }
        } else {
            $string = (float)$string;
        }
        return $string;
    }

    public
    static function curlPost($url = '', $param = [])
    {
        $data_string = json_encode($param); // convert to json
        // Khởi tạo CURL
        $ch = curl_init($url);
        // Thiết lập có return
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Thiết lập sử dụng POST
        curl_setopt($ch, CURLOPT_POST, count($data_string));
        // Thiết lập các dữ liệu gửi đi
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public
    static function curlPostGHTK($url = '', $param = [], $token)
    {
        $url = "https://services.giaohangtietkiem.vn/services" . $url;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "$url",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => array(
                "Token:" . $token,
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

    public
    static function curlUrlGHTK($url, $param = [], $token = "", $method = "POST")
    {
        $url = "https://services.giaohangtietkiem.vn/services" . $url;
        $param = json_encode($param);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => $param,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Token: $token"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }

    public
    static function curlUrlGHN($url, $param = [], $token = "", $method = "POST")
    {
        $url = "https://console.ghn.vn/api/v1/apiv3" . $url;
        $param = json_encode($param);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => $param,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Token: $token"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }

    public
    static function curlUrlVTP($url, $param = [], $token = "", $method = "POST")
    {
        $url = "https://api.viettelpost.vn" . $url;
        $param = json_encode($param);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => $param,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Token: $token"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }

/// API giao hàng 247
    public
    static function curlUrlLogin247($param = [])
    {
        $url = "http://stg.247post.vn:30100/token";
        $input = [
            "username" => $param['username'],
            "password" => $param['password'],
            "grant_type" => "password",
        ];
        $input = http_build_query($input);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => $input,
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "content-type: application/x-www-form-urlencoded",
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }

    public
    static function curlUrl247($url, $param = [], $method = "POST", $port = "51100")
    {
        $url = "http://stg.247post.vn:" . $port . $url;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => json_encode($param),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: " . $param['token_type'] . ' ' . $param['access_token'],
                "userName: " . $param['userName']
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

    public
    static function seoURL($text = '', $number = 0, $strtolower = True, $char = '-')
    {
        $text = html_entity_decode(trim($text), ENT_QUOTES, 'UTF-8');
        $text = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $text);
        $text = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $text);
        $text = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $text);
        $text = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $text);
        $text = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $text);
        $text = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $text);
        $text = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $text);
        $text = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $text);
        $text = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $text);
        $text = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $text);
        $text = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $text);
        $text = preg_replace("/(đ)/", 'd', $text);
        $text = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $text);
        $text = preg_replace("/(đ)/", 'd', $text);
        $text = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $text);
        $text = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $text);
        $text = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $text);
        $text = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $text);
        $text = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $text);
        $text = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $text);
        $text = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $text);
        $text = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $text);
        $text = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $text);
        $text = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $text);
        $text = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $text);
        $text = preg_replace("/(Đ)/", 'D', $text);
        $text = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $text);
        $text = preg_replace("/(Đ)/", 'D', $text);
        $text = preg_replace('/[^A-z0-9]/', $char, $text);
        $text = preg_replace('/-{2,}/', $char, $text);
        $text = str_replace("^^", '', $text);
        $text = str_replace("[", "", $text);
        $text = str_replace("]", "", $text);
        $text = trim($text, $char);
        if ($strtolower = true) {
            $text = strtolower($text);
        }
        if ($number > 0) {
            $text = substr($text, 0, $number);
            $text = trim($text, $char);
        }
        return $text;
    }


    public
    static function removeSpecialCharacter($string, $replace = '-')
    {
        // return $string = htmlspecialchars($string,ENT_QUOTES);
        $string = preg_replace('/([^\pL0-9.!@#%&*+?^${}()|\.\ ]+)/u', '', strip_tags($string));
        return preg_replace('/  +/', ' ', $string);
    }

    public
    static function curlGetLazada($client, $api_key, $action, $argument = [])
    {
        $time = time();
        $date = date("Y-m-d\TH:i:sO", time());
        $parameters = array(
            'UserID' => $client,

            'Version' => '1.0',


            'Action' => $action,

            'Format' => 'json',

            'Timestamp' => $date,

            // 'PrimaryCategory' => $primary_id
        );
        if ($argument != []) {
            foreach ($argument as $key => $value) {
                if (is_array($value)) {
                    $parameters[$key] = json_encode($value);
                } else {
                    $parameters[$key] = $value;
                }
            }
        }
        ksort($parameters);
        $encoded = array();
        foreach ($parameters as $name => $value) {
            $encoded[] = rawurlencode($name) . '=' . rawurlencode($value);
        }
        $concatenated = implode('&', $encoded);
        $api_key = $api_key;

        $parameters['Signature'] = rawurlencode(hash_hmac('sha256', $concatenated, $api_key, false));
        $url = "https://api.sellercenter.lazada.vn";

        $queryString = http_build_query($parameters, '', '&', PHP_QUERY_RFC3986);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . "?" . $queryString);
        // Thiết lập có return
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $result = str_replace("'", "", $result);
        curl_close($ch);
        return $result;
    }

    public
    static function curlPostLazada($client, $api_key, $action, $input_xml)
    {
        $time = time();
        $date = date("Y-m-d\TH:i:sO", time());
        $parameters = array(
            'UserID' => $client,

            // The API version. Currently must be 1.0
            'Version' => '1.0',

            // The API method to call.
            'Action' => $action,

            // The format of the result.
            'Format' => 'json',

            // The current time in ISO8601 format
            'Timestamp' => $date,
        );
        ksort($parameters);
        $encoded = array();
        foreach ($parameters as $name => $value) {
            $encoded[] = rawurlencode($name) . '=' . rawurlencode($value);
        }
        // Concatenate the sorted and URL encoded parameters into a string.
        $concatenated = implode('&', $encoded);
        $api_key = $api_key;

        $parameters['Signature'] = rawurlencode(hash_hmac('sha256', $concatenated, $api_key, false));
        $url = "https://api.sellercenter.lazada.vn";

        $queryString = http_build_query($parameters, '', '&', PHP_QUERY_RFC3986);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . "?" . $queryString);
        // Thiết lập có return
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        // Thiết lập sử dụng POST
        // curl_setopt($ch, CURLOPT_POST, count($parameters));


        // Thiết lập các dữ liệu gửi đi
        curl_setopt($ch, CURLOPT_POSTFIELDS, $input_xml);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);

        curl_close($ch);
        $result = str_replace("'", "", $result);
        return $result;
    }

    public
    function curlSendSms($phone, $content)
    {
        $APIKey = "1D57F2FFAE779CE2FA3BA0EA4FE2C8";
        $SecretKey = "081A62BF70992D860BEC14465646F5";
        $SendContent = urlencode($content);
        $YourPhone = $phone;
        $data = "http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?Phone=$YourPhone&ApiKey=$APIKey&SecretKey=$SecretKey&Content=$SendContent&SmsType=4";
        $curl = curl_init($data);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;

    }


//between from to date
    public
    static function createDateRangeArray($strDateFrom, $strDateTo)
    {
        $aryRange = array();
        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
            while ($iDateFrom < $iDateTo) {
                $iDateFrom += 86400; // add 24 hours
                array_push($aryRange, date('Y-m-d', $iDateFrom));
            }
        }
        return $aryRange;
    }
}
