<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use ZipArchive;
use \RecursiveDirectoryIterator;
// namespace mynamespace;
use RecursiveIteratorIterator;
use RecursiveArrayIterator;
use App\Models\GeneralModel;

class General_helper
{

    public static function tanggalwoah($date = '')
    {
        if (empty($date) || $date == '0000-00-00 00:00:00') {
            return '-';
        }
        $BulanIndo = array(
            "Januari", "Februari", "Maret",
            "April", "Mei", "Juni",
            "Juli", "Agustus", "September",
            "Oktober", "November", "Desember"
        );

        $tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
        $bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
        $tgl   = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring

        $jam = date('H:i', strtotime($date));
        $result = $tgl . " " . $BulanIndo[(int)$bulan - 1] . " " . $tahun . " " . $jam . ' WIB';
        return ($result);
    }

    public static function tanggalwow($date = '')
    {
        if (empty($date) || $date == '0000-00-00 00:00:00') {
            return '-';
        }
        $BulanIndo = array(
            "Januari", "Februari", "Maret",
            "April", "Mei", "Juni",
            "Juli", "Agustus", "September",
            "Oktober", "November", "Desember"
        );

        $tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
        $bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
        $tgl   = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring

        $jam = date('H:i', strtotime($date));
        $result = $tgl . " " . $BulanIndo[(int)$bulan - 1] . " " . $tahun;
        return ($result);
    }

    public static function gen_pass($length = 6)
    {
        $characters = '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function uang($uang)
    {
        $h = 'Rp' . number_format($uang, 0, '.', '.');
        return $h;
    }

    public static function full_copy($source, $target)
    {
        if (is_dir($source)) {
            @mkdir($target);
            $d = dir($source);
            while (FALSE !== ($entry = $d->read())) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }
                $Entry = $source . '/' . $entry;
                if (is_dir($Entry)) {
                    \Helper::full_copy($Entry, $target . '/' . $entry);
                    continue;
                }
                copy($Entry, $target . '/' . $entry);
            }

            $d->close();
        } else {
            copy($source, $target);
        }
    }

    public static function Zip($source, $destination, $include_dir = false)
    {
        error_reporting(0);
        if (!extension_loaded('zip') || !file_exists($source)) {
            return false;
        }
        if (file_exists($destination)) {
            unlink($destination);
        }

        $zip = new ZipArchive();
        if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
            return false;
        }
        $source = str_replace('\\', '/', realpath($source));

        if (is_dir($source) === true) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
            if ($include_dir) {
                $arr = explode("/", $source);
                $maindir = $arr[count($arr) - 1];
                $source = "";
                for ($i = 0; $i < count($arr) - 1; $i++) {
                    $source .= '/' . $arr[$i];
                }
                $source = substr($source, 1);
                $zip->addEmptyDir($maindir);
            }

            foreach ($files as $file) {
                $file = str_replace('\\', '/', $file);
                // Ignore "." and ".." folders
                if (in_array(substr($file, strrpos($file, '/') + 1), array('.', '..')))
                    continue;

                $file = realpath($file);

                if (is_dir($file) === true) {
                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                } else if (is_file($file) === true) {
                    $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                }
            }
        } else if (is_file($source) === true) {
            $zip->addFromString(basename($source), file_get_contents($source));
        }

        return $zip->close();
    }

    public static function get_title_yt($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://noembed.com/embed?dataType=json&url=' . $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public static function insert_report($amount, $type, $id){
        $saldo_db = GeneralModel::getRow('m_saldo', '*');
        $saldo_before = $saldo_db ? $saldo_db->saldo : 0;
        if($type == 'income'){
            $transaction_last = generalModel::getRow('tb_income', '*', 'ORDER BY id DESC LIMIT 1');
            $saldo_after = $saldo_before + $amount;
        } else {
            $transaction_last = generalModel::getRow('tb_expenditure', '*', 'ORDER BY id DESC LIMIT 1');
            $saldo_after = $saldo_before - $amount;
        }
        $data = [
            'id_transaction' => $id,
            'transaction_date' => $transaction_last->transaction_date.' '.$transaction_last->transaction_time,
            'transaction_type' => $type,
            'expenditure' => $type == 'expenditure' ? $amount : 0,
            'income' => $type == 'income' ? $amount : 0,
            'saldo' => $saldo_after,
            'created_at' => date('Y-m-d H:i:s')
        ];
        GeneralModel::setInsert('tb_report', $data);
        $val_saldo = GeneralModel::getRow('m_saldo', '*');
        if(empty($val_saldo)){
            GeneralModel::setInsert('m_saldo', [
                'saldo' => $saldo_after,
                'created_at' => date('Y-m-d H:i:s')
            ]); 
        } else {
            GeneralModel::setUpdate('m_saldo', ['saldo' => $saldo_after], ['id' => $val_saldo->id]);
        }
    }

}
