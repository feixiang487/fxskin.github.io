<?php
/**
 * @Author: printempw
 * @Date:   2016-03-18 22:50:25
 * @Last Modified by:   printempw
 * @Last Modified time: 2016-06-12 11:09:02
 */
require "../libraries/session.inc.php";
if (!$user->is_admin) Utils::redirect('../index.php', '看起来你并不是管理员');
View::show('admin/header', array('page_title' => "站点配置"));
$db = new Database\Database('users');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            站点配置
            <small>Options</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">常规选项</h3>
                    </div><!-- /.box-header -->
                    <form method="post" action="options.php">
                        <input type="hidden" name="option" value="general">
                        <div class="box-body">
                            <?php
                            if (isset($_POST['option']) && ($_POST['option'] == "general")) {
                                // pre-set user_can_register because it will not be posted if not checked
                                if (!isset($_POST['user_can_register'])) $_POST['user_can_register'] = '0';
                                foreach ($_POST as $key => $value) {
                                    // remove slash if site_url is ended with slash
                                    if ($key == "site_url" && preg_match("/.*(\/)$/", $value)) {
                                        $value = substr($value, 0, -1);
                                    }
                                    if ($key != "option" && $key != "submit") {
                                        Option::set($key, $value);
                                    }
                                }
                                echo '<div class="callout callout-success">设置已保存。</div>';
                            } ?>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="key">站点标题</td>
                                        <td class="value">
                                           <input type="text" class="form-control" name="site_name" value="<?php echo Option::get('site_name'); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="key">站点描述</td>
                                        <td class="value">
                                           <input type="text" class="form-control" name="site_description" value="<?php echo Option::get('site_description'); ?>">
                                        </td>
                                    </tr>
                                    <tr data-toggle="tooltip" data-placement="bottom" title="以 http:// 开头，带上子目录（如果有）">
                                        <td class="key">站点地址（URL）</td>
                                        <td class="value">
                                           <input type="text" class="form-control" name="site_url" value="<?php echo Option::get('site_url'); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="key">开放注册</td>
                                        <td class="value">
                                            <label for="user_can_register">
                                                <input <?php echo (Option::get('user_can_register') == '1') ? 'checked="true"' : ''; ?> type="checkbox" id="user_can_register" name="user_can_register" value="1">任何人都可以注册
                                            </label>
                                        </td>
                                    </tr>
                                    <tr data-toggle="tooltip" data-placement="bottom" title="默认皮肤文件请放置于 textures 目录下，并在此键入文件名。默认留空。">
                                        <td class="key">新用户默认皮肤</td>
                                        <td class="value">
                                           <input type="text" class="form-control" name="user_default_skin" value="<?php echo Option::get('user_default_skin'); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="key">每个 IP 限制注册数</td>
                                        <td class="value">
                                           <input type="text" class="form-control" name="regs_per_ip" value="<?php echo Option::get('regs_per_ip'); ?>">
                                        </td>
                                    </tr>
                                    <tr data-toggle="tooltip" data-placement="bottom" title="PHP 限制：<?php echo ini_get('post_max_size'); ?>，定义在 php.ini 中。">
                                        <td class="key">最大允许上传大小</td>
                                        <td class="value">
                                            <div class="input-group">
                                            <input type="text" class="form-control" name="upload_max_size" value="<?php echo Option::get('upload_max_size'); ?>">
                                            <span class="input-group-addon">KB</span>
                                          </div>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="key">首选 JSON API</td>
                                        <td class="value">
                                           <select class="form-control" name="api_type">
                                                <option <?php echo (Option::get('api_type') == '0') ? 'selected="selected"' : ''; ?> value="0">CustomSkinLoader API</option>
                                                <option <?php echo (Option::get('api_type') == '1') ? 'selected="selected"' : ''; ?> value="1">UniversalSkinAPI</option>
                                           </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="key">站点公告</td>
                                        <td class="value">
                                           <textarea name="announcement" class="form-control" rows="3"><?php echo Option::get('announcement'); ?></textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" name="submit" class="btn btn-primary">提交</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">数据对接配置</h3>
                    </div><!-- /.box-header -->
                    <form method="post" action="options.php">
                        <input type="hidden" name="option" value="adapter">
                        <div class="box-body">
                            <?php
                            if (isset($_POST['option']) && ($_POST['option'] == "adapter")) {
                                foreach ($_POST as $key => $value) {
                                    if ($key != "option" && $key != "submit") {
                                        Option::set($key, $value);
                                        //echo $key."=".$value."<br />";
                                    }
                                }
                                echo '<div class="callout callout-success">设置已保存。</div>';
                            } else {
                                echo '<div class="callout callout-warning">如果你不知道下面这些是干什么的，请不要继续编辑。</div>';
                            } ?>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="key">数据对接适配器</td>
                                        <td class="value">
                                           <select class="form-control" name="data_adapter">
                                                <option <?php echo (Option::get('data_adapter') == '') ? 'selected="selected"' : ''; ?> value="">不进行数据对接</option>
                                                <option <?php echo (Option::get('data_adapter') == 'Authme') ? 'selected="selected"' : ''; ?> value="Authme">Authme</option>
                                                <option <?php echo (Option::get('data_adapter') == 'Crazy') ? 'selected="selected"' : ''; ?> value="Crazy">CrazyLogin</option>
                                                <option <?php echo (Option::get('data_adapter') == 'Discuz') ? 'selected="selected"' : ''; ?> value="Discuz">Discuz</option>
                                                <option <?php echo (Option::get('data_adapter') == 'Phpwind') ? 'selected="selected"' : ''; ?> value="Phpwind">Phpwind</option>
                                           </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="key">对接数据表名</td>
                                        <td class="value">
                                           <input type="text" class="form-control" name="data_table_name" value="<?php echo Option::get('data_table_name'); ?>">
                                        </td>
                                    </tr>
                                    <tr data-toggle="tooltip" data-placement="bottom">
                                        <td class="key">密码加密算法</td>
                                        <td class="value">
                                           <select class="form-control" name="encryption">
                                               <option <?php echo (Option::get('encryption') == 'MD5') ? 'selected="selected"' : ''; ?> value="MD5">MD5</option>
                                               <option <?php echo (Option::get('encryption') == 'SALTED2MD5') ? 'selected="selected"' : ''; ?> value="SALTED2MD5">SALTED2MD5</option>
                                               <option <?php echo (Option::get('encryption') == 'SHA256') ? 'selected="selected"' : ''; ?> value="SHA256">SHA256</option>
                                               <option <?php echo (Option::get('encryption') == 'CrazyCrypt1') ? 'selected="selected"' : ''; ?> value="CrazyCrypt1">CrazyCrypt1</option>
                                          </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="key">对接数据表用户名字段</td>
                                        <td class="value">
                                           <input data-toggle="tooltip" data-placement="bottom" type="text" class="form-control" name="data_column_uname" value="<?php echo Option::get('data_column_uname'); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="key">对接数据表密码字段</td>
                                        <td class="value">
                                           <input data-toggle="tooltip" data-placement="bottom" type="text" class="form-control" name="data_column_passwd" value="<?php echo Option::get('data_column_passwd'); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="key">对接数据表 IP 字段</td>
                                        <td class="value">
                                           <input data-toggle="tooltip" data-placement="bottom" type="text" class="form-control" name="data_column_ip" value="<?php echo Option::get('data_column_ip'); ?>">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" name="submit" class="btn btn-primary">提交</button>
                        </div>
                    </form>
                </div>

                <div class="box box-default collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">如何填写数据对接配置？</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                        <!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <h4>对接数据表名</h4>
                        <p>所需要对接的程序的用户信息数据表，Authme 默认为 `authme`，CrazyLogin 默认为 `crazylogin_accounts`，Discuz 默认为 `pre_ucenter_members`，Phpwind 默认为 `windid_user`。请根据实际情况填写。</p>
                        <h4>密码加密算法</h4>
                        <p>皮肤站默认为 MD5。Authme 默认为 SHA256，CrazyLogin 为 CrazyCrypt1，Discuz 和 Phpwind 为 SALTED2MD5。没有需要的加密算法？请联系作者。</p>
                        <h4>对接数据表用户名字段</h4>
                        <p>如果你没有修改插件配置的话，请保持默认（`username`）。CrazyLogin 的话请将此字段改为 `name`。</p>
                        <h4>对接数据表密码字段</h4>
                        <p>同上，不要瞎球改。默认为 `password`</p>
                        <h4>对接数据表 IP 字段</h4>
                        <p>CrazyLogin 的话请将此字段改为 `ips`，Discuz 和 Phpwind 请改为 `regip`。</p>
                    </div>
                </div>
            </div>

        </div>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php
View::show('admin/footer'); ?>
