<?php

/**
 * @desc  验证器调用
 * @param string $request 验证器类
 * @param string $scene 场景名
 * @param array $data 验证数据
 * @return
 */
function validate(string $request, string $scene = '', array $data = []): void
{
    app($request)->validate($scene, $data);
}


/**
 * @desc 返回成功的json格式
 * @param array $data   返回的数据
 * @param string $msg    提示语
 * @param int    $code   code标识
 * @return json
 */
 function sucJson(array|object $data = [], string $msg = '操作成功', int $code = 200) :string
 {
    $result = array(
        'code' => $code,
        'msg' => $msg,
        'data' => empty($data) ? [] : $data,
    );
     return json_encode($result);
 }

/**
 * @desc 返回失败的json格式
 * @param string $msg    提示语
 * @param array $data   返回的数据
 * @param int   $code   code标识
 * @return json
 */
function errJson(string $msg = '操作失败', array $data = [], int $code = 300) :string
{
    $result = array(
        'code' => $code,
        'msg' => $msg,
        'data' => $data,
    );
    return json_encode($result);
}



/**
 * @desc 根据状态值返回json数据
 * @param bool $flag 状态值
 * @return json
 */
function tagJson(bool|int $flag) :string
{
    if (!$flag) {
        return errJson();
    }
    return sucJson();
}
/**
 * @desc 返回随机数
 * @param int $len  随机数长度
 * @param int $type 1:数字+大写字母+小写字母; 2:数字加大写字母加小写字母加特殊字符
 * @return string
 */
function comRand(int $len = 5, int $type = 1) :string
{
    $member  = '0123456789';
    $lLetter = 'abcdefghijklmnopqrstuvwxyz';
    $uLetter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $special = "~!@#%^&*.,";
    $string = '';
    switch($type) {
        case 1:
            $string = substr(str_shuffle($member . $lLetter . $uLetter),0, $len);
            break;
        case 2:
            $string = substr(str_shuffle($member . $lLetter . $uLetter, $special),0, $len);
            break;
    }
    return $string;
}


/**
 * @desc CURL发送请求
 * @param string $url 地址
 * @param array|null $data 参数
 * @param array $opts CURL配置
 * @return string
 */
function curl(string $url, array $data = null, array $opts = array()) :string
{
    $ch = curl_init($url);
    //自定义CURL配置
    $custom_opts = array(
        CURLOPT_SSL_VERIFYPEER => FALSE, //禁用后cURL将终止从服务端进行验证
        CURLOPT_SSL_VERIFYHOST => FALSE, //禁用后cURL将终止从服务端进行验证
        CURLOPT_RETURNTRANSFER => TRUE,  //将获取的信息以文件流的形式返回，而不是直接输出
        CURLOPT_HEADER         => FALSE, //启用时会将头文件的信息作为数据流输出
    );
    if (!empty($opts)) {
        foreach ($opts as $key => $value) {
            $custom_opts[$key] = $value;
        }
    }
    foreach ($custom_opts as $key => $value) {
        curl_setopt($ch, $key, $value);
    }
    //POST请求
    if ($data !== null) {
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

/**
 * @desc 生成或者验证密码
 * @param  string $passwd  秘钥
 * @param  string $salt    安全码
 * @return string 加密后的秘钥
 */
function comPasswd(string $passwd, string $salt='a@&*20233012') :string
{
    return md5(md5( md5($passwd) . md5($salt)));
}

/**
 * @desc   获取guid
 * @param  int $len 长度
 * @param  string $type 转换大小写
 * @author flyer
 */
function guid($len = 32, $type = 'lower'): string
{
    //产生全球唯一GUID，一共使用8个mt_rand，结合microtime（当前时间毫秒级），以及服务器硬件ID
    $guid = sprintf('%04x%04x%04x%04x%04x%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)) . md5((function_exists('zend_get_id') ? implode(',', zend_get_id(true)) : uniqid(mt_rand(), true)) . microtime());

    if ($type == 'upper') {
        $guid = strtoupper($guid);
    }
    return substr($guid, 0, $len);
}

/**
 * @desc   列表转树形结构
 * @param  array $list   列表数据
 * @param  int $root     顶级ID值
 * @param  string $pk    主键
 * @param  string $pid   父级ID键
 * @param  string $child 子节点键
 * @author flyer
 **/
function list_to_tree(array $list, int $root = 0, string $pk = 'id', string $pid = 'parent_id', string $child = 'children') :array
{
    // 创建Tree
    $tree = array();
    if (is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = &$list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = 0;
            if (isset($data[$pid])) {
                $parentId = $data[$pid];
            }
            if ((string)$root == $parentId) {
                $tree[] = &$list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent = &$refer[$parentId];
                    $parent[$child][] = &$list[$key];
                }
            }
        }
    }
    return $tree;
}


/**
 * @desc   树转数组
 * @param  array $tree 树
 * @param  string $children 子节点键名
 * @author flyer
 **/
function treeToList(array $tree = [], string $children = 'children') :array
{
    if (empty($tree) || !is_array($tree)) {
        return $tree;
    }
    $arrRes = [];
    foreach ($tree as $k => $v) {
        $arrTmp = $v;
        unset($arrTmp[$children]);
        $arrRes[] = $arrTmp;
        if (!empty($v[$children])) {
            $arrTmp = treeToList($v[$children]);
            $arrRes = array_merge($arrRes, $arrTmp);
        }
    }
    return $arrRes;
}

/**
 * @desc   获取输入数据
 * @param string $key
 * @param any $default
 * @return array|int|string|null
 * @author flyer
 */
function input(string $key = '', $default = null)
{
    if (empty($key)) {
        return request()->post();
    } else {
        return request()->post($key, $default);
    }
}

/**
 * @desc   异常提示信息
 * param   Exception $e 异常
 * @author flyer
 */
function eMessage(\Exception $e): string
{
    return $e->getFile() . ' Line:' . $e->getLine() . ' Error:' . $e->getMessage();
}



