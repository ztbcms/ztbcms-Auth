<?php if (!defined('CMS_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
    <Admintemplate file="Common/Nav"/>
    <div class="h_a">添加应用</div>
    <form action="" method="post">
        <div class="table_full">
            <table class="table_form" width="100%" cellspacing="0">
                <tbody>
                <tr>
                    <th width="150"><b style="color:red;">*</b><strong> 应用名称 ：</strong></th>
                    <td><input name="app_name" id="app_name" class="input" type="text" size="30"></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="btn_wrap">
            <div class="btn_wrap_pd">
                <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
            </div>
        </div>
    </form>
</div>
</body>
</html>
