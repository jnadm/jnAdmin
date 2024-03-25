<script setup lang='ts'>
    import {useRoute} from 'vue-router'
    //读取小仓库用于控制菜单是否收起的值
    import userlayOutSettingStore from '@/store/modules/setting';    
    let layOutSettingStore = userlayOutSettingStore();

    //获取路由对象
    let $route = useRoute();

    //点击控制菜单图标设置控制变量值
    const changeIcon = ()=>{
        layOutSettingStore.fold = !layOutSettingStore.fold
    }
</script>

<template>
    <!--左侧伸缩图标-->
    <el-icon  style="margin-right:10px" @click="changeIcon">
        <!-- <Expand /> -->
        <component :is=" layOutSettingStore.fold?'Fold':'Expand' "></component>
    </el-icon>
    <!--左侧面包屑-->
    <el-breadcrumb separator-icon="ArrowRight">
        <el-breadcrumb-item v-for="(item, index) in $route.matched" :key="index" v-show="item.meta.title" :to="item.path">
            <el-icon style="vertical-align: middle;">
                <component :is="item.meta.icon"></component>
            </el-icon>
            <span style="margin:0 2px">{{item.meta.title}}</span>
        </el-breadcrumb-item>
    </el-breadcrumb>
</template>

<style lang='scss' scoped>

</style>