<?php
use server\File;
function upload($name = '')
{
    $files = isset($_FILES) ? $_FILES : [];
    if (!empty($files)) {
        // 处理上传文件
        $array = [];
        foreach ($files as $key => $file) {
            if (is_array($file['name'])) {
                $item  = [];
                $keys  = array_keys($file);
                $count = count($file['name']);
                for ($i = 0; $i < $count; $i++) {
                    if (empty($file['tmp_name'][$i])) {
                        continue;
                    }
                    $temp['key'] = $key;
                    foreach ($keys as $_key) {
                        $temp[$_key] = $file[$_key][$i];
                    }
                    $item[] = (new File($temp['tmp_name']))->setUploadInfo($temp);
                }
                $array[$key] = $item;
            } else {
                if ($file instanceof File) {
                    $array[$key] = $file;
                } else {
                    if (empty($file['tmp_name'])) {
                        continue;
                    }
                    $array[$key] = (new File($file['tmp_name']))->setUploadInfo($file);
                }
            }
        }
        if (strpos($name, '.')) {
            list($name, $sub) = explode('.', $name);
        }
        if ('' === $name) {
            // 获取全部文件
            return $array;
        } elseif (isset($sub) && isset($array[$name][$sub])) {
            return $array[$name][$sub];
        } elseif (isset($array[$name])) {
            return $array[$name];
        }
    }
    return null;
}
?>