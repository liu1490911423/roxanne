<?php
/**
 * Copyright (C), 2016-2018, Shall Buy Life info. Co., Ltd.
 * FileName: SharedCacheTest.php
 * Description: 说明
 *
 * @author Morning Start
 * @Create Date    2020/11/10 2:58 下午
 * @Update Date    2020/11/10 2:58 下午 By Morning Start
 * @version v1.0
 */

namespace Tests;

use Tests\TestCase;

class SharedCacheTest extends TestCase
{
    public function testDemo()
    {
        \SharedCache::getServer('activity')->putActivityGoods('ceshi', [0,11,2,3,4]);
        $data = \SharedCache::getServer('activity')->getActivityGoods('ceshi');
        dd($data);
        $this->assertTrue(true);
    }
}
