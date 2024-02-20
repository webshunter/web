<?php
namespace Gugusd999\Web;

class ArrayToQuery {
    public static function insert($arrayData = [], $table = 'test', $wht = '') {
        $s = $arrayData;
        if (count($s) > 0) {
            $y = array_keys($s[0]);
            $x = 'INSERT INTO ' . $table . '(' . implode(',', array_map(function ($u) {
                return '`' . $u . '`';
            }, $y)) . ')' . "\n" . 'SELECT ' . implode(',', array_map(function ($g) {
                return 'a.' . $g;
            }, $y)) . ' FROM (';
            $x .= implode("\n UNION ALL \n", array_map(function ($w) use ($y) {
                $f = ' SELECT ' . implode(',', array_map(function ($q) use ($w) {
                    if ($w[$q] !== null) {
                        return '"' . str_replace('"', '\"', $w[$q]) . '" `' . $q . '`';
                    } else {
                        return '"-" `' . $q . '`';
                    }
                }, $y));
                return $f;
            }, $s));
            $x .= ') a';
            if (is_array($wht)) {
                $x .= ' LEFT JOIN ' . $table . ' ON ';
                $x .= implode(' AND ', array_map(function ($whtx) use ($table) {
                    return ' ' . $table . '.' . $whtx . ' = a.' . $whtx;
                }, $wht));
                $x .= ' WHERE ';
                $x .= implode(' AND ', array_map(function ($whtx) use ($table) {
                    return ' ' . $table . '.' . $whtx . ' IS NULL';
                }, $wht));
            }
            return $x;
        } else {
            return [];
        }
    }

    public static function update($arrayData = [], $table = 'test', $wht = 'kode') {
        $s = $arrayData;
        if (count($s) > 0) {
            $y = array_keys($s[0]);
            $x = 'UPDATE ' . $table . ' aa , ( ';
            $x .= 'SELECT ' . implode(',', array_map(function ($g) {
                return 'a.' . $g;
            }, $y)) . ' FROM (';
            $x .= implode("\n UNION ALL \n", array_map(function ($w) use ($y) {
                $f = ' SELECT ' . implode(',', array_map(function ($q) use ($w) {
                    return '"' . str_replace('"', '\"', $w[$q]) . '" `' . $q . '`';
                }, $y));
                return $f;
            }, $s));
            $x .= ') a ) bb SET ';
            $x .= implode(',', array_map(function ($c) {
                return ' aa.' . $c . ' = bb.' . $c;
            }, $y));
            $x .= ' WHERE ';
            if (is_array($wht)) {
                $x .= implode(' AND ', array_map(function ($whtx) {
                    return ' aa.' . $whtx . ' = bb.' . $whtx;
                }, $wht));
            } else {
                $x .= ' aa.' . $wht . ' = bb.' . $wht;
            }
            return $x;
        } else {
            return '';
        }
    }
}
