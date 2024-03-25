<script setup lang='ts'>
//引入标题与logo配置的文件
import setting from '@/setting'
</script>

<template>
    <div class="logo" v-if="setting.logoIsShow">
        <img :src="setting.logo" alt="logo" class="imgOne"/>
        <img src="../../assets/images/vip.png"  class="imgTwo"/>
        <p>{{setting.title}}</p>
    </div>
</template>

<style lang='scss' scoped>
    .logo {
        width:100%;
        height: 50px;
        color:white;
        display: flex;
        align-items: center;
        padding: 20px;
        .imgOne {
            margin-left: 10px;
            width:37px;
            height: 37px;
        }
        .imgTwo {
            margin-left: 10px;
            width:37px;
            height: 15px;
        }
        p {
            font-size: 18px;
            margin-left:10px;
        }
    }
</style>