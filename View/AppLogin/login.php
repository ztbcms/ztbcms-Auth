<script src="{$config_siteurl}statics/admin/theme/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>
    var login_code = '{$login_code}';
    var redirect = '{$redirect}';
    $.ajax({
        url: '{:U("Auth/AppLogin/loginByLoginCode")}',
        data: {login_code: login_code},
        type: 'post',
        dataType: 'json',
        success: function(res){
            if(res.status){
                //保存access_token
                localStorage.setItem('access_token', res.data.access_token);
            }
            window.location.replace(redirect);
        }
    });
</script>
