<script setup lang='ts'>
    //获取路由对象
    import {useRoute} from 'vue-router'
    //引入左侧菜单logo子组件
    import Logo from "./logo/index.vue"
    //引入菜单组件
    import Menu from "./menu/index.vue"
    //右侧内容展示区
    import Main from "./main/index.vue"
    //引入顶部组件
    import Tabbar from "./tabbar/index.vue"

    //获取用户相关的小仓库
    import useUserStore from "@/store/modules/user";
    let userStore = useUserStore();

    //获取设置菜单是否折叠的小仓库
    import userlayOutSettingStore from '@/store/modules/setting'
    let layOutSettingStore = userlayOutSettingStore();

    //获取路由对象(根据路由地址激活对应的菜单属性使用该项)
    let $route = useRoute();

    //默认展开所有路由
    let openeds = ['/system'];
</script>

<template>
    <div class="layout_container">
        <!--左侧菜单-->
        <div class="layout_slider" :class="{fold:layOutSettingStore.fold?true:false}">
            <!--logo组件-->
            <Logo></Logo>
            <!--滚动组件-->
            <el-scrollbar class="scrollbar">
                <!--菜单组件-->
                <el-menu :collapse="layOutSettingStore.fold?true:false" :default-active="$route.path" :default-openeds="openeds" >
                    <!--根据路由动态生成菜单-->
                    <Menu :menuList="userStore.menuRoutes"></Menu>
                   
                </el-menu>
            </el-scrollbar>
        </div>
        <!--顶部导航-->
        <div class="layout_tabbar" :class="{fold:layOutSettingStore.fold?true:false}">
            <Tabbar></Tabbar>
        </div>
        <!--内容展示区-->
        <div class="layout_main" :class="{fold:layOutSettingStore.fold?true:false}">
            <Main></Main>
        </div>
    </div>
</template>

<style lang='scss' scoped>
    .layout_container {
        width: 100%;
        height: 100vh;
        .layout_slider {  //左侧菜单样式
            width: 200px;
            height: 100vh;
            background-color: $leftBackgroud;
            transition: all 0.3s;
            .scrollbar {
                width: 100%;
                height: calc(100vh - 50px);
                .el-menu {
                    border-right: none;
                }
            }
            &.fold{
                width: 50px;
            }


        }
        .layout_tabbar{
            width: calc(100% - 200px);
            height:  50px;
            position: fixed;
            top: 0px;
            left: 200px;
            //background-color: #856d72;
            background-color:$topBackground;
            transition: all 0.3s;
            &.fold{
                width: calc(100vw - 50px);
                left:50px;
            }
     
        }
        
        .layout_main {
            position: absolute;
            width:calc(100% - 200px);
            height:calc(100vh - 50px);
            background-color: $layoutMainBackgroupd;
            left:200px;
            top: 50px;
            padding: 10px;
            overflow: auto;
            //transition: all 0.3s;
            &.fold {
                width:calc(100% - 50px);
                left:50px;
            }
        } 
        .el-menu{
            --el-menu-hover-text-color:rgb(220, 69, 69);    //悬停状态下字体颜色
            --el-menu-text-color:white;        //默认状态下字体颜色
            //--el-menu-active-color:rgb(52, 3, 3);  //激活状态下字体颜色
            --el-menu-bg-color:$leftBackgroud;   //整个菜单栏背景色
        }
        
       
       
    }
</style>

<style lang='scss'>
    
    .el-sub-menu__title:hover,.el-menu-item:hover {
          color: #6681FA;
    }
</style>