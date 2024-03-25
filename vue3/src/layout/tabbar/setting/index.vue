<script setup lang='ts'>
import {useRouter} from 'vue-router'
let $router = useRouter()
//获取小仓库
import useLayOutSettingStore from '@/store/modules/setting';
let layoutSettingStore = useLayOutSettingStore();

//获取用户相关的仓库
import useUserStore from '@/store/modules/user';
let userStore = useUserStore();
userStore.avatar = '/api' + userStore.avatar

//刷新按钮
const updateRefresh = () => {
    layoutSettingStore.refresh = !layoutSettingStore.refresh;
}

//全屏按钮
const fullScreen = () => {
    //DOM对象属性，用来判断当前是否是全屏，是的话true，否的话null
    let full = document.fullscreenElement;
    //切换全屏模式
    if (!full) {
        document.documentElement.requestFullscreen();
    } else{
        document.exitFullscreen();
    }
}

//退出登录
const logout = () => {
    //1、向服务器发送请求，退出登录
    //2、仓库中用户相关数据清空
    userStore.userLogout();
    //3、跳转到登录页
    $router.push({path:'/login'});
}


</script>

<template>
        <el-button type="primary" size="small" icon="Refresh" circle @click="updateRefresh"></el-button>
        <el-button type="primary" size="small" icon="FullScreen" circle @click="fullScreen"></el-button>
        <!-- <el-button type="primary" size="small" icon="Setting" circle></el-button> -->
        <img v-if="userStore.avatar" :src="userStore.avatar" style="width:25px;height:25px;margin:0 20px;border-radius: 50%;" />
        <el-icon v-else size="20" color="#2a313a" style="margin:0 5px 0 15px"><Avatar /></el-icon> 
        <!--退出登录的下拉菜单-->
        <el-dropdown>
            <span class="el-dropdown-link">
            {{ userStore.username }}
            <el-icon class="el-icon--right">
                <arrow-down />
            </el-icon>
            </span>
            <template #dropdown>
            <el-dropdown-menu>
                <el-dropdown-item @click="logout">退出登录</el-dropdown-item>
            </el-dropdown-menu>
            </template>
        </el-dropdown>
</template>

<style lang='scss' scoped>

</style>