<?php

namespace common\component\oauth;

/**
 * Sina Weibo OAuth
 * @author xjflyttp <xjflyttp@gmail.com>
 */
interface IAuth {

    /**
     * get User Info
     * @return []
     */
    public function getUserInfo();
}
