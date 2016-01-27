<?php
    /*
     * 生成指定长度随机字符
     * $type : alpha 不包含数字的字符
     *         alnum 包含数字以及字符
     *         numeric 包含10个数字
     *         nozero 不包含0 的9个数字
     */
function creat_rand_str($len=8,$type='alnum'){
        switch($type){
            case 'alpha':
                $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'alnum':
                $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'numeric':
                $pool = '0123456789';
                break;
            case 'nozero':
                $pool = '123456789';
                break;
            default :
                return 'type no exist';
        }
        $str = '';
        for($i=0;$i<$len;$i++){
            $str .=substr($pool,mt_rand(0,strlen($pool)-1),1);
        }
        return $str;
}