<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\helpers;

use ArrayAccess;
use Closure;
use Iterator;
use InvalidArgumentException;
use BadMethodCallException;

/**
 * Class ArrayHelper.
 */
class ArrayHelper extends \yii\helpers\ArrayHelper
{
    /**
     * Gets a dot-notated key from an array, with a default value if it does not exist.
     * @param   array  $array   the search array.
     * @param   mixed  $key     the dot-notated key or array of keys.
     * @param   string $default the default value.
     * @return  mixed
     */
    public static function get($array, $key, $default = null)
    {
        if (!is_array($array) and !$array instanceof ArrayAccess) {
            throw new InvalidArgumentException('First parameter must be an array or ArrayAccess object.');
        }
        if (is_null($key)) {
            return $array;
        }
        if (is_array($key)) {
            $return = [];
            foreach ($key as $k) {
                $return[$k] = static::get($array, $k, $default);
            }
            return $return;
        }

        is_object($key) and $key = (string) $key;

        if (array_key_exists($key, $array)) {
            return $array[$key];
        }
        foreach (explode('.', $key) as $keyPart) {
            if (($array instanceof ArrayAccess and isset($array[$keyPart])) === false) {
                if (!is_array($array) or !array_key_exists($keyPart, $array)) {
                    return ($default instanceof Closure) ? $default() : $default;
                }
            }
            $array = $array[$keyPart];
        }
        return $array;
    }

    /**
     * Set an array item (dot-notated) to the value.
     * @param   array  $array the array to insert it into.
     * @param   mixed  $key   the dot-notated key to set or array of keys.
     * @param   mixed  $value the value.
     * @return  void
     */
    public static function set(&$array, $key, $value = null)
    {
        if (is_null($key)) {
            $array = $value;
            return;
        }
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                static::set($array, $k, $v);
            }
        } else {
            $keys = explode('.', $key);
            while (count($keys) > 1) {
                $key = array_shift($keys);
                if (!isset($array[$key]) or !is_array($array[$key])) {
                    $array[$key] = [];
                }
                $array =& $array[$key];
            }
            $array[array_shift($keys)] = $value;
        }
    }

    /**
     * Pluck an array of values from an array.
     * @param  array   $array  collection of arrays to pluck from.
     * @param  string  $key    key of the value to pluck.
     * @param  string  $index  optional return array index key, true for original index.
     * @return array array of plucked values.
     */
    public static function pluck($array, $key, $index = null)
    {
        $return = [];
        $get_deep = strpos($key, '.') !== false;
        if (!$index) {
            foreach ($array as $i => $a) {
                $return[] = (is_object($a) and !($a instanceof ArrayAccess)) ? $a->{$key} :
                    ($get_deep ? static::get($a, $key) : $a[$key]);
            }
        } else {
            foreach ($array as $i => $a) {
                $index !== true and $i = (is_object($a) and ! ($a instanceof ArrayAccess)) ? $a->{$index} : $a[$index];
                $return[$i] = (is_object($a) and ! ($a instanceof ArrayAccess)) ? $a->{$key} :
                    ($get_deep ? static::get($a, $key) : $a[$key]);
            }
        }
        return $return;
    }

    /**
     * Array_key_exists with a dot-notated key from an array.
     * @param   array  $array the search array.
     * @param   mixed  $key   the dot-notated key or array of keys.
     * @return  mixed
     */
    public static function key_exists($array, $key)
    {
        if (!is_array($array) and !$array instanceof ArrayAccess) {
            throw new InvalidArgumentException('First parameter must be an array or ArrayAccess object.');
        }

        is_object($key) and $key = (string) $key;

        if (!is_string($key)) {
            return false;
        }
        if (array_key_exists($key, $array)) {
            return true;
        }
        foreach (explode('.', $key) as $key_part) {
            if (($array instanceof ArrayAccess and isset($array[$key_part])) === false) {
                if (!is_array($array) or ! array_key_exists($key_part, $array)) {
                    return false;
                }
            }
            $array = $array[$key_part];
        }
        return true;
    }

    /**
     * Unsets dot-notated key from an array.
     * @param   array  $array the search array.
     * @param   mixed  $key   the dot-notated key or array of keys.
     * @return  mixed
     */
    public static function delete(&$array, $key)
    {
        if (is_null($key)) {
            return false;
        }
        if (is_array($key)) {
            $return = [];
            foreach ($key as $k) {
                $return[$k] = static::delete($array, $k);
            }
            return $return;
        }

        $keyParts = explode('.', $key);

        if (!is_array($array) or !array_key_exists($keyParts[0], $array)) {
            return false;
        }

        $thisKey = array_shift($keyParts);

        if (!empty($keyParts)) {
            $key = implode('.', $keyParts);
            return static::delete($array[$thisKey], $key);
        } else {
            unset($array[$thisKey]);
        }
        return true;
    }

    /**
     * Converts a multi-dimensional associative array into an array of key => values with the provided field names.
     * @param   array   $assoc    the array to convert.
     * @param   string  $keyField the field name of the key field.
     * @param   string  $valField the field name of the value field.
     * @return  array
     * @throws  InvalidArgumentException
     */
    public static function assoc_to_keyval($assoc, $keyField, $valField)
    {
        if (!is_array($assoc) and ! $assoc instanceof Iterator) {
            throw new InvalidArgumentException('The first parameter must be an array.');
        }
        $output = [];
        foreach ($assoc as $row) {
            if (isset($row[$keyField]) and isset($row[$valField])) {
                $output[$row[$keyField]] = $row[$valField];
            }
        }
        return $output;
    }

    /**
     * Converts an array of key => values into a multi-dimensional associative array with the provided field names.
     * @param   array   $array     the array to convert.
     * @param   string  $key_field the field name of the key field.
     * @param   string  $val_field the field name of the value field.
     * @return  array
     * @throws  InvalidArgumentException
     */
    public static function keyval_to_assoc($array, $key_field, $val_field)
    {
        if (!is_array($array) and !$array instanceof Iterator) {
            throw new \InvalidArgumentException('The first parameter must be an array.');
        }
        $output = [];
        foreach ($array as $key => $value) {
            $output[] = array(
                $key_field => $key,
                $val_field => $value,
            );
        }
        return $output;
    }

    /**
     * Converts the given 1 dimensional non-associative array to an associative array.
     * The array given must have an even number of elements or null will be returned.
     *
     *     Arr::to_assoc(array('foo','bar'));
     *
     * @param  string $arr the array to change.
     * @return array|null  the new array or null
     * @throws BadMethodCallException
     */
    public static function to_assoc($arr)
    {
        if (($count = count($arr)) % 2 > 0) {
            throw new BadMethodCallException('Number of values in to_assoc must be even.');
        }
        $keys = $vals = [];
        for ($i = 0; $i < $count - 1; $i += 2) {
            $keys[] = array_shift($arr);
            $vals[] = array_shift($arr);
        }
        return array_combine($keys, $vals);
    }

    /**
     * Checks if the given array is an assoc array.
     * @param  array $arr the array to check.
     * @return bool true if its an assoc array, false if not
     */
    public static function is_assoc($arr)
    {
        if (!is_array($arr)) {
            throw new InvalidArgumentException('The parameter must be an array.');
        }
        $counter = 0;
        foreach ($arr as $key => $unused) {
            if (!is_int($key) or $key !== $counter++) {
                return true;
            }
        }
        return false;
    }

    /**
     * Flattens a multi-dimensional associative array down into a 1 dimensional associative array.
     * @param   array  $array   the array to flatten.
     * @param   string $glue    what to glue the keys together with.
     * @param   bool   $reset   whether to reset and start over on a new array.
     * @param   bool   $indexed whether to flatten only associative array's, or also indexed ones.
     * @return  array
     */
    public static function flatten($array, $glue = ':', $reset = true, $indexed = true)
    {
        static $return   = [];
        static $curr_key = [];

        if ($reset) {
            $return   = [];
            $curr_key = [];
        }
        foreach ($array as $key => $val) {
            $curr_key[] = $key;
            if (is_array($val) and ($indexed or array_values($val) !== $val)) {
                static::flatten($val, $glue, false, $indexed);
            } else {
                $return[implode($glue, $curr_key)] = $val;
            }
            array_pop($curr_key);
        }
        return $return;
    }

    /**
     * Flattens a multi-dimensional associative array down into a 1 dimensional associative array.
     * @param   array   $array the array to flatten.
     * @param   string  $glue  what to glue the keys together with.
     * @param   bool    $reset whether to reset and start over on a new array.
     * @return  array
     */
    public static function flatten_assoc($array, $glue = ':', $reset = true)
    {
        return static::flatten($array, $glue, $reset, false);
    }

    /**
     * Reverse a flattened array in its original form.
     * @param   array  $array flattened array.
     * @param   string $glue  glue used in flattening.
     * @return  array the unflattened array
     */
    public static function reverse_flatten($array, $glue = ':')
    {
        $return = [];
        foreach ($array as $key => $value) {
            if (stripos($key, $glue) !== false) {
                $keys = explode($glue, $key);
                $temp =& $return;
                while (count($keys) > 1) {
                    $key = array_shift($keys);
                    $key = is_numeric($key) ? (int) $key : $key;
                    if (!isset($temp[$key]) or !is_array($temp[$key])) {
                        $temp[$key] = [];
                    }
                    $temp =& $temp[$key];
                }
                $key = array_shift($keys);
                $key = is_numeric($key) ? (int) $key : $key;
                $temp[$key] = $value;
            } else {
                $key = is_numeric($key) ? (int) $key : $key;
                $return[$key] = $value;
            }
        }
        return $return;
    }

    /**
     * Filters an array on prefixed associative keys.
     * @param   array   $array        the array to filter.
     * @param   string  $prefix       prefix to filter on.
     * @param   bool    $removePrefix whether to remove the prefix.
     * @return  array
     */
    public static function filter_prefixed($array, $prefix, $removePrefix = true)
    {
        $return =[];
        foreach ($array as $key => $val) {
            if (preg_match('/^'.$prefix.'/', $key)) {
                if ($removePrefix === true) {
                    $key = preg_replace('/^'.$prefix.'/','',$key);
                }
                $return[$key] = $val;
            }
        }
        return $return;
    }

    /**
     * Recursive version of PHP's array_filter().
     * @param   array     $array    the array to filter.
     * @param   callback  $callback the callback that determines whether or not a value is filtered.
     * @return  array
     */
    public static function filter_recursive($array, $callback = null)
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = $callback === null ? static::filter_recursive($value) :
                    static::filter_recursive($value, $callback);
            }
        }
        return $callback === null ? array_filter($array) : array_filter($array, $callback);
    }

    /**
     * Filters an array on prefixed associative keys.
     * @param   array   $array  the array to filter.
     * @param   string  $prefix prefix to filter on.
     * @return  array
     */
    public static function remove_prefixed($array, $prefix)
    {
        foreach ($array as $key => $val) {
            if (preg_match('/^'.$prefix.'/', $key)) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    /**
     * Filters an array on suffixed associative keys.
     * @param   array   $array          the array to filter.
     * @param   string  $suffix         suffix to filter on.
     * @param   bool    $remove_suffix  whether to remove the suffix.
     * @return  array
     */
    public static function filter_suffixed($array, $suffix, $remove_suffix = true)
    {
        $return = [];
        foreach ($array as $key => $val) {
            if (preg_match('/'.$suffix.'$/', $key)) {
                if ($remove_suffix === true) {
                    $key = preg_replace('/'.$suffix.'$/', '', $key);
                }
                $return[$key] = $val;
            }
        }
        return $return;
    }

    /**
     * Removes items from an array that match a key suffix.
     * @param   array   $array   the array to remove from
     * @param   string  $suffix  suffix to filter on
     * @return  array
     */
    public static function remove_suffixed($array, $suffix)
    {
        foreach ($array as $key => $val) {
            if (preg_match('/'.$suffix.'$/', $key)) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    /**
     * Filters an array by an array of keys.
     * @param   array  $array  the array to filter.
     * @param   array  $keys   the keys to filter
     * @param   bool   $remove if true, removes the matched elements.
     * @return  array
     */
    public static function filter_keys($array, $keys, $remove = false)
    {
        $return = [];
        foreach ($keys as $key) {
            if (array_key_exists($key, $array)) {
                $remove or $return[$key] = $array[$key];
                if($remove) {
                    unset($array[$key]);
                }
            }
        }
        return $remove ? $array : $return;
    }

    /**
     * Insert value(s) into an array, mostly an array_splice alias
     * WARNING: original array is edited by reference, only boolean success is returned.
     * @param   array        $original the original array (by reference).
     * @param   array|mixed  $value    the value(s) to insert, if you want to insert an array it needs to be in an array itself.
     * @param   int          $pos      the numeric position at which to insert, negative to count from the end backwards.
     * @return  bool false when array shorter then $pos, otherwise true
     */
    public static function insert(array &$original, $value, $pos)
    {
        if (count($original) < abs($pos)) {
            return false;
        }
        array_splice($original, $pos, 0, $value);
        return true;
    }

    /**
     * Insert value(s) into an array, mostly an array_splice alias
     * WARNING: original array is edited by reference, only boolean success is returned.
     * @param   array        $original the original array (by reference).
     * @param   array|mixed  $values   the value(s) to insert, if you want to insert an array it needs to be in an array itself.
     * @param   int          $pos      the numeric position at which to insert, negative to count from the end backwards.
     * @return  bool false when array shorter then $pos, otherwise true
     */
    public static function insert_assoc(array &$original, array $values, $pos)
    {
        if (count($original) < abs($pos)) {
            return false;
        }
        $original = array_slice($original, 0, $pos, true) + $values + array_slice($original, $pos, null, true);
        return true;
    }

    /**
     * Insert value(s) into an array before a specific key
     * WARNING: original array is edited by reference, only boolean success is returned.
     * @param   array        $original the original array (by reference).
     * @param   array|mixed  $value    the value(s) to insert, if you want to insert an array it needs to be in an array itself.
     * @param   string|int   $key      the key before which to insert.
     * @param   bool         $isAssoc  whether the input is an associative array.
     * @return  bool false when key isn't found in the array, otherwise true
     */
    public static function insert_before_key(array &$original, $value, $key, $isAssoc = false)
    {
        $pos = array_search($key, array_keys($original));
        if ($pos === false) {
            return false;
        }
        return $isAssoc ? static::insert_assoc($original, $value, $pos) : static::insert($original, $value, $pos);
    }

    /**
     * Insert value(s) into an array after a specific key
     * WARNING: original array is edited by reference, only boolean success is returned.
     * @param   array        $original the original array (by reference).
     * @param   array|mixed  $value    the value(s) to insert, if you want to insert an array it needs to be in an array itself.
     * @param   string|int   $key      the key after which to insert.
     * @param   bool         $isAssoc  whether the input is an associative array.
     * @return  bool false when key isn't found in the array, otherwise true
     */
    public static function insert_after_key(array &$original, $value, $key, $isAssoc = false)
    {
        $pos = array_search($key, array_keys($original));
        if ($pos === false) {
            return false;
        }
        return $isAssoc ? static::insert_assoc($original, $value, $pos + 1) : static::insert($original, $value, $pos + 1);
    }

    /**
     * Insert value(s) into an array after a specific value (first found in array).
     * @param   array        $original the original array (by reference).
     * @param   array|mixed  $value    the value(s) to insert, if you want to insert an array it needs to be in an array itself.
     * @param   string|int   $search   the Value after which to insert.
     * @param   bool         $isAssoc  whether the input is an associative array.
     * @return  bool false when value isn't found in the array, otherwise true
     */
    public static function insert_after_value(array &$original, $value, $search, $isAssoc = false)
    {
        $key = array_search($search, $original);
        if ($key === false) {
            return false;
        }
        return static::insert_after_key($original, $value, $key, $isAssoc);
    }

    /**
     * Insert value(s) into an array before a specific value (first found in array).
     * @param   array        $original the original array (by reference).
     * @param   array|mixed  $value    the value(s) to insert, if you want to insert an array it needs to be in an array itself.
     * @param   string|int   $search   the value after which to insert.
     * @param   bool         $isAssoc  wether the input is an associative array.
     * @return  bool false when value isn't found in the array, otherwise true
     */
    public static function insert_before_value(array &$original, $value, $search, $isAssoc = false)
    {
        $key = array_search($search, $original);
        if ($key === false) {
            return false;
        }
        return static::insert_before_key($original, $value, $key, $isAssoc);
    }

    /**
     * Sorts a multi-dimensional array by it's values.
     * @access	public
     * @param	array	$array     the array to fetch from.
     * @param	string	$key       the key to sort by.
     * @param	string	$order     the order (asc or desc).
     * @param	int		$sortFlags the php sort type flag.
     * @return	array
     */
    public static function sort($array, $key, $order = 'asc', $sortFlags = SORT_REGULAR)
    {
        if (!is_array($array)) {
            throw new InvalidArgumentException('Arr::sort() - $array must be an array.');
        }
        if (empty($array)) {
            return $array;
        }
        foreach ($array as $k => $v) {
            $b[$k] = static::get($v, $key);
        }
        switch ($order) {
            case 'asc':
                asort($b, $sortFlags);
                break;
            case 'desc':
                arsort($b, $sortFlags);
                break;
            default:
                throw new InvalidArgumentException('Arr::sort() - $order must be asc or desc.');
                break;
        }
        foreach ($b as $key => $val) {
            $c[] = $array[$key];
        }
        return $c;
    }

    /**
     * Find the average of an array
     * @param   array   $array  the array containing the values
     * @return  number the average value
     */
    public static function average($array)
    {
        // No arguments passed, lets not divide by 0
        if ( ! ($count = count($array)) > 0) {
            return 0;
        }
        return (array_sum($array) / $count);
    }

    /**
     * Replaces key names in an array by names in $replace.
     * @param   array		  $source  the array containing the key/value combinations.
     * @param   array|string  $replace key to replace or array containing the replacement keys.
     * @param   string		  $new_key the replacement key.
     * @return  array the array with the new keys
     */
    public static function replace_key($source, $replace, $new_key = null)
    {
        if(is_string($replace)) {
            $replace = array($replace => $new_key);
        }
        if (!is_array($source) or ! is_array($replace)) {
            throw new InvalidArgumentException('Arr::replaceKey() - $source must an array. $replace must be an array or string.');
        }
        $result = [];
        foreach ($source as $key => $value) {
            if (array_key_exists($key, $replace)) {
                $result[$replace[$key]] = $value;
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    /**
     * Merge 2 arrays recursively, differs in 2 important ways from array_merge_recursive().
     * - When there's 2 different values and not both arrays, the latter value overwrites the earlier
     *   instead of merging both into an array.
     * - Numeric keys are never changed.
     * @return  array
     * @throws  InvalidArgumentException
     */
    public static function merge_assoc()
    {
        $array  = func_get_arg(0);
        $arrays = array_slice(func_get_args(), 1);
        if (!is_array($array)) {
            throw new \InvalidArgumentException('Arr::merge_assoc() - all arguments must be arrays.');
        }
        foreach ($arrays as $arr) {
            if ( ! is_array($arr)) {
                throw new InvalidArgumentException('Arr::merge_assoc() - all arguments must be arrays.');
            }
            foreach ($arr as $k => $v) {
                if (is_array($v) and array_key_exists($k, $array) and is_array($array[$k])) {
                    $array[$k] = static::merge_assoc($array[$k], $v);
                } else {
                    $array[$k] = $v;
                }
            }
        }
        return $array;
    }

    /**
     * Prepends a value with an asociative key to an array. Will overwrite if the value exists.
     * @param   array         $arr   the array to prepend to.
     * @param   string|array  $key   the key or array of keys and values.
     * @param   mixed         $value the value to prepend
     */
    public static function prepend(&$arr, $key, $value = null)
    {
        $arr = [is_array($key) ? $key : [$key => $value]] + $arr;
    }

    /**
     * Recursive in_array.
     * @param   mixed  $needle   what to search for.
     * @param   array  $haystack array to search in.
     * @return  bool whether the needle is found in the haystack
     */
    public static function in_array_recursive($needle, $haystack, $strict = false)
    {
        foreach ($haystack as $value) {
            if (!$strict and $needle == $value) {
                return true;
            } elseif ($needle === $value) {
                return true;
            } elseif (is_array($value) and static::in_array_recursive($needle, $value, $strict)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks if the given array is a multidimensional array.
     * @param   array  $arr     the array to check.
     * @param   bool   $allKeys if true, check that all elements are arrays.
     * @return  bool true if its a multidimensional array, false if not
     */
    public static function is_multi($arr, $allKeys = false)
    {
        $values = array_filter($arr, 'is_array');
        return $allKeys ? count($arr) === count($values) : count($values) > 0;
    }

    /**
     * Searches the array for a given value and returns the corresponding key or default value.
     * If $recursive is set to true, then the Arr::search() function will return a delimiter-notated key using $delimiter.
     * @param   array   $array     the search array
     * @param   mixed   $value     the searched value
     * @param   string  $default   the default value
     * @param   bool    $recursive whether to get keys recursive
     * @param   string  $delimiter the delimiter, when $recursive is true
     * @param   bool    $strict    if true, do a strict key comparison
     * @return  mixed
     */
    public static function search($array, $value, $default = null, $recursive = true, $delimiter = '.', $strict = false)
    {
        if (!is_array($array) and ! $array instanceof ArrayAccess) {
            throw new InvalidArgumentException('First parameter must be an array or ArrayAccess object.');
        }
        if (!is_null($default) and !is_int($default) and !is_string($default)) {
            throw new InvalidArgumentException('Expects parameter 3 to be an string or integer or null.');
        }
        if (!is_string($delimiter)) {
            throw new \InvalidArgumentException('Expects parameter 5 must be an string.');
        }
        $key = array_search($value, $array, $strict);
        if ($recursive and $key === false) {
            $keys =[];
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    $rk = static::search($v, $value, $default, true, $delimiter, $strict);
                    if ($rk !== $default) {
                        $keys = [$k, $rk];
                        break;
                    }
                }
            }
            $key = count($keys) ? implode($delimiter, $keys) : false;
        }
        return $key === false ? ($default instanceof Closure ? $default() : $default) : $key;
    }

    /**
     * Returns only unique values in an array. It does not sort. First value is used.
     * @param  array  $arr the array to dedup.
     * @return array array with only de-duped values
     */
    public static function unique($arr)
    {
        // filter out all duplicate values
        return array_filter($arr, function($item) {
            // contrary to popular belief, this is not as static as you think...
            static $vars = [];
            if (in_array($item, $vars, true)) {
                // duplicate
                return false;
            } else {
                // record we've had this value
                $vars[] = $item;
                // unique
                return true;
            }
        });
    }

    /**
     * Calculate the sum of an array.
     * @param   array   $array  the array containing the values.
     * @param   string  $key    key of the value to pluck.
     * @return  number the sum value
     */
    public static function sum($array, $key)
    {
        if (!is_array($array) and !$array instanceof \ArrayAccess) {
            throw new InvalidArgumentException('First parameter must be an array or ArrayAccess object.');
        }
        return array_sum(static::pluck($array, $key));
    }

    /**
     * Returns the array with all numeric keys re-indexed, and string keys untouched
     * @param  array  $arr the array to reindex
     * @return array re-indexed array
     */
    public static function reindex($arr)
    {
        // reindex this level
        $arr = array_merge($arr);
        foreach ($arr as $k => &$v) {
            is_array($v) and $v = static::reindex($v);
        }
        return $arr;
    }

    /**
     * Get the previous value or key from an array using the current array key.
     * @param   array    $array      the array containing the values.
     * @param   string   $key        key of the current entry to use as reference.
     * @param   bool     $get_value  if true, return the previous value instead of the previous key.
     * @param   bool     $strict     if true, do a strict key comparison.
     * @return  mixed the value in the array, null if there is no previous value, or false if the key doesn't exist
     */
    public static function previous_by_key($array, $key, $get_value = false, $strict = false)
    {
        if (!is_array($array) and !$array instanceof \ArrayAccess) {
            throw new InvalidArgumentException('First parameter must be an array or ArrayAccess object.');
        }
        // get the keys of the array
        $keys = array_keys($array);
        // and do a lookup of the key passed
        if (($index = array_search($key, $keys, $strict)) === false) {
            return false; // key does not exist
        } elseif (!isset($keys[$index-1])) {  // check if we have a previous key
            return null; // there is none
        }
        // return the value or the key of the array entry the previous key points to
        return $get_value ? $array[$keys[$index-1]] : $keys[$index-1];
    }

    /**
     * Get the next value or key from an array using the current array key.
     * @param   array   $array      the array containing the values.
     * @param   string  $key        key of the current entry to use as reference.
     * @param   bool    $get_value  if true, return the next value instead of the next key.
     * @param   bool    $strict     if true, do a strict key comparison.
     * @return  mixed the value in the array, null if there is no next value, or false if the key doesn't exist
     */
    public static function next_by_key($array, $key, $get_value = false, $strict = false)
    {
        if (!is_array($array) and !$array instanceof ArrayAccess) {
            throw new InvalidArgumentException('First parameter must be an array or ArrayAccess object.');
        }
        // get the keys of the array
        $keys = array_keys($array);
        // and do a lookup of the key passed
        if (($index = array_search($key, $keys, $strict)) === false) {
            // key does not exist
            return false;
        } elseif ( ! isset($keys[$index+1])) { // check if we have a previous key
            // there is none
            return null;
        }
        // return the value or the key of the array entry the previous key points to
        return $get_value ? $array[$keys[$index+1]] : $keys[$index+1];
    }

    /**
     * Get the previous value or key from an array using the current array value.
     * @param   array    $array      the array containing the values.
     * @param   string   $value      value of the current entry to use as reference.
     * @param   bool     $get_value  if true, return the previous value instead of the previous key.
     * @param   bool     $strict     if true, do a strict key comparison.
     * @return  mixed the value in the array, null if there is no previous value, or false if the key doesn't exist
     */
    public static function previous_by_value($array, $value, $get_value = true, $strict = false)
    {
        if ( ! is_array($array) and !$array instanceof ArrayAccess) {
            throw new InvalidArgumentException('First parameter must be an array or ArrayAccess object.');
        }
        // find the current value in the array
        if (($key = array_search($value, $array, $strict)) === false) {
            // bail out if not found
            return false;
        }
        // get the list of keys, and find our found key
        $keys  = array_keys($array);
        $index = array_search($key, $keys);
        // if there is no previous one, bail out
        if ( ! isset($keys[$index-1])) {
            return null;
        }
        // return the value or the key of the array entry the previous key points to
        return $get_value ? $array[$keys[$index-1]] : $keys[$index-1];
    }

    /**
     * Get the next value or key from an array using the current array value.
     * @param   array    $array      the array containing the values.
     * @param   string   $value      value of the current entry to use as reference.
     * @param   bool     $get_value  if true, return the next value instead of the next key.
     * @param   bool     $strict     if true, do a strict key comparison.
     * @return  mixed the value in the array, null if there is no next value, or false if the key doesn't exist
     */
    public static function next_by_value($array, $value, $get_value = true, $strict = false)
    {
        if (!is_array($array) and !$array instanceof ArrayAccess) {
            throw new InvalidArgumentException('First parameter must be an array or ArrayAccess object.');
        }
        // find the current value in the array
        if (($key = array_search($value, $array, $strict)) === false) {
            // bail out if not found
            return false;
        }
        // get the list of keys, and find our found key
        $keys  = array_keys($array);
        $index = array_search($key, $keys);
        // if there is no next one, bail out
        if (!isset($keys[$index+1])) {
            return null;
        }
        // return the value or the key of the array entry the next key points to
        return $get_value ? $array[$keys[$index+1]] : $keys[$index+1];
    }

    /**
     * Return the subset of the array defined by the supplied keys.
     * Returns $default for missing keys, as with Arr::get().
     * @param   array    $array    the array containing the values.
     * @param   array    $keys     list of keys (or indices) to return.
     * @param   mixed    $default  value of missing keys; default null.
     * @return  array an array containing the same set of keys provided
     */
    public static function subset(array $array, array $keys, $default = null)
    {
        $result = array();
        foreach ($keys as $key) {
            static::set($result, $key, static::get($array, $key, $default));
        }
        return $result;
    }

    /**
     * Get the number of elements in an array on a key.
     * @param  array $array the search array.
     * @param  mixed $key   the dot-notated key or array of keys.
     * @return int
     */
    public static function count(array $array, $key){
        return count(static::get($array, $key, []));
    }

    /**
     * Associative analogue str_replace.
     * @param array  $replace
     * @param string $subject
     * @return string
     */
    public static function replaceAssoc(array $replace, string $subject){
        return str_replace(array_keys($replace), array_values($replace), $subject);
    }

}