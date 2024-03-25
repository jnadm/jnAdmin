<script setup lang='ts'>
//引入左侧面包屑
import Breadcrumb from './breadcrumb/index.vue'
//引入右侧按钮设置
import Setting  from './setting/index.vue';
</script>

<template>
    <div class="tabbar">
        <div class="tabbar_left">
           <Breadcrumb></Breadcrumb>
        </div> 
        <div class="tabbar_right">
            <Setting></Setting>
        </div>
    </div>
</template>

<style lang='scss' scoped>
    .tabbar{
        width:100%;
        height:100%;
        background-color:white;
        display:flex;
        justify-content:space-between;
    }
    .tabbar_left{
        display:flex;
        margin-left:20px;
        justify-content:center;
        align-items:center;
        font-size:13px;
        .item{
            margin-left:10px;
        }
    }
    .tabbar_right{
        display:flex;
        justify-content:center;
        align-items:center;
        font-size:13px;
        .item{
            margin-left:15px;
        }
    }
</style>