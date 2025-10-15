<?php

/**
 * 批量创建用户前的检查。
 * Check before batch creating users.
 *
 * @param  array  $users
 * @param  string $verifyPassword
 * @access public
 * @return bool
 */
public function checkBeforeBatchCreate($users, $verifyPassword)
{
    if(!$users) return true;

    $accounts = array_map(function($user){return $user->account;}, $users);
    $accounts = $this->dao->select('account')->from(TABLE_USER)->where('account')->in($accounts)->fetchPairs();

    foreach($users as $key => $user)
    {
        if(empty($user->account)) continue;

        if(strtolower($user->account) == 'guest') dao::$errors["account[{$key}]"][] = $this->lang->user->error->reserved;
        if(isset($accounts[$user->account])) dao::$errors["account[{$key}]"][] = sprintf($this->lang->error->unique, $this->lang->user->account, $user->account);
        if(!validater::checkAccount($user->account)) dao::$errors["account[{$key}]"][] = sprintf($this->lang->error->account, $this->lang->user->account);
        if(empty($user->realname)) dao::$errors["realname[{$key}]"][] = sprintf($this->lang->error->notempty, $this->lang->user->realname);
        if(empty($user->visions)) dao::$errors["visions[{$key}][]"][] = sprintf($this->lang->error->notempty, $this->lang->user->visions);
        if(empty($user->password)) dao::$errors["password[{$key}]"][] = sprintf($this->lang->error->notempty, $this->lang->user->password);
        if(!empty($user->password) && !validater::checkReg($user->password, '|(.){6,}|')) dao::$errors["password[{$key}]"][] = $this->lang->user->error->password;
        if(!empty($user->email) && !validater::checkEmail($user->email)) dao::$errors["email[{$key}]"][] = sprintf($this->lang->error->email, $this->lang->user->email);
        if(!empty($user->phone) && !validater::checkPhone($user->phone)) dao::$errors["phone[{$key}]"][] = sprintf($this->lang->error->phone, $this->lang->user->phone);
        if(!empty($user->mobile) && !validater::checkMobile($user->mobile)) dao::$errors["mobile[{$key}]"][] = sprintf($this->lang->error->mobile, $this->lang->user->mobile);
        if(empty($user->gender)) dao::$errors["gender[{$key}]"][] = sprintf($this->lang->error->notempty, $this->lang->user->gender);
        if(empty($user->scoreStatistic)) dao::$errors["scoreStatistic[{$key}]"][] = sprintf($this->lang->error->notempty, $this->lang->user->scoreStatistic);

        /* 检查密码强度是否符合安全设置。*/
        /* Check if the password strength meets the security settings. */
        if(isset($this->config->safe->mode) && $this->computePasswordStrength($user->password) < $this->config->safe->mode) dao::$errors["password[{$key}]"][] = $this->lang->user->error->weakPassword;

        /* 检查明文的弱密码。*/
        /* Check the weak password in clear text. */
        if(!empty($this->config->safe->changeWeak))
        {
            if(!isset($this->config->safe->weak)) $this->app->loadConfig('admin');
            if(strpos(",{$this->config->safe->weak},", ",{$user->password},") !== false) dao::$errors["password[{$key}]"][] = sprintf($this->lang->user->error->dangerPassword, $this->config->safe->weak);
        }

        $accounts[$user->account] = $user->account;
    }

    $this->checkVerifyPassword($verifyPassword);

    return !dao::isError();
}
