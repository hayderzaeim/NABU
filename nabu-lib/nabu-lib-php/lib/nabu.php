<?php
/**
 * @class NABU
 */
class NABU {
    /**
     * @param {array|string} $dataCode
     * @param {array} $attributes
     * @return void
     */
    public static function head($dataCode, $attributes) {
        $parentElement = "<head>";
        $this->import($attributes, $parentElement, 'attributes');
        $this->import($dataCode, $parentElement);
    }
  
    /**
     * @param {array|string} $dataCode
     * @param {array} $attributes
     * @return void
     */
    public static function body($dataCode, $attributes) {
        $parentElement = "<body>";
        $this->import($attributes, $parentElement, 'attributes');
        $this->import($dataCode, $parentElement, 'create');
    }
  
    /**
     * @param {array} $dataPages
     * @return void
     */
    public static function pages($dataPages) {
        $fragment = ltrim($_SERVER['REQUEST_URI'], '/');
        if ($fragment === '') {
            $this->importPages($dataPages, 'home');
        } else {
            $valueCh = isset($dataPages[$fragment]) ? $dataPages[$fragment][0] : 'null';
            if ($valueCh !== 'null') {
                $this->importPages($dataPages, $fragment);
            } else {
                $valueError = isset($dataPages['page404']) ? $dataPages['page404'][0] : 'null';
                if ($valueError !== 'null') {
                    echo '1---';
                    $this->importPages($dataPages, 'page404');
                } else {
                    echo '2---';
                    $this->importPages(['page404' => ['./404.js', 'page404']], 'page404');
                }
            }

        }    }
  
    /**
     * @param {array} $dataPages
     * @param {string} $fragment
     * @return void
     */
    public static function importPages($dataPages, $fragment) {
        foreach ($dataPages as $key => $value) {
            if ($key === $fragment) {
                $path = $value[0];
                $nameFun = $value[1];
                require_once $path;
                if (function_exists($nameFun)) {
                    $nameFun();
                } else {
                    echo $nameFun . ' This function not found...!';
                }
            }
        }
    }
  
    /**
     * @param {string} $path
     * @param {string} $nameFun
     * @return mixed
     */
    public static function getFiler($path, $nameFun) {
        require_once $path;
        return $nameFun();    }
  
    /**
     * @param {mixed} $data
     * @return mixed
     */
    public static function child($data) {
        return $data;    }
  
    /**
     * @param {mixed} $data
     * @return mixed
     */
    public static function style($data) {
        return $data;
    }
  
    /**
     * @param {mixed} $data
     * @return mixed
     */
    public static function element($data) {
        return $data;
    }
  
    /**
     * @param {array|string} $dataCode
     * @param {string} $parentElement
     * @param {string} $typeData
     * @return void
     */
    public static function import($dataCode, $parentElement, $typeData = '') {
        require_once './NJSCreateElements.php';

        $MyElementCreator = new ElementCreator();

        if ($typeData === 'attributes') {
            $MyElementCreator->NJS_setAttributes($dataCode, $parentElement);
        } else {
            $MyElementCreator->createElements($dataCode, $parentElement);
        }
    }
    }
}
?>
