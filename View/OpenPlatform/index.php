<?php if (!defined('CMS_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
    <Admintemplate file="Common/Nav"/>
    <div class="h_a">应用列表</div>
    <form onsubmit="return false;">
        <div class="table_full" id="app">
            <table class="table_form" width="100%" cellspacing="0">
                <tbody>
                <tr>
                    <th>ID</th>
                    <th>应用名称</th>
                    <th>APP_ID</th>
                    <th>APP_SECRET</th>
                    <th>操作</th>
                </tr>
                <tr v-for="item in lists">
                    <td>{{ item.id }}</td>
                    <td>{{ item.app_name }}</td>
                    <td>{{ item.app_id }}</td>
                    <td>
                        <a @click="resetAppSecret(item.id)" href="javascript:">重置</a>
                    </td>
                    <td>
                        <a v-if="item.is_allow_auth == 1" @click="updateOpenAuth(item.id, 0)" class="btn btn-danger" href="javascript:">取消授权</a>
                        <a v-else @click="updateOpenAuth(item.id, 1)" class="btn btn-success" href="javascript:">允许授权</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>
</body>
<script>
    new Vue({
        el: '#app',
        data: {
            lists: []
        },
        methods: {
            getList: function(){
                var that = this;
                var url = '{:U("Auth/OpenPlatform/getOpenPlatformList")}';
                var data = {page: 1, limit: 999};
                $.get(url, data, function(res){
                    if(res.status){
                        that.lists = res.data;
                    }
                }, 'json');
            },
            resetAppSecret: function(id){
                var that = this;
                layer.confirm('是否确定重置APP_SECRET', {title: '重置'}, function(){
                    var url = '{:U("Auth/OpenPlatform/resetAppSecret")}';
                    var data = {id: id};
                    $.post(url, data, function(res){
                        if(res.status){
                            layer.alert(res.data, {title: '请保存APP_SECRET'});
                        }else{
                            layer.msg(res.msg);
                        }
                    }, 'json');
                });
            },
            updateOpenAuth: function(id, value){
                var that = this;
                var url = '{:U("Auth/OpenPlatform/updateOpenAuth")}';
                var data = {id: id, value: value};
                $.post(url, data, function(res){
                    if(res.status){
                        layer.msg(res.msg, {time: 1000}, function(){
                            that.getList();
                        });
                    }else{
                        layer.msg(res.msg);
                    }
                }, 'json');
            }
        },
        mounted: function(){
            this.getList();
        }
    });
</script>
</html>
